<?php
$host = "localhost";// Dirección del servidor MySQL (normalmente es 'localhost')
$username = "root"; // Tu nombre de usuario de MySQL
$password = ""; // La contraseña de MySQL
$dbname = "chat"; // Nombre de la base de datos

// Crear la conexión
$conn = new mysqli($host, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
