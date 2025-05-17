<?php
require_once __DIR__ . '/../../config/database.php';

class Usuarios {
    public static function obtenerTodos() {
        global $conn;
        $sql = "SELECT * FROM usuarios";
        $result = $conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public static function obtenerPorId($id) {
        global $conn;
        $sql = "SELECT * FROM usuarios WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public static function crearUsuario($nombre, $correo, $contraseña) {
    global $conn;
    $sql = "INSERT INTO usuarios (nombre, correo, contraseña) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $hash = password_hash($contraseña, PASSWORD_BCRYPT); // <-- variable intermedia
    $stmt->bind_param("sss", $nombre, $correo, $hash);
    return $stmt->execute();
}
    public static function actualizarUsuario($id, $nombre, $correo, $contraseña) {
        global $conn;
        $sql = "UPDATE usuarios SET nombre = ?, correo = ?, contraseña = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $nombre, $correo, password_hash($contraseña, PASSWORD_BCRYPT), $id);
        return $stmt->execute();
    }

    public static function eliminarUsuario($id) {
        global $conn;
        $sql = "DELETE FROM usuarios WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>