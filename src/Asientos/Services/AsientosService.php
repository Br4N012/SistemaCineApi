<?php
require_once __DIR__ . '/../models/Asientos.php';

class AsientosService {
    public static function obtenerPorFuncion($id_funcion) {
        return Asientos::obtenerPorFuncion($id_funcion);
    }

    public static function obtenerPorId($id) {
        return Asientos::obtenerPorId($id);
    }

    public static function crearAsientos($id_funcion, $asientos) {
        return Asientos::crearAsientos($id_funcion, $asientos);
    }

    public static function actualizarEstado($id, $disponible) {
        return Asientos::actualizarEstado($id, $disponible);
    }

    public static function eliminarAsiento($id) {
        return Asientos::eliminarAsiento($id);
    }
}
?>