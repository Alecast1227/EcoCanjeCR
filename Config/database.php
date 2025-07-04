<?php
$conexion = new mysqli("localhost", "root", "", "EcoCanjeCR");
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}
return $conexion;
