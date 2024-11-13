<?php
// Datos de conexión
$host = 'localhost';
$usuario = 'root';
$contraseña = ''; // Deja esto vacío si no tienes una contraseña
$base_datos = 'uniVentas';

// Conexión a la base de datos
$conn = new mysqli($host, $usuario, $contraseña, $base_datos);

// Verificación de conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
