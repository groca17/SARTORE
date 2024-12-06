<?php
// Nombre del servidor donde se aloja la base de datos
$servername = "localhost";

// Nombre de usuario para acceder a la base de datos
$username = "admin";

// Contraseña correspondiente al usuario
$password = "12345678";

// Nombre de la base de datos a la que se conectará
$dbname = "sartore";

// Crear conexión a la base de datos utilizando la clase mysqli
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar si la conexión fue exitosa
if ($conn->connect_error) {
    // Si hay un error en la conexión, se muestra un mensaje y se detiene la ejecución del script
    die("Conexión fallida: " . $conn->connect_error);
}

// Si no hay errores, la conexión está lista para ser utilizada
?>
