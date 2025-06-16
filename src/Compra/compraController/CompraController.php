<?php
require_once __DIR__ . '/../compraServices/CompraService.php';
require_once __DIR__ . '/../../../handler/XmlHandler.php';

class CompraController {
    // POST /api/boletos/reservar
    public static function reservar() {
        $data = file_get_contents("php://input");
        $xml = simplexml_load_string($data);

        $id_usuario = (int)$xml->id_usuario;
        $id_funcion = (int)$xml->id_funcion;
        $id_asiento = (int)$xml->id_asiento;

        try {
            if (CompraService::reservarBoleto($id_usuario, $id_funcion, $id_asiento)) {
                header("HTTP/1.1 201 Created");
                echo "<message>Boleto reservado temporalmente</message>";
            }
        } catch (Exception $e) {
            header("HTTP/1.1 400 Bad Request");
            echo "<error>{$e->getMessage()}</error>";
        }
    }

    // POST /api/boletos/comprar
    public static function comprar() {
        $data = json_decode(file_get_contents("php://input"));

        // Validar datos
        if (!isset($data->function_id, $data->seat_ids, $data->total_price, $data->payment_method)) {
            header("HTTP/1.1 400 Bad Request");
            echo json_encode(["success" => false, "message" => "Datos incompletos"]);
            return;
        }

        $function_id = (int)$data->function_id;
        $seat_ids = $data->seat_ids;
        $total_price = (float)$data->total_price;
        $payment_method = (string)$data->payment_method;
        $usuario_id = 1; // O el usuario real si tienes login

        $success = true;
        $errores = [];
        $boletos_ids = [];

        foreach ($seat_ids as $seat_id) {
            $boleto_id = CompraService::reservarBoleto($usuario_id, $function_id, $seat_id);
            if ($boleto_id) {
                $boletos_ids[] = $boleto_id;
                $compra = CompraService::comprarBoleto($boleto_id, $payment_method, $total_price / count($seat_ids));
                // ACTUALIZA EL ESTADO DEL ASIENTO A NO DISPONIBLE
                require_once __DIR__ . '/../../Asientos/Models/Asientos.php';
                Asientos::actualizarEstado($seat_id, 0);
                if (!$compra) {
                    $success = false;
                    $errores[] = "Error al comprar boleto para asiento $seat_id";
                }
            } else {
                $success = false;
                $errores[] = "No se pudo reservar el asiento $seat_id";
            }
        }

        if ($success) {
            header("Content-Type: application/json");
            echo json_encode(["success" => true, "message" => "Compra confirmada", "boletos" => $boletos_ids]);
        } else {
            header("HTTP/1.1 500 Internal Server Error");
            echo json_encode(["success" => false, "message" => "Error al confirmar la compra", "errores" => $errores]);
        }
    }
}
?>