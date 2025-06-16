<?php
require_once __DIR__ . '/../services/SalasService.php';
require_once __DIR__ . '/../../../handler/XmlHandler.php';

class SalasController {
    public static function index() {
        $salas = SalasService::obtenerTodas();
        header('Content-Type: application/xml');
        echo XmlHandler::generarXml($salas, 'salas', 'sala');
    }

    public static function show($id) {
        $sala = SalasService::obtenerPorId($id);
        header('Content-Type: application/xml');

        if (!$sala) {
            header("HTTP/1.1 404 Not Found");
            echo "<error>Sala no encontrada</error>";
            return;
        }
        echo XmlHandler::generarXml($sala, 'sala');
    }

    public static function create() {
        $data = simplexml_load_string(file_get_contents("php://input"));

        $nombre = (string)$data->nombre;
        $capacidad = (int)$data->capacidad;

        if (SalasService::crearSala($nombre, $capacidad)) {
            header("HTTP/1.1 201 Created");
            echo "<message>Sala creada exitosamente</message>";
        } else {
            header("HTTP/1.1 500 Internal Server Error");
            echo "<error>Error al crear la sala</error>";
        }
    }

    public static function update() {
        $data = simplexml_load_string(file_get_contents("php://input"));

        $id = (int)$data->id;
        $nombre = (string)$data->nombre;
        $capacidad = (int)$data->capacidad;

        if (SalasService::actualizarSala($id, $nombre, $capacidad)) {
            header("HTTP/1.1 200 OK");
            echo "<message>Sala actualizada exitosamente</message>";
        } else {
            header("HTTP/1.1 500 Internal Server Error");
            echo "<error>Error al actualizar la sala</error>";
        }
    }

    public static function delete($id) {
        if (SalasService::eliminarSala($id)) {
            header("HTTP/1.1 200 OK");
            echo "<message>Sala eliminada exitosamente</message>";
        } else {
            header("HTTP/1.1 500 Internal Server Error");
            echo "<error>Error al eliminar la sala</error>";
        }
    }
}
?>