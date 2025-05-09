<?php
require_once __DIR__ . '/../../config/database.php';

class Funciones {
    public static function obtenerTodas() {
        global $conn;
        $sql = "SELECT funciones.id, peliculas.titulo AS pelicula, salas.nombre AS sala, funciones.horario 
                FROM funciones
                INNER JOIN peliculas ON funciones.id_pelicula = peliculas.id
                INNER JOIN salas ON funciones.id_sala = salas.id";
        $result = $conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public static function obtenerPorId($id) {
        global $conn;
        $sql = "SELECT funciones.id, peliculas.titulo AS pelicula, salas.nombre AS sala, funciones.horario 
                FROM funciones
                INNER JOIN peliculas ON funciones.id_pelicula = peliculas.id
                INNER JOIN salas ON funciones.id_sala = salas.id
                WHERE funciones.id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public static function crearFuncion($id_pelicula, $id_sala, $horario) {
        global $conn;
        $sql = "INSERT INTO funciones (id_pelicula, id_sala, horario) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iis", $id_pelicula, $id_sala, $horario);
        return $stmt->execute();
    }

    public static function actualizarFuncion($id, $id_pelicula, $id_sala, $horario) {
        global $conn;
        $sql = "UPDATE funciones SET id_pelicula = ?, id_sala = ?, horario = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iisi", $id_pelicula, $id_sala, $horario, $id);
        return $stmt->execute();
    }

    public static function eliminarFuncion($id) {
        global $conn;
        $sql = "DELETE FROM funciones WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>