<?php
// api/index.php

$request_uri = $_SERVER['REQUEST_URI'];
$request_method = $_SERVER['REQUEST_METHOD'];

if (strpos($request_uri, '/api/peliculas') === 0) {
    include '../src/Peliculas/routes/PeliculasRoutes.php';
} else {
    header("HTTP/1.0 404 Not Found");
}

?>
