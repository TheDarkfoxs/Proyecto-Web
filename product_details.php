<?php
include 'includes/connection.php';
session_start();

if (!isset($_GET['id'])) {
    echo "Producto no especificado.";
    exit();
}

$productoID = $_GET['id'];

// Consulta para obtener los detalles del producto y el usuario que lo subió
$stmt = $conn->prepare('SELECT p.NombreProducto, p.DescripcionProducto, p.Precio, p.ImagenProducto, u.UsuarioID 
                        FROM Productos p 
                        JOIN Usuarios u ON p.UsuarioID = u.UsuarioID 
                        WHERE p.ProductoID = ?');
$stmt->bind_param('i', $productoID);
$stmt->execute();
$stmt->bind_result($nombreProducto, $descripcion, $precio, $imagenProducto, $usuarioID);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle del Producto</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <nav class="navbar">
        <div class="tabs">
            <a href="home.php">INICIO</a>
            <a href="chats.php">CHATS</a>
            <a href="saved.php">GUARDADOS</a>
            <a href="#mis-productos">MIS PRODUCTOS</a>
        </div>
        <div class="profile">
            <a href="profile.php"><img src="<?php echo $imagenProducto ? $imagenProducto : 'default-product.png'; ?>" alt="Foto de producto"></a>
        </div>
    </nav>

    <div class="container">
        <h1><?php echo htmlspecialchars($nombreProducto); ?></h1>
        <div class="product-detail">
            <p><strong>Descripción:</strong> <?php echo htmlspecialchars($descripcion); ?></p>
            <p><strong>Precio:</strong> $<?php echo htmlspecialchars($precio); ?></p>

            <!-- Botones de acción -->
            <div class="action-buttons">
                <a href="profile.php?usuario_id=<?php echo $usuarioID; ?>" class="button">Ver Perfil</a>
                <a href="chats.php?usuario_id=<?php echo $usuarioID; ?>" class="button">Enviar Mensaje</a>
                <form method="post" action="save_product.php">
                    <input type="hidden" name="producto_id" value="<?php echo $productoID; ?>">
                    <button type="submit" class="button">Guardar Producto</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
