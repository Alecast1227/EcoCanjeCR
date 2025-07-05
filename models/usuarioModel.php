<?php
class Usuario {
    private $usuarios = [];

    public function __construct($db = null) {
        // Simulación de datos
        $this->usuarios = [
            [
                "IdUsuario" => 1,
                "Nombre" => "Carlos",
                "Apellido" => "Rodríguez",
                "Correo" => "carlos.rodriguez@example.com",
                "Telefono" => "8888-1234",
                "Direccion" => "Alajuela, Costa Rica",
                "Contrasena" => "password123",
                "FechaCreacion" => "2023-03-10"
            ],
            [
                "IdUsuario" => 2,
                "Nombre" => "Ana",
                "Apellido" => "Jiménez",
                "Correo" => "ana.jimenez@example.com",
                "Telefono" => "7777-5678",
                "Direccion" => "Cartago, Costa Rica",
                "Contrasena" => "securepass456",
                "FechaCreacion" => "2023-04-15"
            ],
            [
                "IdUsuario" => 3,
                "Nombre" => "Luis",
                "Apellido" => "Mora",
                "Correo" => "luis.mora@example.com",
                "Telefono" => "6666-9012",
                "Direccion" => "Puntarenas, Costa Rica",
                "Contrasena" => "securepass789",
                "FechaCreacion" => "2023-05-20"
            ]
        ];
    }

    public function obtenerTodos() {
        return $this->usuarios;
    }

    public function guardar($datos) {
        $nuevoId = count($this->usuarios) + 1;
        $nuevoUsuario = [
            "IdUsuario" => $nuevoId,
            "Nombre" => $datos['nombre'],
            "Apellido" => $datos['apellido'],
            "Correo" => $datos['correo'],
            "Telefono" => $datos['telefono'],
            "Direccion" => $datos['direccion'],
            "Contrasena" => $datos['contrasena'],
            "FechaCreacion" => date('Y-m-d') // Fecha actual
        ];
        $this->usuarios[] = $nuevoUsuario;
        return true;
    }

    public function obtenerPorId($id) {
        foreach ($this->usuarios as $usuario) {
            if ($usuario['IdUsuario'] == $id) {
                return $usuario;
            }
        }
        return null;
    }

    public function actualizar($id, $datos) {
        foreach ($this->usuarios as $key => $usuario) {
            if ($usuario['IdUsuario'] == $id) {
                $this->usuarios[$key]['Nombre'] = $datos['nombre'];
                $this->usuarios[$key]['Apellido'] = $datos['apellido'];
                $this->usuarios[$key]['Correo'] = $datos['correo'];
                $this->usuarios[$key]['Telefono'] = $datos['telefono'];
                $this->usuarios[$key]['Direccion'] = $datos['direccion'];
                $this->usuarios[$key]['Contrasena'] = $datos['contrasena'];
                return true;
            }
        }
        return false;
    }

    public function eliminar($id) {
        // En una implementación real, aquí se eliminaría de la base de datos
        // Por ahora, simulamos la operación
        foreach ($this->usuarios as $key => $usuario) {
            if ($usuario['IdUsuario'] == $id) {
                unset($this->usuarios[$key]);
                return true;
            }
        }
        return false;
    }
}

/* 
// Versión con conexión a base de datos (para implementación futura)
class Usuario {
    private $conn;
    private $table = "USUARIOS";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function obtenerTodos() {
        $query = "SELECT * FROM $this->table";
        return $this->conn->query($query);
    }

    public function guardar($datos) {
        $stmt = $this->conn->prepare("INSERT INTO $this->table 
            (Nombre, Apellido, Correo, Telefono, Direccion, Contrasena, FechaCreacion)
            VALUES (?, ?, ?, ?, ?, ?, ?)");
        $fechaActual = date('Y-m-d');
        $stmt->bind_param("sssssss", $datos['nombre'], $datos['apellido'], $datos['correo'], 
                         $datos['telefono'], $datos['direccion'], $datos['contrasena'], $fechaActual);
        return $stmt->execute();
    }

    public function obtenerPorId($id) {
        $stmt = $this->conn->prepare("SELECT * FROM $this->table WHERE IdUsuario = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function actualizar($id, $datos) {
        $stmt = $this->conn->prepare("UPDATE $this->table SET 
            Nombre=?, Apellido=?, Correo=?, Telefono=?, Direccion=?, Contrasena=? 
            WHERE IdUsuario=?");
        $stmt->bind_param("ssssssi", $datos['nombre'], $datos['apellido'], $datos['correo'], 
                         $datos['telefono'], $datos['direccion'], $datos['contrasena'], $id);
        return $stmt->execute();
    }

    public function eliminar($id) {
        $stmt = $this->conn->prepare("DELETE FROM $this->table WHERE IdUsuario = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
*/
