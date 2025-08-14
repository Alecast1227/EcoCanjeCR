<?php
require_once 'Config/database.php';

class MaterialRecicladoModel {
  private $db;
  public function __construct(){ $this->db = Database::conectar(); }

  public function listar(){
    $sql = "SELECT m.*, u.Nombre AS Usuario_Nombre
            FROM MATERIAL_RECICLADO m
            JOIN USUARIOS u ON u.ID_Usuario = m.ID_Usuario
            ORDER BY m.ID_Material_Reciclado DESC";
    $res = $this->db->query($sql);
    return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
  }

  public function listarPorUsuario(int $userId){
    $stmt = $this->db->prepare(
      "SELECT m.*, u.Nombre AS Usuario_Nombre
       FROM MATERIAL_RECICLADO m
       JOIN USUARIOS u ON u.ID_Usuario = m.ID_Usuario
       WHERE m.ID_Usuario=?
       ORDER BY m.ID_Material_Reciclado DESC"
    );
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $r = $stmt->get_result();
    return $r ? $r->fetch_all(MYSQLI_ASSOC) : [];
  }

  public function obtenerPorId(int $id){
    $stmt = $this->db->prepare("SELECT * FROM MATERIAL_RECICLADO WHERE ID_Material_Reciclado=?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $res = $stmt->get_result();
    return $res ? $res->fetch_assoc() : null;
  }

  // Alias para que el controller viejo no reviente
  public function obtener(int $id){
    return $this->obtenerPorId($id);
  }

  private function nextId(): int {
    $r = $this->db->query("SELECT COALESCE(MAX(ID_Material_Reciclado),0)+1 AS nxt FROM MATERIAL_RECICLADO");
    $row = $r ? $r->fetch_assoc() : ['nxt'=>1];
    return (int)$row['nxt'];
  }

  private function saldoActual(int $usuarioId): int {
    $stmt = $this->db->prepare(
      "SELECT COALESCE(SUM(Puntos_Modificados),0) AS s
       FROM HISTORIAL_PUNTOS
       WHERE ID_Usuario=? AND ID_Estado=1"
    );
    $stmt->bind_param('i', $usuarioId);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    return (int)($row['s'] ?? 0);
  }

  // Espera keys: Tipo_Material, Tamano, Peso, Fecha, Puntos, ID_Usuario, ID_Centro_Acopio, ID_Estado
  public function crear(array $d): int {
    $this->db->begin_transaction();
    try {
      $id = $this->nextId();
      $stmt = $this->db->prepare("
        INSERT INTO MATERIAL_RECICLADO
          (ID_Material_Reciclado, Tipo_Material, Peso, Fecha_Registro, Puntos_Asignados, ID_Usuario, ID_Centro_Acopio, ID_Estado)
        VALUES (?,?,?,?,?,?,?,?)
      ");
      $stmt->bind_param(
        "isdsiiii",
        $id,
        $d['Tipo_Material'],
        $d['Peso'],
        $d['Fecha'],
        $d['Puntos'],
        $d['ID_Usuario'],
        $d['ID_Centro_Acopio'],
        $d['ID_Estado']
      );
      $stmt->execute();

      // + historial
      $saldoPrev = $this->saldoActual((int)$d['ID_Usuario']);
      $nuevo = $saldoPrev + (int)$d['Puntos'];
      $desc = "Reciclaje: {$d['Tipo_Material']} ({$d['Tamano']})";
      $stmt2 = $this->db->prepare("
        INSERT INTO HISTORIAL_PUNTOS
          (Fecha, Descripcion, Puntos_Modificados, Puntos_Totales, ID_Usuario, ID_Estado)
        VALUES (?,?,?,?,?,1)
      ");
      $stmt2->bind_param("ssiii", $d['Fecha'], $desc, $d['Puntos'], $nuevo, $d['ID_Usuario']);
      $stmt2->execute();

      $this->db->commit();
      return $id;
    } catch(Throwable $e){
      $this->db->rollback(); throw $e;
    }
  }

  // Espera: ID, Tipo_Material, Tamano, Peso, Fecha, Puntos, ID_Centro_Acopio, ID_Estado
  public function actualizar(array $d): bool {
    $this->db->begin_transaction();
    try {
      $old = $this->obtenerPorId((int)$d['ID']);
      if (!$old) throw new Exception('No existe material');

      $stmt = $this->db->prepare("
        UPDATE MATERIAL_RECICLADO
           SET Tipo_Material=?, Peso=?, Fecha_Registro=?, Puntos_Asignados=?, ID_Centro_Acopio=?, ID_Estado=?
         WHERE ID_Material_Reciclado=?
      ");
      $stmt->bind_param(
        "sdsiiii",
        $d['Tipo_Material'],
        $d['Peso'],
        $d['Fecha'],
        $d['Puntos'],
        $d['ID_Centro_Acopio'],
        $d['ID_Estado'],
        $d['ID']
      );
      $ok = $stmt->execute();

      $delta = (int)$d['Puntos'] - (int)$old['Puntos_Asignados'];
      if ($delta !== 0) {
        $saldoPrev = $this->saldoActual((int)$old['ID_Usuario']);
        $nuevo = $saldoPrev + $delta;
        $desc = "Ajuste material #{$d['ID']}";
        $stmt2 = $this->db->prepare("
          INSERT INTO HISTORIAL_PUNTOS
            (Fecha, Descripcion, Puntos_Modificados, Puntos_Totales, ID_Usuario, ID_Estado)
          VALUES (?,?,?,?,?,1)
        ");
        $stmt2->bind_param("ssiii", $d['Fecha'], $desc, $delta, $nuevo, $old['ID_Usuario']);
        $stmt2->execute();
      }

      $this->db->commit();
      return $ok;
    } catch(Throwable $e){
      $this->db->rollback(); throw $e;
    }
  }

  public function eliminar(int $id): bool {
    $this->db->begin_transaction();
    try {
      $old = $this->obtenerPorId($id);
      if (!$old) throw new Exception('No existe');

      $stmt = $this->db->prepare("DELETE FROM MATERIAL_RECICLADO WHERE ID_Material_Reciclado=?");
      $stmt->bind_param("i", $id);
      $stmt->execute();

      $neg = -(int)$old['Puntos_Asignados'];
      $saldoPrev = $this->saldoActual((int)$old['ID_Usuario']);
      $nuevo = $saldoPrev + $neg;
      $desc = "Eliminación material #$id";
      $stmt2 = $this->db->prepare("
        INSERT INTO HISTORIAL_PUNTOS
          (Fecha, Descripcion, Puntos_Modificados, Puntos_Totales, ID_Usuario, ID_Estado)
        VALUES (CURRENT_DATE, ?, ?, ?, ?, 1)
      ");
      $stmt2->bind_param("siii", $desc, $neg, $nuevo, $old['ID_Usuario']);
      $stmt2->execute();

      $this->db->commit();
      return true;
    } catch(Throwable $e){
      $this->db->rollback(); throw $e;
    }
  }
}
