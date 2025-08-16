<?php
require_once 'Config/database.php';

class UsuarioModel {
  private $db;
  public function __construct(){ $this->db = Database::conectar(); }

  public function listar(){
    $sql = 'SELECT u.ID_Usuario, u.Nombre, u.Apellido, u.ID_Tipo_Usuario, u.ID_Estado, u.Fecha_Registro
            FROM USUARIOS u
            ORDER BY u.ID_Usuario ASC';
    $res = $this->db->query($sql);
    return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
  }

  public function obtener(int $id){
    $stmt = $this->db->prepare('SELECT ID_Usuario, Nombre, Apellido, Fecha_Registro, ID_Tipo_Usuario, ID_Estado
                                FROM USUARIOS WHERE ID_Usuario=?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $r = $stmt->get_result()->fetch_assoc();
    return $r ?: null;
  }

  public function actualizar(array $d): bool {
    $stmt = $this->db->prepare('UPDATE USUARIOS
                                SET Nombre=?, Apellido=?, Fecha_Registro=?, ID_Tipo_Usuario=?, ID_Estado=?
                                WHERE ID_Usuario=?');
    $stmt->bind_param('sssiii',
      $d['Nombre'], $d['Apellido'], $d['Fecha_Registro'],
      $d['ID_Tipo_Usuario'], $d['ID_Estado'], $d['ID_Usuario']
    );
    return $stmt->execute();
  }

  // desactivar usuario (ID_Estado = 2)
  public function desactivar(int $id): bool {
    $stmt = $this->db->prepare('UPDATE USUARIOS SET ID_Estado=2 WHERE ID_Usuario=?');
    $stmt->bind_param('i', $id);
    return $stmt->execute();
  }
}
