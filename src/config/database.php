<?php
$host = "localhost";
$user = "root";
$password = "Arbolito123.";
$database = "cine";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Error de conexión a la base de datos: " . $conn->connect_error);
}
?>
