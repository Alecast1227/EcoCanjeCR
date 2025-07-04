
<?php
class MaterialReciclado {
    private $materiales = [];

    public function __construct($db = null) {
        // Simulación de datos
        $this->materiales = [
            [
                "ID_Material_Reciclado" => 1,
                "Tipo_Material" => "Botella PET",
                "Peso" => 0.5,
                "Fecha_Reciclaje" => "2025-07-01",
                "Puntos_Asignados" => 10,
                "ID_Usuario" => 1,
                "ID_Centro_Acopio" => 1,
                "ID_Estado" => 1
            ],
            [
                "ID_Material_Reciclado" => 2,
                "Tipo_Material" => "Aluminio",
                "Peso" => 0.2,
                "Fecha_Reciclaje" => "2025-07-02",
                "Puntos_Asignados" => 5,
                "ID_Usuario" => 2,
                "ID_Centro_Acopio" => 2,
                "ID_Estado" => 1
            ]
        ];
    }

    public function obtenerTodos() {
        return $this->materiales;
    }

    public function guardar($datos) {
        
        return true;
    }

    public function obtenerPorId($id) {
        foreach ($this->materiales as $material) {
            if ($material['ID_Material_Reciclado'] == $id) {
                return $material;
            }
        }
        return null;
    }

    public function actualizar($id, $datos) {
       
        return true;
    }

    public function eliminar($id) {
       
        return true;
    }
}
/*
//<?php
class MaterialReciclado {
    private $conn;
    private $table = "MATERIAL_RECICLADO";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function obtenerTodos() {
        $query = "SELECT * FROM $this->table";
        return $this->conn->query($query);
    }

    public function guardar($datos) {
        $stmt = $this->conn->prepare("INSERT INTO $this->table 
            (Tipo_Material, Peso, Fecha_Reciclaje, Puntos_Asignados, ID_Usuario, ID_Centro_Acopio, ID_Estado)
            VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sdsiiii", $datos['tipo'], $datos['peso'], $datos['fecha'], $datos['puntos'], $datos['usuario'], $datos['centro'], $datos['estado']);
        return $stmt->execute();
    }

    public function obtenerPorId($id) {
        $stmt = $this->conn->prepare("SELECT * FROM $this->table WHERE ID_Material_Reciclado = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function actualizar($id, $datos) {
        $stmt = $this->conn->prepare("UPDATE $this->table SET 
            Tipo_Material=?, Peso=?, Fecha_Reciclaje=?, Puntos_Asignados=?, ID_Usuario=?, ID_Centro_Acopio=?, ID_Estado=? 
            WHERE ID_Material_Reciclado=?");
        $stmt->bind_param("sdsiiiii", $datos['tipo'], $datos['peso'], $datos['fecha'], $datos['puntos'], $datos['usuario'], $datos['centro'], $datos['estado'], $id);
        return $stmt->execute();
    }

    public function eliminar($id) {
        $stmt = $this->conn->prepare("DELETE FROM $this->table WHERE ID_Material_Reciclado = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
//*/