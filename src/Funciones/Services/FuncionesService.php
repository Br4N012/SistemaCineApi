<?php
require_once __DIR__ . '/../models/Funciones.php';

class FuncionesService {
    public static function obtenerTodas() {
        return Funciones::obtenerTodas();
    }

    public static function obtenerPorId($id) {
        return Funciones::obtenerPorId($id);
    }

    public static function crearFuncion($id_pelicula, $id_sala, $horario) {
        return Funciones::crearFuncion($id_pelicula, $id_sala, $horario);
    }

    public static function actualizarFuncion($id, $id_pelicula, $id_sala, $horario) {
        return Funciones::actualizarFuncion($id, $id_pelicula, $id_sala, $horario);
    }

    public static function eliminarFuncion($id) {
        return Funciones::eliminarFuncion($id);
    }
}
?>