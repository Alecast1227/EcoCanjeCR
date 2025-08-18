<?php
require_once 'models/RecompensaModel.php';
require_once 'helpers/auth.php';

class RecompensaController {
  private $model;
  public function __construct(){ $this->model = new RecompensaModel(); }

  public function index(){
    requireLogin();
    $recompensas = $this->model->listarDisponibles();
    $saldo = $this->model->saldoUsuario($_SESSION['user']['ID_Usuario']);
    require 'views/recompensa/index.php';
  }

  public function canjear(){
    requireLogin();
    $id = (int)($_POST['id_recompensa'] ?? 0);
    $qty = (int)($_POST['cantidad'] ?? 1);
    if ($id<=0 || $qty<=0) die('Datos inválidos');
    try {
      $this->model->canjear($_SESSION['user']['ID_Usuario'], $id, $qty);
      header('Location: index.php?controller=Recompensa&action=index&ok=1');
    } catch (Throwable $e){
      die('No se pudo canjear: '.$e->getMessage());
    }
  }

  // Mis canjes (historial del usuario)
  public function misCanjes(){
    requireLogin();
    $canjes = $this->model->listarCanjesPorUsuario((int)$_SESSION['user']['ID_Usuario']);
    require 'views/recompensa/mis_canjes.php';
  }
    // === ADMIN ===
  public function adminIndex(){
    requireRoleAdmin();
    $recompensas = $this->model->listarTodas();
    require 'views/recompensa/admin_index.php';
  }

public function create(){
  requireRoleAdmin();
  $recompensa = ['Nombre'=>'','Descripcion'=>'','Puntos_Requeridos'=>0,'Cantidad_Disponible'=>0,'ID_Estado'=>1];
  $estados = $this->model->listarEstados();
  $isEdit = false;
  require 'views/recompensa/form.php';
}

  public function store(){
    requireRoleAdmin();
    $nombre = trim($_POST['Nombre'] ?? '');
    $descripcion = trim($_POST['Descripcion'] ?? '');
    $puntos = (int)($_POST['Puntos_Requeridos'] ?? 0);
    $cantidad = (int)($_POST['Cantidad_Disponible'] ?? 0);
    $estado = (int)($_POST['ID_Estado'] ?? 1);
    if($nombre===''){ die('Nombre es requerido'); }
    $this->model->crear($nombre,$descripcion,$puntos,$cantidad,$estado);
    header('Location: index.php?controller=Recompensa&action=adminIndex&ok=1');
  }

  public function edit(){
  requireRoleAdmin();
  $id = (int)($_GET['id'] ?? 0);
  $recompensa = $this->model->obtener($id);
  if(!$recompensa){ die('Recompensa no encontrada'); }
  $estados = $this->model->listarEstados();
  $isEdit = true;
  require 'views/recompensa/form.php';
}

  public function update(){
    requireRoleAdmin();
    $id = (int)($_POST['ID_Recompensa'] ?? 0);
    $nombre = trim($_POST['Nombre'] ?? '');
    $descripcion = trim($_POST['Descripcion'] ?? '');
    $puntos = (int)($_POST['Puntos_Requeridos'] ?? 0);
    $cantidad = (int)($_POST['Cantidad_Disponible'] ?? 0);
    $estado = (int)($_POST['ID_Estado'] ?? 1);
    if(!$id){ die('ID inválido'); }
    $this->model->actualizar($id,$nombre,$descripcion,$puntos,$cantidad,$estado);
    header('Location: index.php?controller=Recompensa&action=adminIndex&ok=1');
  }

  public function delete(){
    requireRoleAdmin();
    $id = (int)($_POST['id'] ?? 0);
    if(!$id){ die('ID inválido'); }
    $this->model->desactivar($id);
    header('Location: index.php?controller=Recompensa&action=adminIndex&ok=1');
  }

}

