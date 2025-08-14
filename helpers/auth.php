
<?php
if (!function_exists('start_session_safe')) {
  function start_session_safe(){ if(session_status()!==PHP_SESSION_ACTIVE) session_start(); }
}
if (!function_exists('requireLogin')) {
  function requireLogin(){
    start_session_safe();
    if (empty($_SESSION['user'])) {
      header('Location: index.php?controller=Auth&action=login');
      exit;
    }
  }
}
if (!function_exists('requireRoleAdmin')) {
  function requireRoleAdmin(){
    start_session_safe();
    if (empty($_SESSION['user']) || (int)$_SESSION['user']['ID_Tipo_Usuario'] !== 1) {
      http_response_code(403);
      die('Acceso solo para administradores');
    }
  }
}
?>