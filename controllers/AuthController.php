<?php
require_once 'helpers/auth.php';
require_once 'models/AuthModel.php';

class AuthController {
  private $model;
  public function __construct(){ $this->model = new AuthModel(); }

  public function login(){
    start_session_safe();
    if (!empty($_SESSION['user'])) {
      header('Location: index.php?controller=Menu&action=home'); return;
    }
    $error = isset($_GET['err']) ? 'Correo no encontrado o inactivo.' : '';
    require 'views/auth/login.php';
  }

  public function doLogin(){
    start_session_safe();
    $email = trim($_POST['email'] ?? '');
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      header('Location: index.php?controller=Auth&action=login&err=1'); return;
    }
    $u = $this->model->findUserByEmail($email);
    if (!$u) {
      header('Location: index.php?controller=Auth&action=login&err=1'); return;
    }
    $_SESSION['user'] = $u;
    header('Location: index.php?controller=Menu&action=home');
  }

  public function logout(){
    start_session_safe();
    session_destroy();
    header('Location: index.php?controller=Auth&action=login');
  }

  // ===== Registro =====
  public function registro(){
    start_session_safe();
    if (!empty($_SESSION['user'])) {
      header('Location: index.php?controller=Menu&action=home'); return;
    }
    $msg = $_GET['msg'] ?? '';
    $err = $_GET['err'] ?? '';
    require 'views/auth/register.php';
  }

  public function doRegistro(){
    start_session_safe();
    $nombre  = trim($_POST['nombre'] ?? '');
    $apellido= trim($_POST['apellido'] ?? '');
    $email   = trim($_POST['email'] ?? '');

    if ($nombre==='' || $apellido==='' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
      header('Location: index.php?controller=Auth&action=registro&err=Datos inválidos'); return;
    }

    try {
      $user = $this->model->createUser($nombre, $apellido, $email);
      $_SESSION['user'] = $user;
      header('Location: index.php?controller=Menu&action=home');
    } catch (Throwable $e) {
      $m = urlencode($e->getMessage());
      header("Location: index.php?controller=Auth&action=registro&err=$m");
    }
  }
}
