<?php
include("config/database.php"); 

header('Content-Type: application/json');

$function_id = isset($_GET['function_id']) ? intval($_GET['function_id']) : 0;

if ($function_id === 0) {
    echo json_encode([]);
    exit;
}

// Fetch seats for the given function
$query = "SELECT id, numero_asiento, disponible FROM asientos WHERE id_funcion = ? ORDER BY numero_asiento ASC";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $function_id);
$stmt->execute();
$result = $stmt->get_result();

$asientos = [];
while ($row = $result->fetch_assoc()) {
    $asientos[] = $row;
}

echo json_encode($asientos);

$stmt->close();
$conn->close();
?>