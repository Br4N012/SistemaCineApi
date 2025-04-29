<?php

$host = "localhost";
$user = "root";
$password = "password";
$database = "cine";

$conn= new mysqli($host, $user, $password, $database);
if($conn->connect_error){
    die("Connection failed: " . $conn->connect_error);
}else{
    //echo "Connected successfully";
}


?> 