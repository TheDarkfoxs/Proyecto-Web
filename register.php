<?php
include 'includes/connection.php'; // Incluye la conexión a la base de datos
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombreUsuario = $_POST['nombreUsuario'];
    $correo = $_POST['email'];
    $password = $_POST['password'];
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT); // Encripta la contraseña

    // Verifica si el correo ya existe
    $stmt = $conn->prepare('SELECT UsuarioID FROM Usuarios WHERE Email = ?');
    $stmt->bind_param('s', $correo);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        echo "Este correo ya está registrado.";
    } else {
        // Inserta el nuevo usuario
        $stmt = $conn->prepare('INSERT INTO Usuarios (NombreUsuario, Email, Contraseña) VALUES (?, ?, ?)');
        $stmt->bind_param('sss', $nombreUsuario, $correo, $hashedPassword);
        if ($stmt->execute()) {
            echo "Registro exitoso. Ahora puedes iniciar sesión.";
            header('Location: login.php'); // Redirige a la página de inicio de sesión
            exit();
        } else {
            echo "Error al registrar el usuario.";
        }
    }
    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body class="login-page">
    <header class="header-title">
        <h1>UniVentas - Registro</h1>
    </header>
    <div class="container-login">
        <h2>Regístrate</h2>
        <form method="post" action="">
            <div class="input-group">
                <label for="nombreUsuario">Nombre de Usuario</label>
                <input type="text" id="nombreUsuario" name="nombreUsuario" required>
            </div>
            <div class="input-group">
                <label for="email">Correo Electrónico</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="input-group">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="button">Registrarse</button>
        </form>
        <p>¿Ya tienes una cuenta? <a href="login.php" class="link">Inicia sesión aquí</a></p>
    </div>
</body>
</html>
