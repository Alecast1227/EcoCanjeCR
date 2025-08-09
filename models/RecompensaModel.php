<?php
// models/RecompensaModel.php
class RecompensaModel {
    private $db;
    private $ID_ESTADO_ACTIVO = 1; // ajusta si tu "activo" es otro valor

    public function __construct($db = null) {
        // Tu database.php retorna $conexion (mysqli)
        $this->db = $db ?: require __DIR__ . '/../Config/database.php';
        if (!($this->db instanceof mysqli)) {
            throw new Exception("No se obtuvo una conexión mysqli válida desde Config/database.php");
        }
        // $this->db->set_charset('utf8mb4'); // si lo necesitas
    }

    /** =========================
     *  Recompensas (catálogo)
     *  ========================= */
    public function listar() {
        $sql = "SELECT ID_Recompensa, Nombre, Descripcion, Cantidad_Disponible, Puntos_Requeridos
                FROM RECOMPENSA
                WHERE ID_Estado = ?
                ORDER BY Nombre";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $this->ID_ESTADO_ACTIVO);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function obtenerPorId($id) {
        $sql = "SELECT ID_Recompensa, Nombre, Descripcion, Cantidad_Disponible, Puntos_Requeridos, ID_Estado
                FROM RECOMPENSA
                WHERE ID_Recompensa = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    /** =========================
     *  Puntos del usuario
     *  ========================= */
    // Suma los movimientos activos del historial (si usas Puntos_Totales, podrías tomar el último por fecha).
    public function puntosUsuario($idUsuario) {
        $sql = "SELECT COALESCE(SUM(Puntos_Modificados),0) AS puntos
                FROM HISTORIAL_PUNTOS
                WHERE ID_Usuario = ? AND ID_Estado = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ii", $idUsuario, $this->ID_ESTADO_ACTIVO);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        return (int)$row['puntos'];
    }

    /** =========================
     *  Canje (transacción)
     *  ========================= */
    public function canjear($idRecompensa, $idUsuario, $cantidad) {
        if ($cantidad < 1) {
            return ["ok" => false, "msg" => "La cantidad debe ser mayor a 0."];
        }

        $this->db->begin_transaction();
        try {
            // 1) Bloquear recompensa
            $sql = "SELECT ID_Recompensa, Nombre, Cantidad_Disponible, Puntos_Requeridos, ID_Estado
                    FROM RECOMPENSA
                    WHERE ID_Recompensa = ? FOR UPDATE";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("i", $idRecompensa);
            $stmt->execute();
            $recompensa = $stmt->get_result()->fetch_assoc();

            if (!$recompensa || (int)$recompensa['ID_Estado'] !== $this->ID_ESTADO_ACTIVO) {
                $this->db->rollback();
                return ["ok" => false, "msg" => "Recompensa no disponible."];
            }
            if ((int)$recompensa['Cantidad_Disponible'] < $cantidad) {
                $this->db->rollback();
                return ["ok" => false, "msg" => "Stock insuficiente."];
            }

            // 2) Bloquear usuario (para serializar operaciones del mismo usuario)
            $stmt = $this->db->prepare("SELECT ID_Usuario FROM USUARIOS WHERE ID_Usuario = ? FOR UPDATE");
            $stmt->bind_param("i", $idUsuario);
            $stmt->execute();
            $usuario = $stmt->get_result()->fetch_assoc();
            if (!$usuario) {
                $this->db->rollback();
                return ["ok" => false, "msg" => "Usuario no encontrado."];
            }

            $puntosActuales = $this->puntosUsuario($idUsuario);
            $costo = (int)$recompensa['Puntos_Requeridos'] * $cantidad;

            if ($puntosActuales < $costo) {
                $this->db->rollback();
                return ["ok" => false, "msg" => "Puntos insuficientes."];
            }

            // 3) Descontar stock
            $stmt = $this->db->prepare(
                "UPDATE RECOMPENSA SET Cantidad_Disponible = Cantidad_Disponible - ? WHERE ID_Recompensa = ?"
            );
            $stmt->bind_param("ii", $cantidad, $idRecompensa);
            $stmt->execute();

            // 4) Registrar canje (ID_Canje es AUTO_INCREMENT)
            $stmt = $this->db->prepare(
                "INSERT INTO CANJE_RECOMPENSA (Fecha_Canje, Cantidad, ID_Recompensa, ID_Usuario, ID_Estado)
                 VALUES (NOW(), ?, ?, ?, ?)"
            );
            $estado = $this->ID_ESTADO_ACTIVO;
            $stmt->bind_param("iiii", $cantidad, $idRecompensa, $idUsuario, $estado);
            $stmt->execute();

            // 5) Registrar movimiento de puntos (ID_Historial_Puntos es AUTO_INCREMENT)
            $descripcion = "Canje de recompensa: " . $recompensa['Nombre'] . " (x$cantidad)";
            $puntosMod = -$costo;
            $puntosTotalesDespues = $puntosActuales - $costo;

            $stmt = $this->db->prepare(
                "INSERT INTO HISTORIAL_PUNTOS (Fecha, Descripcion, Puntos_Modificados, Puntos_Totales, ID_Usuario, ID_Estado)
                 VALUES (NOW(), ?, ?, ?, ?, ?)"
            );
            $stmt->bind_param("siiii", $descripcion, $puntosMod, $puntosTotalesDespues, $idUsuario, $estado);
            $stmt->execute();

            $this->db->commit();
            return ["ok" => true, "msg" => "Canje realizado con éxito."];
        } catch (\Throwable $e) {
            $this->db->rollback();
            return ["ok" => false, "msg" => "Error en canje: " . $e->getMessage()];
        }
    }

    /** =========================
     *  Historial (mis canjes)
     *  ========================= */
    public function canjesPorUsuario($idUsuario) {
        $sql = "SELECT c.ID_Canje, c.Fecha_Canje, c.Cantidad, c.ID_Recompensa,
                       r.Nombre AS Recompensa, r.Puntos_Requeridos,
                       (r.Puntos_Requeridos * c.Cantidad) AS Puntos_Usados
                FROM CANJE_RECOMPENSA c
                JOIN RECOMPENSA r ON r.ID_Recompensa = c.ID_Recompensa
                WHERE c.ID_Usuario = ?
                ORDER BY c.Fecha_Canje DESC, c.ID_Canje DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function detalleCanje($idCanje, $idUsuario = null) {
        $sql = "SELECT c.ID_Canje, c.Fecha_Canje, c.Cantidad,
                       c.ID_Recompensa, c.ID_Usuario,
                       r.Nombre AS Recompensa, r.Descripcion, r.Puntos_Requeridos,
                       (r.Puntos_Requeridos * c.Cantidad) AS Puntos_Usados
                FROM CANJE_RECOMPENSA c
                JOIN RECOMPENSA r ON r.ID_Recompensa = c.ID_Recompensa
                WHERE c.ID_Canje = ?";
        if ($idUsuario !== null) {
            $sql .= " AND c.ID_Usuario = ?";
        }

        if ($idUsuario !== null) {
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("ii", $idCanje, $idUsuario);
        } else {
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("i", $idCanje);
        }
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
}
