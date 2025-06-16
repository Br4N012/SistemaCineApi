<?php
$host = "localhost";
$user = "root";
<<<<<<< HEAD
$password = "Arbolito123.";
=======
$password = "password";
>>>>>>> 5a4025cdd15885f4da7ecf582b8b08a42ffe8837
$database = "cine";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Error de conexión a la base de datos: " . $conn->connect_error);
}
?>
