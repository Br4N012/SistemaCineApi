<?php
require_once __DIR__ . '/../models/Movimientos.php';

class MovimientosService {
    public static function obtenerTodos() {
        return Movimientos::obtenerTodos();
    }

    public static function obtenerPorId($id) {
        return Movimientos::obtenerPorId($id);
    }

    public static function obtenerPorUsuario($id_usuario) {
        return Movimientos::obtenerPorUsuario($id_usuario);
    }

    public static function crearMovimiento($id_usuario, $id_funcion, $total_pago, $metodo_pago, $fecha) {
        return Movimientos::crearMovimiento($id_usuario, $id_funcion, $total_pago, $metodo_pago, $fecha);
    }

    public static function eliminarMovimiento($id) {
        return Movimientos::eliminarMovimiento($id);
    }
}
?>