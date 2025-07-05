<?php
require_once "models/usuarioModel.php";

class UsuarioController {
    private $modelo;

    public function __construct($db = null) {
        $this->modelo = new Usuario($db);
    }

    public function index() {
        $usuarios = $this->modelo->obtenerTodos();

        include "views/usuario/index.php";
    }

    public function crear() {
        include "views/usuario/create.php";
    }

    public function guardar() {
        $this->modelo->guardar($_POST);
        header("Location: index.php?controller=Usuario&action=index");
    }

    public function editar() {
        $usuario = $this->modelo->obtenerPorId($_GET['id']);
        include "views/usuario/edit.php";
    }

    public function actualizar() {
        $this->modelo->actualizar($_POST['id'], $_POST);
        header("Location: index.php?controller=Usuario&action=index");
    }

    public function eliminar() {
        $this->modelo->eliminar($_GET['id']);
        header("Location: index.php?controller=Usuario&action=index");
    }
}
