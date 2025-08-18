<?php
require_once 'Config/database.php';

class RecompensaModel {
  private $db;
  public function __construct(){ $this->db = Database::conectar(); }

  public function listarDisponibles(){
    $res = $this->db->query(
      "SELECT ID_Recompensa, Nombre, Descripcion, Cantidad_Disponible, Puntos_Requeridos
       FROM RECOMPENSA
       WHERE ID_Estado=1 AND Cantidad_Disponible>0
       ORDER BY Puntos_Requeridos ASC"
    );
    return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
  }

  // ===== saldo =====
  private function saldoActual(int $userId): int {
    $stmt = $this->db->prepare(
      "SELECT COALESCE(SUM(Puntos_Modificados),0) AS s
       FROM HISTORIAL_PUNTOS
       WHERE ID_Usuario=? AND ID_Estado=1"
    );
    $stmt->bind_param('i',$userId);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    return (int)($row['s'] ?? 0);
  }
  public function saldoUsuario(int $userId): int { return $this->saldoActual($userId); }

  // ===== canje =====
  private function nextCanjeId(): int {
    $r = $this->db->query('SELECT COALESCE(MAX(ID_Canje),0)+1 AS nxt FROM CANJE_RECOMPENSA');
    return (int)$r->fetch_assoc()['nxt'];
  }

  public function canjear(int $userId, int $idRecompensa, int $cantidad=1): bool {
    if ($cantidad <= 0) throw new Exception('Cantidad inválida');
    $this->db->begin_transaction();
    try {
      $stmt = $this->db->prepare(
        'SELECT Cantidad_Disponible, Puntos_Requeridos FROM RECOMPENSA WHERE ID_Recompensa=? AND ID_Estado=1 FOR UPDATE'
      );
      $stmt->bind_param('i',$idRecompensa);
      $stmt->execute();
      $r = $stmt->get_result()->fetch_assoc();
      if (!$r) throw new Exception('Recompensa no válida');

      $stock  = (int)$r['Cantidad_Disponible'];
      $precio = (int)$r['Puntos_Requeridos'];
      if ($stock < $cantidad) throw new Exception('Stock insuficiente');

      $saldo = $this->saldoActual($userId);
      $costo = $precio * $cantidad;
      if ($saldo < $costo) throw new Exception('Puntos insuficientes');

      // baja stock
      $stmt2 = $this->db->prepare('UPDATE RECOMPENSA SET Cantidad_Disponible = Cantidad_Disponible - ? WHERE ID_Recompensa=?');
      $stmt2->bind_param('ii', $cantidad, $idRecompensa);
      $stmt2->execute();

      // registra canje
      $newId = $this->nextCanjeId();
      $stmt3 = $this->db->prepare(
        'INSERT INTO CANJE_RECOMPENSA (ID_Canje, Fecha_Canje, Cantidad, ID_Recompensa, ID_Usuario, ID_Estado)
         VALUES (?, CURRENT_DATE, ?, ?, ?, 1)'
      );
      $stmt3->bind_param('iiii', $newId, $cantidad, $idRecompensa, $userId);
      $stmt3->execute();

      // historial negativo
      $nuevoSaldo = $saldo - $costo;
      $stmt4 = $this->db->prepare(
        'INSERT INTO HISTORIAL_PUNTOS (Fecha, Descripcion, Puntos_Modificados, Puntos_Totales, ID_Usuario, ID_Estado)
         VALUES (CURRENT_DATE, ?, ?, ?, ?, 1)'
      );
      $desc = 'Canje recompensa #'.$idRecompensa.' x'.$cantidad;
      $neg = -$costo;
      $stmt4->bind_param('siii', $desc, $neg, $nuevoSaldo, $userId);
      $stmt4->execute();

      $this->db->commit();
      return true;
    } catch (Throwable $e){
      $this->db->rollback(); throw $e;
    }
  }

  // ===== MIS CANJES =====
  public function listarCanjesPorUsuario(int $userId){
    $stmt = $this->db->prepare(
      "SELECT c.ID_Canje, c.Fecha_Canje, c.Cantidad, c.ID_Estado,
              r.ID_Recompensa, r.Nombre, r.Puntos_Requeridos
       FROM CANJE_RECOMPENSA c
       JOIN RECOMPENSA r ON r.ID_Recompensa = c.ID_Recompensa
       WHERE c.ID_Usuario=?
       ORDER BY c.Fecha_Canje DESC, c.ID_Canje DESC"
    );
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $res = $stmt->get_result();
    return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
  }
    // Listar todas las recompensas (para admin)
  public function listarTodas(){
    $res = $this->db->query(
      "SELECT ID_Recompensa, Nombre, Descripcion, Cantidad_Disponible, Puntos_Requeridos, ID_Estado
       FROM RECOMPENSA
       ORDER BY ID_Estado DESC, Puntos_Requeridos ASC, ID_Recompensa DESC"
    );
    return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
  }

