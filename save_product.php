<?php
include 'includes/connection.php';
session_start();

if (!isset($_SESSION['UsuarioID'])) {
    header('Location: login.php');
    exit();
}

$usuarioID = $_SESSION['UsuarioID'];
$productoID = $_POST['producto_id'];

// Consulta para verificar si ya está guardado
$checkStmt = $conn->prepare('SELECT * FROM Guardados WHERE UsuarioID = ? AND ProductoID = ?');
$checkStmt->bind_param('ii', $usuarioID, $productoID);
$checkStmt->execute();
$checkStmt->store_result();

if ($checkStmt->num_rows == 0) {
    $stmt = $conn->prepare('INSERT INTO Guardados (UsuarioID, ProductoID) VALUES (?, ?)');
    $stmt->bind_param('ii', $usuarioID, $productoID);
    $stmt->execute();
    $stmt->close();
    echo "Producto guardado con éxito.";
} else {
    echo "El producto ya está guardado.";
}
$checkStmt->close();
$conn->close();
?>
