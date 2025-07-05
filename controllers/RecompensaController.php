<?php
require_once "models/Recompensa.php";

class RecompensaController {
    private $modelo;

    public function __construct() {
        $this->modelo = new Recompensa();
    }

    public function index() {
        $recompensas = $this->modelo->obtenerTodas();
        include "views/recompensa/index.php";
    }

    public function canjear() {
        $mensaje = $this->modelo->canjear($_POST['usuario'], $_POST['recompensa']);
        include "views/recompensa/mensaje.php";
    }
}