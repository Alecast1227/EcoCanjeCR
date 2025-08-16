<?php
require_once 'Config/database.php';

class AuthModel {
  private $db;
  public function __construct(){ $this->db = Database::conectar(); }

  public function findUserByEmail(string $email){
    $stmt = $this->db->prepare(
      "SELECT u.ID_Usuario, u.Nombre, u.Apellido, u.ID_Tipo_Usuario, u.ID_Estado, u.Fecha_Registro,
              c.Correo_Electronico
       FROM CORREO c
       JOIN USUARIOS u ON u.ID_Usuario = c.ID_Usuario
       WHERE c.Correo_Electronico = ? AND c.ID_Estado = 1 AND u.ID_Estado = 1
       LIMIT 1"
    );
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $r = $stmt->get_result()->fetch_assoc();
    return $r ?: null;
  }

  public function emailExists(string $email): bool {
    $stmt = $this->db->prepare("SELECT 1 FROM CORREO WHERE Correo_Electronico=? LIMIT 1");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    return (bool)$stmt->get_result()->fetch_row();
  }

  private function nextUserId(): int {
    $r = $this->db->query("SELECT COALESCE(MAX(ID_Usuario),0)+1 AS nxt FROM USUARIOS");
    return (int)$r->fetch_assoc()['nxt'];
  }

  private function nextCorreoId(): int {
    $r = $this->db->query("SELECT COALESCE(MAX(ID_Correo),0)+1 AS nxt FROM CORREO");
    return (int)$r->fetch_assoc()['nxt'];
  }

  /**
   * Crea usuario estándar (rol Usuario) + correo asociado + historial inicial (0).
   * Retorna el arreglo del usuario para sesión.
   */
  public function createUser(string $nombre, string $apellido, string $email): array {
    if ($this->emailExists($email)) {
      throw new Exception('El correo ya está registrado.');
    }

    $this->db->begin_transaction();
    try {
      $uid = $this->nextUserId();
      $cid = $this->nextCorreoId();

      // USUARIO (rol Usuario=2, Activo=1)
      $stmtU = $this->db->prepare(
        "INSERT INTO USUARIOS (ID_Usuario, Nombre, Apellido, Fecha_Registro, ID_Tipo_Usuario, ID_Estado)
         VALUES (?, ?, ?, CURDATE(), 2, 1)"
      );
      $stmtU->bind_param('iss', $uid, $nombre, $apellido);
      $stmtU->execute();

      // CORREO (ID_Empresa NULL/omitir; ID_Estado=1)
      $stmtC = $this->db->prepare(
        "INSERT INTO CORREO (ID_Correo, Correo_Electronico, ID_Usuario, ID_Empresa, ID_Estado)
         VALUES (?, ?, ?, NULL, 1)"
      );
      $stmtC->bind_param('isi', $cid, $email, $uid);
      $stmtC->execute();

      // HISTORIAL inicial 0
      $stmtH = $this->db->prepare(
        "INSERT INTO HISTORIAL_PUNTOS (Fecha, Descripcion, Puntos_Modificados, Puntos_Totales, ID_Usuario, ID_Estado)
         VALUES (CURDATE(), 'Registro inicial', 0, 0, ?, 1)"
      );
      $stmtH->bind_param('i', $uid);
      $stmtH->execute();

      $this->db->commit();

      return [
        'ID_Usuario'      => $uid,
        'Nombre'          => $nombre,
        'Apellido'        => $apellido,
        'ID_Tipo_Usuario' => 2,
        'ID_Estado'       => 1,
        'Fecha_Registro'  => date('Y-m-d'),
        'Correo_Electronico' => $email,
      ];
    } catch (Throwable $e) {
      $this->db->rollback();
      throw $e;
    }
  }
}
