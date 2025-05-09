<?php
require_once __DIR__ . '/../models/Salas.php';

class SalasService {
    public static function obtenerTodas() {
        return Salas::obtenerTodas();
    }

    public static function obtenerPorId($id) {
        return Salas::obtenerPorId($id);
    }

    public static function crearSala($nombre, $capacidad) {
        return Salas::crearSala($nombre, $capacidad);
    }

    public static function actualizarSala($id, $nombre, $capacidad) {
        return Salas::actualizarSala($id, $nombre, $capacidad);
    }

    public static function eliminarSala($id) {
        return Salas::eliminarSala($id);
    }
}
?>