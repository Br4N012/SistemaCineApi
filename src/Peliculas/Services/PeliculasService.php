<?php
require_once __DIR__ . '/../models/Peliculas.php';

class PeliculasService {
    public static function obtenerTodos() {
        return Peliculas::obtenerTodos();
    }

    public static function obtenerPorId($id) {
        return Peliculas::obtenerPorId($id);
    }

    public static function ordenarPorClasificacion() {
        return Peliculas::ordenarPorClasificacion();
    }

    public static function crearPelicula($titulo, $sinopsis, $duracion, $clasificacion, $genero) {
        return Peliculas::crearPelicula($titulo, $sinopsis, $duracion, $clasificacion, $genero);
    }

    public static function actualizarPelicula($id, $titulo, $sinopsis, $duracion, $clasificacion, $genero) {
        return Peliculas::actualizarPelicula($id, $titulo, $sinopsis, $duracion, $clasificacion, $genero);
    }

    public static function eliminarPelicula($id) {
        return Peliculas::eliminarPelicula($id);
    }
    public static function obtenerPeliculaPorNombre($titulo) {
        return Peliculas::obtenerPeliculaPorNombre($titulo);
    }
}
?>