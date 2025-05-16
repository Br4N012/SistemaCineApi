<?php
require_once __DIR__ . '/../controllers/CompraController.php';

$request_uri = $_SERVER['REQUEST_URI'];
$request_method = $_SERVER['REQUEST_METHOD'];

if ($request_method === "POST" && $request_uri === '/api/peliculas/reservar') {
    CompraController::reservar();
}


else if ($request_method === "POST" && $request_uri === '/api/peliculas/comprar') {
    CompraController::comprar();
}


?>