<?php
require_once __DIR__ . '/../services/PeliculasService.php';
require_once __DIR__ . '/../../../handler/XmlHandler.php';

class PeliculasController {
    public static function index() {
        $peliculas = PeliculasService::obtenerTodos();
        header('Content-Type: application/xml');        
        echo XmlHandler::generarXml($peliculas, 'peliculas', 'pelicula');
    }

    public static function show($id) {
        $pelicula = PeliculasService::obtenerPorId($id);
        header('Content-Type: application/xml');

        if (!$pelicula) {
            header("HTTP/1.1 404 Not Found");
            echo "<error>Película no encontrada</error>";
            return;
        }
        echo XmlHandler::generarXml($pelicula, 'pelicula');
    }

    public static function ordenarPorClasificacion() {
        $peliculas = PeliculasService::ordenarPorClasificacion();
        header('Content-Type: application/xml');
        echo XmlHandler::generarXml($peliculas, 'peliculas', 'pelicula');
    }

    public static function create() {
        $data = file_get_contents("php://input");
        $xml = simplexml_load_string($data);

        $titulo = (string)$xml->titulo;
        $sinopsis = (string)$xml->sinopsis;
        $duracion = (int)$xml->duracion;
        $clasificacion = (string)$xml->clasificacion;
        $genero = (string)$xml->genero;

        if (PeliculasService::crearPelicula($titulo, $sinopsis, $duracion, $clasificacion, $genero)) {
            header("HTTP/1.1 201 Created");
            echo "<message>Película creada exitosamente</message>";
        } else {
            header("HTTP/1.1 500 Internal Server Error");
            echo "<error>Error al crear la película</error>";
        }
    }

    public static function update() {
        $data = file_get_contents("php://input");
        $xml = simplexml_load_string($data);

        $id = (int)$xml->id;
        $titulo = (string)$xml->titulo;
        $sinopsis = (string)$xml->sinopsis;
        $duracion = (int)$xml->duracion;
        $clasificacion = (string)$xml->clasificacion;
        $genero = (string)$xml->genero;

        if (PeliculasService::actualizarPelicula($id, $titulo, $sinopsis, $duracion, $clasificacion, $genero)) {
            header("HTTP/1.1 200 OK");
            echo "<message>Película actualizada exitosamente</message>";
        } else {
            header("HTTP/1.1 500 Internal Server Error");
            echo "<error>Error al actualizar la película</error>";
        }
    }

    public static function delete($id) {
        if (PeliculasService::eliminarPelicula($id)) {
            header("HTTP/1.1 200 OK");
            echo "<message>Película eliminada exitosamente</message>";
        } else {
            header("HTTP/1.1 500 Internal Server Error");
            echo "<error>Error al eliminar la película</error>";
        }
    }
}
?>