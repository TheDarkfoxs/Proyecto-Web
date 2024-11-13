<?php
include 'includes/connection.php'; // Incluye la conexión a la base de datos
session_start();
// Consulta para obtener los productos de la base de datos
$query = "SELECT ProductoID, NombreProducto, DescripcionProducto, Precio, ImagenProducto FROM Productos";
$result = $conn->query($query);

if (isset($_SESSION['UsuarioID'])) {
    if (!isset($_SESSION['fotoPerfil'])) {
        $stmt = $conn->prepare('SELECT FotoPerfil FROM Usuarios WHERE UsuarioID = ?');
        $stmt->bind_param('i', $_SESSION['UsuarioID']);
        $stmt->execute();
        $stmt->bind_result($fotoPerfil);
        $stmt->fetch();
        $stmt->close();
        $_SESSION['fotoPerfil'] = $fotoPerfil ? $fotoPerfil : 'https://via.placeholder.com/40';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>
    <!-- Enlace al archivo CSS externo -->
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Navbar fija en la parte superior -->
    <nav class="navbar">
        <div class="tabs">
            <a href="home.php" class="active">INICIO</a>
            <a href="chats.php">CHATS</a>
            <a href="saved.php">GUARDADOS</a>
            <a href="#mis-productos">MIS PRODUCTOS</a>
        </div>
        <!-- Foto de perfil y opción de cerrar sesión -->
        <div class="profile">
            <?php if (isset($_SESSION['UsuarioID'])): ?>
                <a href="profile.php"><img src="<?php echo $_SESSION['fotoPerfil']; ?>" alt="Foto de perfil"></a>
                <a href="logout.php" class="logout-link">Cerrar sesión</a>
            <?php else: ?>
                <a href="login.php" class="login-link">Iniciar sesión</a>
            <?php endif; ?>
        </div>
    </nav>

    <!-- Contenido principal -->
    <div class="container">
        <h1>Bienvenido a la página</h1>
        <p>Explora los productos disponibles.</p>

        <!-- Cuadrícula de productos -->
        <div class="product-grid">
            <?php while ($row = $result->fetch_assoc()) { ?>
                <div class="product-card">
                    <a href="product_details.php?id=<?php echo $row['ProductoID']; ?>">
                        <img src="<?php echo htmlspecialchars($row['ImagenProducto']); ?>" alt="Producto">
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

<?php
$conn->close(); // Cierra la conexión a la base de datos
?>
