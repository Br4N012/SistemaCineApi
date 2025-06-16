<?php
require_once __DIR__ . '/../services/AsientosService.php';

class AsientosController {


    public static function getByFuncion($conn, $id_funcion) { 
        $asientosService = new AsientosService($conn); // Llama al método de instancia
        header('Content-Type: application/json');
        echo json_encode($asientos);
    }

    public static function getById($conn, $id) { 
        $asientosService = new AsientosService($conn);
        $asiento = $asientosService->obtenerPorId($id);
        header('Content-Type: application/json');
        if (!$asiento) {
            header("HTTP/1.1 404 Not Found");
            echo json_encode(["error" => "Asiento no encontrado"]);
            return;
        }
        echo json_encode($asiento);
    }

    public static function create($conn) { 
        $data = json_decode(file_get_contents("php://input"), true);
        $id_funcion = $data['id_funcion'];
        $asientos = $data['asientos'];
        $asientosService = new AsientosService($conn);
        if ($asientosService->crearAsientos($id_funcion, $asientos)) {
            header("HTTP/1.1 201 Created");
            echo json_encode(["message" => "Asientos creados exitosamente"]);
        } else {
            header("HTTP/1.1 500 Internal Server Error");
            echo json_encode(["error" => "Error al crear los asientos"]);
        }
    }

    public static function update($conn, $id) { 
        $data = json_decode(file_get_contents("php://input"), true);
        $disponible = $data['disponible'];
        $asientosService = new AsientosService($conn);
        if ($asientosService->actualizarEstado($id, $disponible)) {
            header("HTTP/1.1 200 OK");
            echo json_encode(["message" => "Estado del asiento actualizado"]);
        } else {
            header("HTTP/1.1 500 Internal Server Error");
            echo json_encode(["error" => "Error al actualizar el asiento"]);
        }
    }

    public static function delete($conn, $id) { 
        $asientosService = new AsientosService($conn);
        if ($asientosService->eliminarAsiento($id)) {
            header("HTTP/1.1 200 OK");
            echo json_encode(["message" => "Asiento eliminado exitosamente"]);
        } else {
            header("HTTP/1.1 500 Internal Server Error");
            echo json_encode(["error" => "Error al eliminar el asiento"]);
        }
    }
}
?>