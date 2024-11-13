<?php
session_start();
session_destroy(); // Destruye todas las sesiones
header('Location: login.php'); // Redirige al inicio de sesión
exit();
?>
