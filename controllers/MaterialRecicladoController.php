<?php

require_once 'Config/database.php'; 
require_once 'models/materialRecicladoModel.php';

class MaterialRecicladoController {

    private $model;

    public function __construct() {
        $this->model = new MaterialRecicladoModel();
    }

    public function index() {
        $materiales = $this->model->obtenerTodos();
        require_once 'views/material/index.php';
    }

    public function crear() {
        require_once 'views/material/create.php';
    }

    public function guardar() {
        $data = [
            'Tipo_Material' => $_POST['tipo'],
            'Peso' => $_POST['peso'],
            'Fecha_Reciclaje' => $_POST['fecha'],
            'Puntos_Asignados' => $_POST['puntos'],
            'ID_Usuario' => $_POST['usuario'],
            'ID_Centro_Acopio' => $_POST['centro'],
            'ID_Estado' => $_POST['estado']
        ];
        $this->model->insertar($data);
        header('Location: index.php?controller=MaterialReciclado&action=index');
    }

    public function editar() {
        $id = $_GET['id'];
        $material = $this->model->obtenerPorId($id);
        require_once 'views/material/edit.php';
    }

    public function actualizar() {
        $data = [
            'ID_Material_Reciclado' => $_POST['id'],
            'Tipo_Material' => $_POST['tipo'],
            'Peso' => $_POST['peso'],
            'Fecha_Reciclaje' => $_POST['fecha'],
            'Puntos_Asignados' => $_POST['puntos'],
            'ID_Usuario' => $_POST['usuario'],
            'ID_Centro_Acopio' => $_POST['centro'],
            'ID_Estado' => $_POST['estado']
        ];
        $this->model->actualizar($data);
        header('Location: index.php?controller=MaterialReciclado&action=index');
    }

    public function eliminar() {
        $id = $_GET['id'];
        $this->model->eliminar($id);
        header('Location: index.php?controller=MaterialReciclado&action=index');
    }
}
