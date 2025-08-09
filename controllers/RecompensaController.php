<?php
require_once "models/RecompensaModel.php";

class RecompensaController {
    private $modelo;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) { session_start(); }
        $this->modelo = new RecompensaModel();
    }

    // Listado de recompensas activas
    public function index() {
        $recompensas = $this->modelo->listar();
        require "views/recompensa/index.php";
    }

    // Formulario de canje (detalle de una recompensa + puntos del usuario)
    public function canjear() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $recompensa = $this->modelo->obtenerPorId($id);
        if (!$recompensa) {
            $_SESSION['flash_error'] = "Recompensa no encontrada.";
            header("Location: index.php?controller=Recompensa&action=index");
            exit;
        }

        // Ajusta este índice según cómo guardes el usuario en sesión
        $idUsuario = $_SESSION['user']['ID_Usuario'] ?? 1;
        $puntosUsuario = $this->modelo->puntosUsuario($idUsuario);

        require "views/recompensa/canjear.php";
    }

    // Procesa el canje (POST)
    public function storeCanje() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?controller=Recompensa&action=index"); exit;
        }

        $idRecompensa = (int)($_POST['id_recompensa'] ?? 0);
        $cantidad     = (int)($_POST['cantidad'] ?? 0);
        $idUsuario    = $_SESSION['user']['ID_Usuario'] ?? 1;

        $res = $this->modelo->canjear($idRecompensa, $idUsuario, $cantidad);

        if ($res['ok']) {
            $_SESSION['flash_ok'] = $res['msg'];
        } else {
            $_SESSION['flash_error'] = $res['msg'];
        }

        header("Location: index.php?controller=Recompensa&action=index");
        exit;
    }

    // Historial de canjes del usuario actual
    public function misCanjes() {
        $idUsuario = $_SESSION['user']['ID_Usuario'] ?? 1;
        $canjes = $this->modelo->canjesPorUsuario($idUsuario);
        require "views/recompensa/mis_canjes.php";
    }

    // Detalle de un canje específico
    public function detalle() {
        $idCanje = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $idUsuario = $_SESSION['user']['ID_Usuario'] ?? 1;
        $detalle = $this->modelo->detalleCanje($idCanje, $idUsuario);
        if (!$detalle) {
            $_SESSION['flash_error'] = "Canje no encontrado.";
            header("Location: index.php?controller=Recompensa&action=misCanjes");
            exit;
        }
        require "views/recompensa/detalle.php";
    }

}