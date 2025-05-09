<?php
require_once __DIR__ . '/../controllers/SalasController.php';

$request_uri = $_SERVER['REQUEST_URI'];
$request_method = $_SERVER["REQUEST_METHOD"];

if ($request_method === "GET" && $request_uri === '/api/salas') {
    SalasController::index();
}

else if ($request_method === "GET" && preg_match('/\/api\/salas\/(\d+)/', $request_uri, $matches)) {
    $id = $matches[1];
    SalasController::show($id);
}

else if ($request_method === "POST" && $request_uri === '/api/salas') {
    SalasController::create();
}

else if ($request_method === "PUT" && $request_uri === '/api/salas') {
    SalasController::update();
}

else if ($request_method === "DELETE" && preg_match('/\/api\/salas\/(\d+)/', $request_uri, $matches)) {
    $id = $matches[1];
    SalasController::delete($id);
}

else {
    header("HTTP/1.1 404 Not Found");
}
?>