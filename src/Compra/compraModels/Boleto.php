<?php
require_once __DIR__ . '/../../config/database.php';

class Boleto {
    // Reservar un boleto (cambia estado a 'reservado' temporalmente)
    public static function reservar($id_usuario, $id_funcion, $id_asiento) {
        global $conn;
        $sql = "INSERT INTO boletos (id_usuario, id_funcion, id_asiento, estado) VALUES (?, ?, ?, 'disponible')";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iii", $id_usuario, $id_funcion, $id_asiento);
        if ($stmt->execute()) {
            return $conn->insert_id; // Devuelve el ID insertado
        }
        return false;
    }

    // Confirmar compra (cambia estado a 'comprado' y registra pago)
    public static function comprar($id_boleto, $metodo_pago, $total_pago) {
        global $conn;
        
        // 1. Actualizar estado del boleto
        $sql = "UPDATE boletos SET estado = 'comprado', fecha_compra = NOW() WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_boleto);
        
        if ($stmt->execute()) {
            // 2. Registrar el movimiento de pago
            $sql_movimiento = "INSERT INTO movimientos (id_usuario, id_funcion, total_pago, metodo_pago, fecha) 
                              SELECT id_usuario, id_funcion, ?, ?, NOW() FROM boletos WHERE id = ?";
            $stmt_mov = $conn->prepare($sql_movimiento);
            $stmt_mov->bind_param("dsi", $total_pago, $metodo_pago, $id_boleto);
            return $stmt_mov->execute();
        }
        return false;
    }

    // Verificar si un asiento está disponible
    public static function verificarDisponibilidad($id_asiento, $id_funcion) {
        global $conn;
        $sql = "SELECT disponible FROM asientos WHERE id = ? AND id_funcion = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $id_asiento, $id_funcion);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc()['disponible'] ?? false;
    }
}
?>