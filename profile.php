<?php
include 'includes/connection.php'; // Incluye la conexión a la base de datos
session_start();

if (!isset($_SESSION['UsuarioID'])) {
    header('Location: login.php'); // Redirige al inicio de sesión si no está autenticado
    exit();
}

$usuarioID = $_SESSION['UsuarioID'];

// Obtiene los datos del usuario de la base de datos
$stmt = $conn->prepare('SELECT NombreUsuario, Email, Telefono, Direccion, FechaRegistro, FotoPerfil FROM Usuarios WHERE UsuarioID = ?');
$stmt->bind_param('i', $usuarioID);
$stmt->execute();
$stmt->bind_result($nombreUsuario, $email, $telefono, $direccion, $fechaRegistro, $fotoPerfil);
$stmt->fetch();
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $telefono = $_POST['telefono'];
    $direccion = $_POST['direccion'];
    $nuevaFoto = false;

    // Procesa la subida de la nueva foto de perfil
    if (isset($_FILES['fotoPerfil']) && $_FILES['fotoPerfil']['error'] == 0) {
        $directorioSubida = 'uploads/';
        $nombreArchivo = $directorioSubida . basename($_FILES['fotoPerfil']['name']);
        if (move_uploaded_file($_FILES['fotoPerfil']['tmp_name'], $nombreArchivo)) {
            $nuevaFoto = $nombreArchivo; // Guarda la ruta de la nueva foto
        } else {
            echo "Error al subir la foto.";
        }
    }

    // Actualiza los campos en la base de datos
    if ($nuevaFoto) {
        $updateStmt = $conn->prepare('UPDATE Usuarios SET Telefono = ?, Direccion = ?, FotoPerfil = ? WHERE UsuarioID = ?');
        $updateStmt->bind_param('sssi', $telefono, $direccion, $nuevaFoto, $usuarioID);
    } else {
        $updateStmt = $conn->prepare('UPDATE Usuarios SET Telefono = ?, Direccion = ? WHERE UsuarioID = ?');
        $updateStmt->bind_param('ssi', $telefono, $direccion, $usuarioID);
    }

    if ($updateStmt->execute()) {
        echo "Datos actualizados correctamente.";
        header("Refresh:0"); // Recarga la página para mostrar los cambios
    } else {
        echo "Error al actualizar los datos.";
    }
    $updateStmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Navbar fija en la parte superior -->
    <nav class="navbar">
        <div class="tabs">
            <a href="home.php">INICIO</a>
            <a href="chats.php">CHATS</a>
            <a href="saved.php">GUARDADOS</a>
            <a href="#mis-productos">MIS PRODUCTOS</a>
        </div>
        <!-- Foto de perfil circular con enlace -->
        <div class="profile">
            <a href="profile.php"><img src="<?php echo $fotoPerfil ? $fotoPerfil : 'https://via.placeholder.com/40'; ?>" alt="Foto de perfil"></a>
        </div>
    </nav>

    <!-- Contenido principal de la página de perfil -->
    <div class="profile-container">
        <h1>Mi Perfil</h1>
        <div class="profile-details">
            <img class="profile-picture-large" src="<?php echo $fotoPerfil ? $fotoPerfil : 'https://via.placeholder.com/150'; ?>" alt="Foto de perfil grande">
            <div class="profile-info">
                <h2><?php echo htmlspecialchars($nombreUsuario); ?></h2>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
                <p><strong>Teléfono:</strong> <?php echo htmlspecialchars($telefono ? $telefono : 'No especificado'); ?></p>
                <p><strong>Dirección:</strong> <?php echo htmlspecialchars($direccion ? $direccion : 'No especificada'); ?></p>
                <p><strong>Fecha de Registro:</strong> <?php echo htmlspecialchars($fechaRegistro); ?></p>
            </div>
        </div>

        <!-- Formulario para actualizar teléfono, dirección y foto de perfil -->
        <form method="post" action="" enctype="multipart/form-data">
            <div class="input-group">
                <label for="telefono">Teléfono</label>
                <input type="text" id="telefono" name="telefono" value="<?php echo htmlspecialchars($telefono); ?>" required>
            </div>
            <div class="input-group">
                <label for="direccion">Dirección</label>
                <input type="text" id="direccion" name="direccion" value="<?php echo htmlspecialchars($direccion); ?>" required>
            </div>
            <div class="input-group">
                <label for="fotoPerfil">Foto de Perfil</label>
                <input type="file" id="fotoPerfil" name="fotoPerfil" accept="image/*">
            </div>
            <button type="submit" class="button">Actualizar Datos</button>
        </form>
    </div>
</body>
</html>