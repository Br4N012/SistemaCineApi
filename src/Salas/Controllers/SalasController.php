<?php
require_once __DIR__ . '/../services/SalasService.php';

class SalasController {
    public static function index() {
        $salas = SalasService::obtenerTodas();
        header('Content-Type: application/json');
        echo json_encode($salas);
    }

    public static function show($id) {
        $sala = SalasService::obtenerPorId($id);
        header('Content-Type: application/json');

        if (!$sala) {
            header("HTTP/1.1 404 Not Found");
            echo json_encode(["error" => "Sala no encontrada"]);
            return;
        }
        echo json_encode($sala);
    }

    public static function create() {
        $data = json_decode(file_get_contents("php://input"), true);

        $nombre = $data['nombre'];
        $capacidad = $data['capacidad'];

        if (SalasService::crearSala($nombre, $capacidad)) {
            header("HTTP/1.1 201 Created");
            echo json_encode(["message" => "Sala creada exitosamente"]);
        } else {
            header("HTTP/1.1 500 Internal Server Error");
            echo json_encode(["error" => "Error al crear la sala"]);
        }
    }

    public static function update() {
        $data = json_decode(file_get_contents("php://input"), true);

        $id = $data['id'];
        $nombre = $data['nombre'];
        $capacidad = $data['capacidad'];

        if (SalasService::actualizarSala($id, $nombre, $capacidad)) {
            header("HTTP/1.1 200 OK");
            echo json_encode(["message" => "Sala actualizada exitosamente"]);
        } else {
            header("HTTP/1.1 500 Internal Server Error");
            echo json_encode(["error" => "Error al actualizar la sala"]);
        }
    }

    public static function delete($id) {
        if (SalasService::eliminarSala($id)) {
            header("HTTP/1.1 200 OK");
            echo json_encode(["message" => "Sala eliminada exitosamente"]);
        } else {
            header("HTTP/1.1 500 Internal Server Error");
            echo json_encode(["error" => "Error al eliminar la sala"]);
        }
    }
}
?>