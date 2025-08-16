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
}

