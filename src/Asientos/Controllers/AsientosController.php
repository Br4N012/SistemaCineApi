<?php
require_once __DIR__ . '/../services/AsientosService.php';

class AsientosController {
    public static function getByFuncion($id_funcion) {
        $asientos = AsientosService::obtenerPorFuncion($id_funcion);
        header('Content-Type: application/json');
        echo json_encode($asientos);
    }

    public static function getById($id) {
        $asiento = AsientosService::obtenerPorId($id);
        header('Content-Type: application/json');
        if (!$asiento) {
            header("HTTP/1.1 404 Not Found");
            echo json_encode(["error" => "Asiento no encontrado"]);
            return;
        }
        echo json_encode($asiento);
    }

    public static function create() {
        $data = json_decode(file_get_contents("php://input"), true);
        $id_funcion = $data['id_funcion'];
        $asientos = $data['asientos'];
        if (AsientosService::crearAsientos($id_funcion, $asientos)) {
            header("HTTP/1.1 201 Created");
            echo json_encode(["message" => "Asientos creados exitosamente"]);
        } else {
            header("HTTP/1.1 500 Internal Server Error");
            echo json_encode(["error" => "Error al crear los asientos"]);
        }
    }

    public static function update($id) {
        $data = json_decode(file_get_contents("php://input"), true);
        $disponible = $data['disponible'];
        if (AsientosService::actualizarEstado($id, $disponible)) {
            header("HTTP/1.1 200 OK");
            echo json_encode(["message" => "Estado del asiento actualizado"]);
        } else {
            header("HTTP/1.1 500 Internal Server Error");
            echo json_encode(["error" => "Error al actualizar el asiento"]);
        }
    }

    public static function delete($id) {
        if (AsientosService::eliminarAsiento($id)) {
            header("HTTP/1.1 200 OK");
            echo json_encode(["message" => "Asiento eliminado exitosamente"]);
        } else {
            header("HTTP/1.1 500 Internal Server Error");
            echo json_encode(["error" => "Error al eliminar el asiento"]);
        }
    }
}
?>