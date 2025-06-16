<?php
include("config/database.php"); 

header('Content-Type: application/json');
$response = ['success' => false, 'message' => ''];

$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!isset($data['function_id'], $data['seat_ids'], $data['total_price'], $data['payment_method'])) {
    $response['message'] = 'Datos incompletos para la compra.';
    echo json_encode($response);
    exit;
}

$function_id = intval($data['function_id']);
$seat_ids = $data['seat_ids']; 
$total_price = floatval($data['total_price']);
$payment_method = htmlspecialchars($data['payment_method']);
$movie_title = htmlspecialchars($data['movie_title']); 
$showtime = htmlspecialchars($data['showtime']);    
$room_name = htmlspecialchars($data['room_name']);   

if (empty($seat_ids) || !is_array($seat_ids)) {
    $response['message'] = 'No se seleccionaron asientos.';
    echo json_encode($response);
    exit;
}

$conn->begin_transaction(); 

try {
    $placeholders = implode(',', array_fill(0, count($seat_ids), '?'));
    $types = str_repeat('i', count($seat_ids)); 

    $stmt = $conn->prepare("SELECT id, disponible FROM asientos WHERE id IN ($placeholders) AND id_funcion = ?");

    $bind_params_check = array_merge($seat_ids, [$function_id]);
    $stmt->bind_param($types . 'i', ...$bind_params_check);

    $stmt->execute();
    $result = $stmt->get_result();

    $available_count = 0;
    while ($row = $result->fetch_assoc()) {
        if ($row['disponible'] == 1) { 
            $available_count++;
        }
    }
    $stmt->close();

    if ($available_count !== count($seat_ids)) {
        $conn->rollback();
        $response['message'] = 'Alguno de los asientos seleccionados ya no está disponible.';
        echo json_encode($response);
        exit;
    }

    $stmt = $conn->prepare("UPDATE asientos SET disponible = FALSE WHERE id IN ($placeholders)");
    $stmt->bind_param($types, ...$seat_ids);
    $stmt->execute();
    $stmt->close();

 
    $guest_user_id = 1;

    $insert_boleto_query = "INSERT INTO boletos (id_usuario, id_funcion, id_asiento, estado, fecha_compra) VALUES (?, ?, ?, 'comprado', NOW())";
    $stmt_boleto = $conn->prepare($insert_boleto_query);
    foreach ($seat_ids as $seat_id) {
        $stmt_boleto->bind_param("iii", $guest_user_id, $function_id, $seat_id);
        $stmt_boleto->execute();
    }
    $stmt_boleto->close();

    $insert_movimiento_query = "INSERT INTO movimientos (id_usuario, id_funcion, total_pago, metodo_pago, fecha) VALUES (?, ?, ?, ?, NOW())";
    $stmt_movimiento = $conn->prepare($insert_movimiento_query);
    $stmt_movimiento->bind_param("iids", $guest_user_id, $function_id, $total_price, $payment_method);
    $stmt_movimiento->execute();
    $stmt_movimiento->close();

    $conn->commit(); 

    $seat_numbers_query = "SELECT numero_asiento FROM asientos WHERE id IN ($placeholders)";
    $stmt_seats = $conn->prepare($seat_numbers_query);
    $stmt_seats->bind_param($types, ...$seat_ids);
    $stmt_seats->execute();
    $result_seats = $stmt_seats->get_result();
    $bought_seat_numbers = [];
    while ($row = $result_seats->fetch_assoc()) {
        $bought_seat_numbers[] = $row['numero_asiento'];
    }
    $stmt_seats->close();

    $response['success'] = true;
    $response['message'] = 'Compra realizada con éxito.';
    $response['receipt'] = [
        'movie_title' => $movie_title,
        'showtime' => $showtime,
        'room_name' => $room_name,
        'seats_numbers' => implode(', ', $bought_seat_numbers),
        'payment_method' => $payment_method,
        'total_price' => $total_price,
        'purchase_date' => date('Y-m-d H:i:s')
    ];

} catch (Exception $e) {
    $conn->rollback(); 
    $response['message'] = 'Error en la transacción: ' . $e->getMessage();
}

echo json_encode($response);
$conn->close();
?>