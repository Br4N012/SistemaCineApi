<?php
require_once __DIR__ . '/../controllers/AsientosController.php';

$request_uri = $_SERVER['REQUEST_URI'];
$request_method = $_SERVER["REQUEST_METHOD"];

if ($request_method === "GET" && preg_match('/\/api\/asientos\/funcion\/(\d+)/', $request_uri, $matches)) {
    $id_funcion = $matches[1];
    AsientosController::getByFuncion($id_funcion);
    exit;
} else if ($request_method === "GET" && preg_match('/\/api\/asientos\/(\d+)/', $request_uri, $matches)) {
    $id = $matches[1];
    AsientosController::getById($id);
    exit;
} else if ($request_method === "POST" && $request_uri === '/api/asientos') {
    AsientosController::create();
    exit;
} else if ($request_method === "PUT" && preg_match('/\/api\/asientos\/(\d+)/', $request_uri, $matches)) {
    $id = $matches[1];
    AsientosController::update($id);
    exit;
} else if ($request_method === "DELETE" && preg_match('/\/api\/asientos\/(\d+)/', $request_uri, $matches)) {
    $id = $matches[1];
    AsientosController::delete($id);
    exit;
}

// Si ninguna ruta coincide, responde 404:
header("HTTP/1.1 404 Not Found");
echo json_encode(["error" => "Ruta no encontrada"]);
exit;
?>