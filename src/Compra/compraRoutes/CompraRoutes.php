<?php
require_once __DIR__ . '/../compraController/CompraController.php';

$request_uri = $_SERVER['REQUEST_URI'];
$request_method = $_SERVER['REQUEST_METHOD'];

if ($request_method === "POST" && $request_uri === '/api/compra/reservar') {
    CompraController::reservar();
}


else if ($request_method === "POST" && $request_uri === '/api/compra/comprar') {
    CompraController::comprar();
}


?>