<?php
class Recompensa {
    private $recompensas = [];

    public function __construct() {
        $this->recompensas = [
            [
                "ID_Recompensa" => 1,
                "Nombre" => "Descuento en tienda",
                "Descripcion" => "Canjea por un 10% de descuento en productos ecológicos.",
                "Puntos_Necesarios" => 50
            ],
            [
                "ID_Recompensa" => 2,
                "Nombre" => "Bolsa reutilizable",
                "Descripcion" => "Bolsa con diseño reciclado.",
                "Puntos_Necesarios" => 30
            ]
        ];
    }

    public function obtenerTodas() {
        return $this->recompensas;
    }

    public function canjear($idUsuario, $idRecompensa) {
        return "Usuario $idUsuario ha canjeado la recompensa #$idRecompensa.";
    }
}