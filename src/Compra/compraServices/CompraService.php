<?php
require_once __DIR__ . '/../compraModels/Boleto.php';

class CompraService {
    // Reservar un boleto
    public static function reservarBoleto($id_usuario, $id_funcion, $id_asiento) {
        // Llama al método reservar de Boleto y devuelve el ID insertado o false
        return Boleto::reservar($id_usuario, $id_funcion, $id_asiento);
    }

    // Confirmar compra
    public static function comprarBoleto($id_boleto, $metodo_pago, $total_pago) {
        return Boleto::comprar($id_boleto, $metodo_pago, $total_pago);
    }
}
?>