<?php
$servername = "localhost";
$username = "admin";
$password = "12345678";
$dbname = "sartore";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
