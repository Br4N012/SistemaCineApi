<?php
require_once __DIR__ . '/../controllers/MovimientosController.php';

$request_uri = $_SERVER['REQUEST_URI'];
$request_method = $_SERVER["REQUEST_METHOD"];

if ($request_method === "GET" && $request_uri === '/api/movimientos') {
    MovimientosController::index();
} else if ($request_method === "GET" && preg_match('/\/api\/movimientos\/usuario\/(\d+)/', $request_uri, $matches)) {
    $id_usuario = $matches[1];
    MovimientosController::showByUsuario($id_usuario);
} else if ($request_method === "GET" && preg_match('/\/api\/movimientos\/(\d+)/', $request_uri, $matches)) {
    $id = $matches[1];
    MovimientosController::show($id);
} else if ($request_method === "POST" && $request_uri === '/api/movimientos') {
    MovimientosController::create();
} else if ($request_method === "DELETE" && preg_match('/\/api\/movimientos\/(\d+)/', $request_uri, $matches)) {
    $id = $matches[1];
    MovimientosController::delete($id);
} else {
    header("HTTP/1.1 404 Not Found");
}
?>