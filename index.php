<?php
$controller = $_GET['controller'] ?? 'MaterialReciclado';
$action = $_GET['action'] ?? 'index';

require_once "controllers/{$controller}Controller.php";
$controllerName = $controller . "Controller";
$objController = new $controllerName();
$objController->$action();
