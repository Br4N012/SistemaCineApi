<?php
require_once __DIR__ . '/../../config/database.php';

class Movimientos {
    public static function obtenerTodos() {
        global $conn;
        $sql = "SELECT * FROM movimientos";
        $result = $conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public static function obtenerPorId($id) {
        global $conn;
        $sql = "SELECT * FROM movimientos WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public static function obtenerPorUsuario($id_usuario) {
        global $conn;
        $sql = "SELECT * FROM movimientos WHERE id_usuario = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public static function crearMovimiento($id_usuario, $id_funcion, $total_pago, $metodo_pago, $fecha) {
        global $conn;
        $sql = "INSERT INTO movimientos (id_usuario, id_funcion, total_pago, metodo_pago, fecha) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iidss", $id_usuario, $id_funcion, $total_pago, $metodo_pago, $fecha);
        return $stmt->execute();
    }

    public static function eliminarMovimiento($id) {
        global $conn;
        $sql = "DELETE FROM movimientos WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>