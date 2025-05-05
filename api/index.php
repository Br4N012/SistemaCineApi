<?php
// api/index.php
$request_uri = $_SERVER['REQUEST_URI'];
$request_method = $_SERVER['REQUEST_METHOD'];

if (strpos($request_uri, '/api/peliculas') === 0) {
    include '../src/peliculas/index.php';
} else {
    header("HTTP/1.1 404 Not Found");
}
?>