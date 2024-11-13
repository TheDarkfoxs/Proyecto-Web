<?php
include 'includes/connection.php'; // Incluye la conexión a la base de datos
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $correo = $_POST['email'];
    $password = $_POST['password'];

    // Verifica si el usuario existe usando el correo electrónico
    $stmt = $conn->prepare('SELECT UsuarioID, Contraseña FROM Usuarios WHERE Email = ?');
    $stmt->bind_param('s', $correo);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($usuarioID, $hashedPassword);
        $stmt->fetch();
        if (password_verify($password, $hashedPassword)) {
            $_SESSION['UsuarioID'] = $usuarioID;
            header('Location: home.php'); // Redirige al usuario
            exit();
        } else {
            echo "Contraseña incorrecta";
        }
    } else {
        echo "Correo electrónico no encontrado";
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
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body class="login-page">
    <header class="header-title">
        <h1>UniVentas</h1>
    </header>
    <div class="container-login">
        <h2>Inicio de Sesión</h2>
        <form method="post" action="">
            <div class="input-group">
                <label for="email">Correo Electrónico</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="input-group">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="button">Iniciar Sesión</button>
        </form>
        <p>¿No tienes una cuenta? <a href="register.php" class="link">Regístrate</a></p>
    </div>
</body>
</html>
