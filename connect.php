<?php
$host = 'localhost';     
$usuario = 'root';      
$senha = '';             
$banco = 'gamoraloja';      

$conn = new mysqli($host, $usuario, $senha, $banco);      


if ($conn->connect_error) {
    error_log("Database connection failed: " . $conn->connect_error);
    
    die("Connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8");
?>