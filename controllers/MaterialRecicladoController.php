<?php
require_once "models/materialRecicladoModel.php";

class MaterialRecicladoController {
    private $modelo;

    public function __construct($db = null) {
        $this->modelo = new MaterialReciclado($db);
    }

    public function index() {
        $materiales = $this->modelo->obtenerTodos();
        include "views/material/index.php";
    }

    public function crear() {
        include "views/material/create.php";
    }

    public function guardar() {
        $this->modelo->guardar($_POST);
        header("Location: index.php?controller=MaterialReciclado&action=index");
    }

    public function editar() {
        $material = $this->modelo->obtenerPorId($_GET['id']);
        include "views/material/edit.php";
    }

    public function actualizar() {
        $this->modelo->actualizar($_POST['id'], $_POST);
        header("Location: index.php?controller=MaterialReciclado&action=index");
    }

    public function eliminar() {
        $this->modelo->eliminar($_GET['id']);
        header("Location: index.php?controller=MaterialReciclado&action=index");
    }
}

/*
<?php
require_once "models/materialRecicladoModel.php";

class MaterialRecicladoController {
    private $modelo;

    public function __construct($db) {
        $this->modelo = new MaterialReciclado($db);
    }

    public function index() {
        $materiales = $this->modelo->obtenerTodos();
        include "views/material/index.php";
    }

    public function crear() {
        include "views/material/create.php";
    }

    public function guardar() {
        $datos = $_POST;
        $this->modelo->guardar($datos);
        header("Location: index.php?controller=MaterialReciclado&action=index");
    }

    public function editar() {
        $id = $_GET['id'];
        $material = $this->modelo->obtenerPorId($id);
        include "views/material/edit.php";
    }

    public function actualizar() {
        $id = $_POST['id'];
        $datos = $_POST;
        $this->modelo->actualizar($id, $datos);
        header("Location: index.php?controller=MaterialReciclado&action=index");
    }

    public function eliminar() {
        $id = $_GET['id'];
        $this->modelo->eliminar($id);
        header("Location: index.php?controller=MaterialReciclado&action=index");
    }
}
*/