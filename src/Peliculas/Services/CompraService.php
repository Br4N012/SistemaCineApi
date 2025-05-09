<?php
require_once __DIR__ . '/../models/Boleto.php';

class CompraService {
    // Reservar un boleto
    public static function reservarBoleto($id_usuario, $id_funcion, $id_asiento) {
        if (!Boleto::verificarDisponibilidad($id_asiento, $id_funcion)) {
            throw new Exception("El asiento no está disponible.");
        }
        return Boleto::reservar($id_usuario, $id_funcion, $id_asiento);
    }

    // Confirmar compra
    public static function comprarBoleto($id_boleto, $metodo_pago, $total_pago) {
        return Boleto::comprar($id_boleto, $metodo_pago, $total_pago);
    }
}
?>