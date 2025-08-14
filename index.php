<?php
require_once __DIR__ . '/helpers/auth.php';
start_session_safe();

$controller = $_GET['controller'] ?? 'Menu';
$action     = $_GET['action'] ?? 'home';

// Rutas públicas
$public = [
  ['Auth','login'],
  ['Auth','doLogin'],
  ['Auth','logout'],
    ['Auth','registro'],   
  ['Auth','doRegistro'],
];

$ctl = preg_replace('/[^A-Za-z]/','', $controller);
$act = preg_replace('/[^A-Za-z_]/','', $action);

$isPublic = false;
foreach ($public as $p) {
  if (strcasecmp($p[0], $ctl)===0 && strcasecmp($p[1], $act)===0) { $isPublic = true; break; }
}
if (!$isPublic) { requireLogin(); }

$controllerClass = $ctl . 'Controller';
$controllerFile  = __DIR__ . '/controllers/' . $controllerClass . '.php';

if (!file_exists($controllerFile)) { http_response_code(404); die('Controlador no encontrado'); }
require_once $controllerFile;

$ctrl = new $controllerClass();
if (!method_exists($ctrl, $act)) { http_response_code(404); die('Acción no encontrada'); }
$ctrl->$act();
