<?php
require_once __DIR__ . '/../services/FuncionesService.php';
require_once __DIR__ . '/../../../handler/XmlHandler.php';

class FuncionesController {
    public static function index() {
        $funciones = FuncionesService::obtenerTodas();
        header('Content-Type: application/xml');
        echo XmlHandler::generarXml($funciones, 'funciones', 'funcion');
    }

    public static function show($id) {
        $funcion = FuncionesService::obtenerPorId($id);
        header('Content-Type: application/xml');

        if (!$funcion) {
            header("HTTP/1.1 404 Not Found");
            echo "<error>Función no encontrada</error>";
            return;
        }
        echo XmlHandler::generarXml($funcion, 'funcion');
    }

    public static function create() {
        $data = simplexml_load_string(file_get_contents("php://input"));

        $id_pelicula = (int)$data->id_pelicula;
        $id_sala = (int)$data->id_sala;
        $horario = (string)$data->horario;

        if (FuncionesService::crearFuncion($id_pelicula, $id_sala, $horario)) {
            header("HTTP/1.1 201 Created");
            echo "<message>Función creada exitosamente</message>";
        } else {
            header("HTTP/1.1 500 Internal Server Error");
            echo "<error>Error al crear la función</error>";
        }
    }

    public static function update() {
        $data = simplexml_load_string(file_get_contents("php://input"));

        $id = (int)$data->id;
        $id_pelicula = (int)$data->id_pelicula;
        $id_sala = (int)$data->id_sala;
        $horario = (string)$data->horario;

        if (FuncionesService::actualizarFuncion($id, $id_pelicula, $id_sala, $horario)) {
            header("HTTP/1.1 200 OK");
            echo "<message>Función actualizada exitosamente</message>";
        } else {
            header("HTTP/1.1 500 Internal Server Error");
            echo "<error>Error al actualizar la función</error>";
        }
    }

    public static function delete($id) {
        if (FuncionesService::eliminarFuncion($id)) {
            header("HTTP/1.1 200 OK");
            echo "<message>Función eliminada exitosamente</message>";
        } else {
            header("HTTP/1.1 500 Internal Server Error");
            echo "<error>Error al eliminar la función</error>";
        }
    }
    public static function getByPelicula($id_pelicula) {
    $funciones = FuncionesService::obtenerPorPelicula($id_pelicula);
    header('Content-Type: application/json');
    echo json_encode($funciones);
}
}
?>