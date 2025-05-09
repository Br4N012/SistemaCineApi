<?php
require_once __DIR__ . '/../services/FuncionesService.php';

class FuncionesController {
    public static function index() {
        $funciones = FuncionesService::obtenerTodas();
        header('Content-Type: application/json');
        echo json_encode($funciones);
    }

    public static function show($id) {
        $funcion = FuncionesService::obtenerPorId($id);
        header('Content-Type: application/json');

        if (!$funcion) {
            header("HTTP/1.1 404 Not Found");
            echo json_encode(["error" => "Función no encontrada"]);
            return;
        }
        echo json_encode($funcion);
    }

    public static function create() {
        $data = json_decode(file_get_contents("php://input"), true);

        $id_pelicula = $data['id_pelicula'];
        $id_sala = $data['id_sala'];
        $horario = $data['horario'];

        if (FuncionesService::crearFuncion($id_pelicula, $id_sala, $horario)) {
            header("HTTP/1.1 201 Created");
            echo json_encode(["message" => "Función creada exitosamente"]);
        } else {
            header("HTTP/1.1 500 Internal Server Error");
            echo json_encode(["error" => "Error al crear la función"]);
        }
    }

    public static function update() {
        $data = json_decode(file_get_contents("php://input"), true);

        $id = $data['id'];
        $id_pelicula = $data['id_pelicula'];
        $id_sala = $data['id_sala'];
        $horario = $data['horario'];

        if (FuncionesService::actualizarFuncion($id, $id_pelicula, $id_sala, $horario)) {
            header("HTTP/1.1 200 OK");
            echo json_encode(["message" => "Función actualizada exitosamente"]);
        } else {
            header("HTTP/1.1 500 Internal Server Error");
            echo json_encode(["error" => "Error al actualizar la función"]);
        }
    }

    public static function delete($id) {
        if (FuncionesService::eliminarFuncion($id)) {
            header("HTTP/1.1 200 OK");
            echo json_encode(["message" => "Función eliminada exitosamente"]);
        } else {
            header("HTTP/1.1 500 Internal Server Error");
            echo json_encode(["error" => "Error al eliminar la función"]);
        }
    }
}
?>