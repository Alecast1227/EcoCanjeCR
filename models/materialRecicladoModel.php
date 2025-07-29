<?php

require_once 'Config/database.php';

class MaterialRecicladoModel {
    private $db;

    public function __construct() {
        $this->db = Database::conectar();
    }

    public function obtenerTodos() {
        $sql = "SELECT * FROM MATERIAL_RECICLADO";
        $resultado = $this->db->query($sql);
        return $resultado->fetch_all(MYSQLI_ASSOC);
    }

    public function obtenerPorId($id) {
        $stmt = $this->db->prepare("SELECT * FROM MATERIAL_RECICLADO WHERE ID_Material_Reciclado = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function insertar($data) {
        $stmt = $this->db->prepare("INSERT INTO MATERIAL_RECICLADO (Tipo_Material, Peso, Fecha_Reciclaje, Puntos_Asignados, ID_Usuario, ID_Centro_Acopio, ID_Estado) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sdsiiii", $data['Tipo_Material'], $data['Peso'], $data['Fecha_Reciclaje'], $data['Puntos_Asignados'], $data['ID_Usuario'], $data['ID_Centro_Acopio'], $data['ID_Estado']);
        $stmt->execute();
    }

    public function actualizar($data) {
        $stmt = $this->db->prepare("UPDATE MATERIAL_RECICLADO SET Tipo_Material=?, Peso=?, Fecha_Reciclaje=?, Puntos_Asignados=?, ID_Usuario=?, ID_Centro_Acopio=?, ID_Estado=? WHERE ID_Material_Reciclado=?");
        $stmt->bind_param("sdsiiiii", $data['Tipo_Material'], $data['Peso'], $data['Fecha_Reciclaje'], $data['Puntos_Asignados'], $data['ID_Usuario'], $data['ID_Centro_Acopio'], $data['ID_Estado'], $data['ID_Material_Reciclado']);
        $stmt->execute();
    }

    public function eliminar($id) {
        $stmt = $this->db->prepare("DELETE FROM MATERIAL_RECICLADO WHERE ID_Material_Reciclado = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }
}
