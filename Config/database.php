<?php
class Database {
    public static function conectar() {
        $servidor = "localhost"; 
        $usuario = "root";      
        $clave = "22652365Che!";  
        $base = "RECICLA_CR";
        $puerto = 3306;          

        $conexion = new mysqli($servidor, $usuario, $clave, $base, $puerto);
        
        if ($conexion->connect_error) {
            die("Error de conexión: " . $conexion->connect_error);
        }
        return $conexion;
    }
}

