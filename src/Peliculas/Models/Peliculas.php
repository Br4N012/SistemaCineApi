<?php
require_once __DIR__ . '/../../config/database.php';

class Peliculas {
    public static function obtenerTodos() {
        global $conn;
        $sql = "SELECT * FROM peliculas";
        $result = $conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public static function obtenerPorId($id) {
        global $conn;
        $sql = "SELECT * FROM peliculas WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public static function ordenarPorClasificacion() {
        global $conn;
        $sql = "SELECT * FROM peliculas ORDER BY clasificacion";
        $result = $conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public static function crearPelicula($titulo, $sinopsis, $duracion, $clasificacion, $genero) {
        global $conn;
        $sql = "INSERT INTO peliculas (titulo, sinopsis, duracion, clasificacion, genero) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssiss", $titulo, $sinopsis, $duracion, $clasificacion, $genero);
        return $stmt->execute();
    }

    public static function actualizarPelicula($id, $titulo, $sinopsis, $duracion, $clasificacion, $genero) {
        global $conn;
        $sql = "UPDATE peliculas SET titulo = ?, sinopsis = ?, duracion = ?, clasificacion = ?, genero = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssissi", $titulo, $sinopsis, $duracion, $clasificacion, $genero, $id);
        return $stmt->execute();
    }

    public static function eliminarPelicula($id) {
        global $conn;
        $sql = "DELETE FROM peliculas WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
    public static function obtenerPeliculaPorNombre($titulo){
        global $conn;
        $sql = "SELECT * FROM peliculas WHERE titulo = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $titulo);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
}
?>
