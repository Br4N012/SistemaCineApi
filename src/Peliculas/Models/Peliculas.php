<?php
require_once __DIR__ . '/../../config/database.php';

class Peliculas{
    public static function obtenerCartelera(){
        global $conn;
        $sql = "SELECT * FROM peliculas";
        $result = $conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);

    }

    public static function obtenerPelicula($titulo){
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