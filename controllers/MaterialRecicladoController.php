<?php
require_once 'models/materialRecicladoModel.php';
require_once 'helpers/auth.php';

class MaterialRecicladoController {
  private $model;

  public function __construct(){
    $this->model = new MaterialRecicladoModel();
  }

  public function index(){
    requireLogin();
    $user = $_SESSION['user'];

    if ((int)$user['ID_Tipo_Usuario'] === 1) {
      $materiales = $this->model->listar(); // Admin ve todo
    } else {
      $materiales = $this->model->listarPorUsuario((int)$user['ID_Usuario']); // User ve solo lo suyo
    }
    require 'views/material/index.php';
  }

  public function crear(){
    requireLogin(); // cualquier logueado puede crear
    require 'views/material/create.php';
  }

  public function guardar(){
    requireLogin();
    $user = $_SESSION['user'];

    $tipo   = trim($_POST['tipo'] ?? '');
    $tam    = trim($_POST['tamano'] ?? '');
    $fecha  = trim($_POST['fecha'] ?? date('Y-m-d'));
    $centro = (int)($_POST['centro'] ?? 0);
    $estado = (int)($_POST['estado'] ?? 1);

    $tiposValidos   = ['Plástico','Cartón','Vidrio'];
    $tamanosValidos = ['Pequeño','Mediano','Grande'];
    if (!in_array($tipo, $tiposValidos, true) || !in_array($tam, $tamanosValidos, true)) { die('Tipo o tamaño inválido'); }
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha) || $centro <= 0) { die('Datos inválidos'); }

    // Pesos (kg) y puntos por tamaño
    $PESOS = [
      'Plástico' => ['Pequeño'=>0.03,'Mediano'=>0.05,'Grande'=>0.08],
      'Cartón'   => ['Pequeño'=>0.10,'Mediano'=>0.25,'Grande'=>0.50],
      'Vidrio'   => ['Pequeño'=>0.25,'Mediano'=>0.50,'Grande'=>1.00],
    ];
    $PUNTOS = [
      'Plástico' => ['Pequeño'=>10,'Mediano'=>20,'Grande'=>30],
      'Cartón'   => ['Pequeño'=>5 ,'Mediano'=>10,'Grande'=>15],
      'Vidrio'   => ['Pequeño'=>8 ,'Mediano'=>16,'Grande'=>24],
    ];

    $peso   = $PESOS[$tipo][$tam];
    $puntos = $PUNTOS[$tipo][$tam];

    $this->model->crear([
      'Tipo_Material'    => $tipo,
      'Tamano'           => $tam,
      'Peso'             => $peso,
      'Fecha'            => $fecha,
      'Puntos'           => $puntos,
      'ID_Usuario'       => (int)$user['ID_Usuario'],
      'ID_Centro_Acopio' => $centro,
      'ID_Estado'        => ($estado===2?2:1),
    ]);

    header('Location: index.php?controller=MaterialReciclado&action=index');
  }

  // ======== SOLO ADMIN desde aquí ========

  public function editar(){
    requireLogin();
    requireRoleAdmin(); // bloqueo por rol

    $id = (int)($_GET['id'] ?? 0);
    $material = $this->model->obtener($id);
    if (!$material) die('No existe el material');

    // Deducir tamaño por peso para el select
    $PESOS = [
      'Plástico' => ['Pequeño'=>0.03,'Mediano'=>0.05,'Grande'=>0.08],
      'Cartón'   => ['Pequeño'=>0.10,'Mediano'=>0.25,'Grande'=>0.50],
      'Vidrio'   => ['Pequeño'=>0.25,'Mediano'=>0.50,'Grande'=>1.00],
    ];
    $tam = 'Mediano';
    if (isset($PESOS[$material['Tipo_Material']])) {
      foreach ($PESOS[$material['Tipo_Material']] as $t=>$w) {
        if (abs($w - (float)$material['Peso']) < 0.0001) { $tam = $t; break; }
      }
    }
    $material['Tamano'] = $tam;

    require 'views/material/edit.php';
  }

  public function actualizar(){
    requireLogin();
    requireRoleAdmin(); // bloqueo por rol

    $id     = (int)($_POST['id'] ?? 0);
    $exist  = $this->model->obtener($id);
    if (!$exist) die('No existe');

    $tipo   = trim($_POST['tipo'] ?? $exist['Tipo_Material']);
    $tam    = trim($_POST['tamano'] ?? 'Mediano');
    $fecha  = trim($_POST['fecha'] ?? $exist['Fecha_Registro']);
    $centro = (int)($_POST['centro'] ?? $exist['ID_Centro_Acopio']);
    $estado = (int)($_POST['estado'] ?? $exist['ID_Estado']);

    $tiposValidos   = ['Plástico','Cartón','Vidrio'];
    $tamanosValidos = ['Pequeño','Mediano','Grande'];
    if (!in_array($tipo, $tiposValidos, true) || !in_array($tam, $tamanosValidos, true)) { die('Tipo/tamaño inválido'); }
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha) || $centro <= 0) { die('Datos inválidos'); }

    $PESOS = [
      'Plástico' => ['Pequeño'=>0.03,'Mediano'=>0.05,'Grande'=>0.08],
      'Cartón'   => ['Pequeño'=>0.10,'Mediano'=>0.25,'Grande'=>0.50],
      'Vidrio'   => ['Pequeño'=>0.25,'Mediano'=>0.50,'Grande'=>1.00],
    ];
    $PUNTOS = [
      'Plástico' => ['Pequeño'=>10,'Mediano'=>20,'Grande'=>30],
      'Cartón'   => ['Pequeño'=>5 ,'Mediano'=>10,'Grande'=>15],
      'Vidrio'   => ['Pequeño'=>8 ,'Mediano'=>16,'Grande'=>24],
    ];

    $peso   = $PESOS[$tipo][$tam];
    $puntos = $PUNTOS[$tipo][$tam];

    $this->model->actualizar([
      'ID'               => $id,
      'Tipo_Material'    => $tipo,
      'Tamano'           => $tam,
      'Peso'             => $peso,
      'Fecha'            => $fecha,
      'Puntos'           => $puntos,
      'ID_Centro_Acopio' => $centro,
      'ID_Estado'        => ($estado===2?2:1),
    ]);

    header('Location: index.php?controller=MaterialReciclado&action=index');
  }

  public function eliminar(){
    requireLogin();
    requireRoleAdmin(); // bloqueo por rol

    $id = (int)($_GET['id'] ?? 0);
    $this->model->eliminar($id);
    header('Location: index.php?controller=MaterialReciclado&action=index');
  }
}
