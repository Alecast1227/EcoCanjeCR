<?php
require_once 'models/UsuarioModel.php';
require_once 'helpers/auth.php';

class UsuarioController {
  private $model;
  public function __construct(){ $this->model = new UsuarioModel(); }

  public function index(){
    requireLogin(); requireRoleAdmin();
    $usuarios = $this->model->listar();
    require 'views/usuario/index.php';
  }

  public function editar(){
    requireLogin(); requireRoleAdmin();
    $id = (int)($_GET['id'] ?? 0);
    $u = $this->model->obtener($id);
    if (!$u) { http_response_code(404); die('Usuario no encontrado'); }
    require 'views/usuario/edit.php';
  }

  public function actualizar(){
    requireLogin(); requireRoleAdmin();
    $id = (int)($_POST['id'] ?? 0);
    if ($id <= 0) { die('ID inválido'); }

    $data = [
      'ID_Usuario'      => $id,
      'Nombre'          => trim($_POST['nombre'] ?? ''),
      'Apellido'        => trim($_POST['apellido'] ?? ''),
      'Fecha_Registro'  => trim($_POST['fecha'] ?? ''),
      'ID_Tipo_Usuario' => (int)($_POST['tipo'] ?? 2),
      'ID_Estado'       => (int)($_POST['estado'] ?? 1),
    ];

    if ($data['Nombre']==='' || $data['Apellido']==='' ||
        !preg_match('/^\d{4}-\d{2}-\d{2}$/', $data['Fecha_Registro']) ||
        !in_array($data['ID_Tipo_Usuario'], [1,2], true) ||
        !in_array($data['ID_Estado'], [1,2], true)) {
      die('Datos inválidos');
    }

    $ok = $this->model->actualizar($data);
    header('Location: index.php?controller=Usuario&action=index&upd='.($ok?1:0));
  }

  // “Eliminar” = desactivar 
  public function eliminar(){
    requireLogin(); requireRoleAdmin();
    $id = (int)($_GET['id'] ?? 0);
    if ($id <= 0) { die('ID inválido'); }
    $ok = $this->model->desactivar($id);
    header('Location: index.php?controller=Usuario&action=index&deact='.($ok?1:0));
  }
}
