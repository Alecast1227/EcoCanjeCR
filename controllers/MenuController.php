<?php
require_once 'helpers/auth.php';
require_once 'models/RecompensaModel.php';

class MenuController {
  public function home(){
    requireLogin();
    $user = $_SESSION['user'];
    $esAdmin = (int)$user['ID_Tipo_Usuario'] === 1;

    // saldo opcional para mostrar en el menú
    $saldo = 0;
    try {
      $r = new RecompensaModel();
      $saldo = $r->saldoUsuario((int)$user['ID_Usuario']);
    } catch (Throwable $e) {}

    require 'views/menu/home.php';
  }
}