  // Obtener una recompensa
  public function obtener($id){
    $stmt = $this->db->prepare(
      "SELECT ID_Recompensa, Nombre, Descripcion, Cantidad_Disponible, Puntos_Requeridos, ID_Estado
       FROM RECOMPENSA WHERE ID_Recompensa=? LIMIT 1"
    );
    $stmt->bind_param('i',$id);
    $stmt->execute();
    $res = $stmt->get_result();
    return $res ? $res->fetch_assoc() : null;
  }

  // Crear recompensa
  public function crear($nombre,$descripcion,$puntos,$cantidad,$estado=1){
    $stmt = $this->db->prepare(
      "INSERT INTO RECOMPENSA (Nombre,Descripcion,Cantidad_Disponible,Puntos_Requeridos,ID_Estado)
       VALUES (?,?,?,?,?)"
    );
    $stmt->bind_param('ssiii',$nombre,$descripcion,$cantidad,$puntos,$estado);
    if(!$stmt->execute()){ throw new Exception("Error al crear recompensa"); }
    return $this->db->insert_id;
  }

  // Actualizar recompensa
  public function actualizar($id,$nombre,$descripcion,$puntos,$cantidad,$estado){
    $stmt = $this->db->prepare(
      "UPDATE RECOMPENSA
       SET Nombre=?, Descripcion=?, Cantidad_Disponible=?, Puntos_Requeridos=?, ID_Estado=?
       WHERE ID_Recompensa=?"
    );
    $stmt->bind_param('ssiiii',$nombre,$descripcion,$cantidad,$puntos,$estado,$id);
    if(!$stmt->execute()){ throw new Exception("Error al actualizar recompensa"); }
    return true;
  }

  


public function desactivar($id){
  $inactivo = $this->getEstadoId('Inactivo'); 
  $stmt = $this->db->prepare("UPDATE RECOMPENSA SET ID_Estado=? WHERE ID_Recompensa=?");
  $stmt->bind_param('ii', $inactivo, $id);
  if(!$stmt->execute()){ throw new Exception("Error al desactivar recompensa"); }
  return true;
}
// Devuelve estados normalizados con clave 'Nombre' aunque la tabla no tenga esa columna
public function listarEstados(){
  $res = $this->db->query("SELECT * FROM estado ORDER BY ID_Estado");
  $rows = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];

  $prefer = ['Nombre','nombre','Descripcion','descripcion','Estado','estado','Detalle','detalle','Etiqueta','etiqueta','Titulo','titulo'];
  $out = [];

  foreach ($rows as $r) {
    $display = null;
    // 1) intenta nombres comunes
    foreach ($prefer as $k) {
      if (array_key_exists($k, $r) && trim((string)$r[$k]) !== '') { $display = $r[$k]; break; }
    }
    // 2) si no hay, toma la primera columna que no sea ID_Estado
    if ($display === null) {
      foreach ($r as $k=>$v) {
        if (strcasecmp($k,'ID_Estado') !== 0) { $display = $v; break; }
      }
    }
    // 3) fallback final
    if ($display === null) { $display = 'Estado '.(string)$r['ID_Estado']; }

    $out[] = [
      'ID_Estado' => (int)$r['ID_Estado'],
      'Nombre'    => (string)$display,
    ];
  }
  return $out;
}

// Busca el ID del estado por texto, probando varias columnas comunes.
// Si no encuentra coincidencia exacta, intenta por LIKE (case-insensitive).
private function getEstadoId($buscado){
  $buscado = trim((string)$buscado);
  if ($buscado === '') { throw new Exception("Nombre de estado vacío"); }

  $cols = ['Nombre','nombre','Descripcion','descripcion','Estado','estado','Detalle','detalle','Etiqueta','etiqueta','Titulo','titulo'];
  foreach ($cols as $col) {
   
    $check = $this->db->query("SHOW COLUMNS FROM estado LIKE '".$this->db->real_escape_string($col)."'");
    if ($check && $check->num_rows > 0) {
      $stmt = $this->db->prepare("SELECT ID_Estado FROM estado WHERE $col = ? LIMIT 1");
      $stmt->bind_param('s', $buscado);
      $stmt->execute();
      $res = $stmt->get_result()->fetch_assoc();
      if ($res) return (int)$res['ID_Estado'];

      $like = "%".$buscado."%";
      $stmt = $this->db->prepare("SELECT ID_Estado FROM estado WHERE $col LIKE ? ORDER BY ID_Estado LIMIT 1");
      $stmt->bind_param('s', $like);
      $stmt->execute();
      $res = $stmt->get_result()->fetch_assoc();
      if ($res) return (int)$res['ID_Estado'];
    }
  }


  $candidatos = $this->listarEstados();
  if (!empty($candidatos)) return (int)$candidatos[0]['ID_Estado'];

  throw new Exception("No se pudo determinar el ID del estado para '$buscado'");
}


}
