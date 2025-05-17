<?php
require_once __DIR__ . '/../services/MovimientosService.php';
require_once __DIR__ . '/../../../handler/XmlHandler.php';

class MovimientosController {
    public static function index() {
        $movimientos = MovimientosService::obtenerTodos();
        header('Content-Type: application/json');
        echo json_encode($movimientos);
    }

    public static function show($id) {
        $movimiento = MovimientosService::obtenerPorId($id);
        header('Content-Type: application/json');
        if (!$movimiento) {
            header("HTTP/1.1 404 Not Found");
            echo json_encode(["error" => "Movimiento no encontrado"]);
            return;
        }
        echo json_encode($movimiento);
    }

    public static function showByUsuario($id_usuario) {
        $movimientos = MovimientosService::obtenerPorUsuario($id_usuario);
        header('Content-Type: application/json');
        echo json_encode($movimientos);
    }

    public static function create() {
        $data = json_decode(file_get_contents("php://input"), true);

        $id_usuario = $data['id_usuario'];
        $id_funcion = $data['id_funcion'];
        $total_pago = $data['total_pago'];
        $metodo_pago = $data['metodo_pago'];
        $fecha = $data['fecha'];

        if (MovimientosService::crearMovimiento($id_usuario, $id_funcion, $total_pago, $metodo_pago, $fecha)) {
            header("HTTP/1.1 201 Created");
            echo json_encode(["message" => "Movimiento registrado exitosamente"]);
        } else {
            header("HTTP/1.1 500 Internal Server Error");
            echo json_encode(["error" => "Error al registrar el movimiento"]);
        }
    }

    public static function delete($id) {
        if (MovimientosService::eliminarMovimiento($id)) {
            header("HTTP/1.1 200 OK");
            echo json_encode(["message" => "Movimiento eliminado exitosamente"]);
        } else {
            header("HTTP/1.1 500 Internal Server Error");
            echo json_encode(["error" => "Error al eliminar el movimiento"]);
        }
    }
}
?>