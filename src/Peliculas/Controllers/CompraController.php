<?php
require_once __DIR__ . '/../services/CompraService.php';
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
        $data = file_get_contents("php://input");
        $xml = simplexml_load_string($data);

        $id_boleto = (int)$xml->id_boleto;
        $metodo_pago = (string)$xml->metodo_pago;
        $total_pago = (float)$xml->total_pago;

        if (CompraService::comprarBoleto($id_boleto, $metodo_pago, $total_pago)) {
            header("HTTP/1.1 200 OK");
            echo "<message>Compra confirmada</message>";
        } else {
            header("HTTP/1.1 500 Internal Server Error");
            echo "<error>Error al confirmar la compra</error>";
        }
    }
}
?>