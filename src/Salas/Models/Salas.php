<?php
require_once __DIR__ . '/../../config/database.php';

class Salas {
    public static function obtenerTodas() {
        global $conn;
        $sql = "SELECT * FROM salas";
        $result = $conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public static function obtenerPorId($id) {
        global $conn;
        $sql = "SELECT * FROM salas WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public static function crearSala($nombre, $capacidad) {
        global $conn;
        $sql = "INSERT INTO salas (nombre, capacidad) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $nombre, $capacidad);
        return $stmt->execute();
    }

    public static function actualizarSala($id, $nombre, $capacidad) {
        global $conn;
        $sql = "UPDATE salas SET nombre = ?, capacidad = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sii", $nombre, $capacidad, $id);
        return $stmt->execute();
    }

    public static function eliminarSala($id) {
        global $conn;
        $sql = "DELETE FROM salas WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>