<?php
include("config/database.php"); 

header('Content-Type: application/json');

$pelicula_id = isset($_GET['pelicula_id']) ? intval($_GET['pelicula_id']) : 0;

if ($pelicula_id === 0) {
    echo json_encode([]);
    exit;
}

$query = "SELECT f.id, f.horario, f.id_sala, s.nombre AS sala_nombre
          FROM funciones f
          JOIN salas s ON f.id_sala = s.id
          WHERE f.id_pelicula = ?
          ORDER BY f.horario ASC";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $pelicula_id);
$stmt->execute();
$result = $stmt->get_result();

$funciones = [];
while ($row = $result->fetch_assoc()) {
    $funciones[] = $row;
}

echo json_encode($funciones);

$stmt->close();
$conn->close();
?>