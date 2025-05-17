<?php
require_once __DIR__ . '/../../config/database.php';

class Asientos {
    public static function obtenerPorFuncion($id_funcion) {
        global $conn;
        $sql = "SELECT * FROM asientos WHERE id_funcion = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_funcion);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public static function obtenerPorId($id) {
        global $conn;
        $sql = "SELECT * FROM asientos WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public static function crearAsientos($id_funcion, $asientos) {
        global $conn;
        $sql = "INSERT INTO asientos (id_funcion, numero_asiento, disponible) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        foreach ($asientos as $asiento) {
            $numero_asiento = $asiento['numero_asiento'];
            $disponible = $asiento['disponible'];
            $stmt->bind_param("isi", $id_funcion, $numero_asiento, $disponible);
            $stmt->execute();
        }
        return true;
    }

    public static function actualizarEstado($id, $disponible) {
        global $conn;
        $sql = "UPDATE asientos SET disponible = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $disponible, $id);
        return $stmt->execute();
    }

    public static function eliminarAsiento($id) {
        global $conn;
        $sql = "DELETE FROM asientos WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>