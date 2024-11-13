<?php
include 'includes/connection.php';
session_start();

if (!isset($_SESSION['UsuarioID'])) {
    header('Location: login.php');
    exit();
}

$usuarioID = $_SESSION['UsuarioID'];

// Consulta para obtener los productos guardados
$stmt = $conn->prepare('SELECT p.ProductoID, p.NombreProducto, p.DescripcionProducto, p.Precio, p.ImagenProducto 
                        FROM Guardados g 
                        JOIN Productos p ON g.ProductoID = p.ProductoID 
                        WHERE g.UsuarioID = ?');
$stmt->bind_param('i', $usuarioID);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos Guardados</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <nav class="navbar">
        <div class="tabs">
            <a href="home.php">INICIO</a>
            <a href="chats.php">CHATS</a>
            <a href="saved.php" class="active">GUARDADOS</a>
            <a href="#mis-productos">MIS PRODUCTOS</a>
        </div>
        <div class="profile">
            <a href="profile.php"><img src="<?php echo $_SESSION['fotoPerfil'] ?? 'https://via.placeholder.com/40'; ?>" alt="Foto de perfil"></a>
        </div>
    </nav>

    <div class="container">
        <h1>Productos Guardados</h1>
        <div class="product-grid">
            <?php while ($row = $result->fetch_assoc()) { ?>
                <div class="product-card">
                    <a href="detalle_producto.php?id=<?php echo $row['ProductoID']; ?>">
                        <img src="<?php echo $row['ImagenProducto'] ? $row['ImagenProducto'] : 'default-product.png'; ?>" alt="Producto">
                        <div class="product-info">
                            <p class="product-name"><?php echo htmlspecialchars($row['NombreProducto']); ?></p>
                            <p class="product-description"><?php echo htmlspecialchars($row['DescripcionProducto']); ?></p>
                            <p class="product-price">$<?php echo htmlspecialchars($row['Precio']); ?></p>
                        </div>
                    </a>
                </div>
            <?php } ?>
        </div>
    </div>
</body>
</html>
