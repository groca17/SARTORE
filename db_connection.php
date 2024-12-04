<?php
$host = "localhost:8889";
$username = "root"; // Cambia si tienes otro usuario
$password = ""; // Cambia si tienes otra contraseña
$dbname = "sartore";
$puerto = 3306;

// Crear conexión
$conn = new mysqli($host, $username, $password, $dbname, $puerto);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
