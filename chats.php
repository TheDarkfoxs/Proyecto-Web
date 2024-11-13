<?php
include 'includes/connection.php';
session_start();
// Aquí se agrega el código para verificar y cargar la imagen de perfil en la sesión
if (!isset($_SESSION['fotoPerfil'])) {
    $stmt = $conn->prepare('SELECT FotoPerfil FROM Usuarios WHERE UsuarioID = ?');
    $stmt->bind_param('i', $_SESSION['UsuarioID']);
    $stmt->execute();
    $stmt->bind_result($fotoPerfil);
    $stmt->fetch();
    $stmt->close();
    $_SESSION['fotoPerfil'] = $fotoPerfil ? $fotoPerfil : 'https://via.placeholder.com/40';
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chats</title>
    <link rel="stylesheet" href="styles.css">
    <script>
        // Función para cargar mensajes mediante AJAX
        function cargarMensajes(chatId) {
            const xhr = new XMLHttpRequest();
            xhr.open('GET', 'load_message.php?chat_id=' + chatId, true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    document.querySelector('.messages').innerHTML = xhr.responseText;
                }
            };
            xhr.send();
        }
        // Llamada inicial para cargar mensajes del chat actual (cambia el chat_id según sea necesario)
        window.onload = function () {
            cargarMensajes(1); // Puedes reemplazar '1' con el chat_id actual
        };
    </script>
</head>
<body>
    <nav class="navbar">
        <div class="tabs">
            <a href="home.php">INICIO</a>
            <a href="chats.php" class="active">CHATS</a>
            <a href="saved.php">GUARDADOS</a>
            <a href="#mis-productos">MIS PRODUCTOS</a>
        </div>
        <div class="profile">
            <a href="profile.php"><img src="<?php echo $_SESSION['fotoPerfil']; ?>" alt="Foto de perfil"></a>
        </div>
    </nav>

    <div class="container" style="display: flex; height: calc(100vh - 80px);">
        <div class="chat-list" style="width: 250px; border-right: 1px solid #ddd; padding: 10px; overflow-y: auto;">
            <h2>Conversaciones</h2>
            <!-- Lista de chats (puedes hacerla dinámica con PHP más adelante) -->
            <div class="chat-item" onclick="cargarMensajes(1)">
                <p class="chat-name">Usuario 1</p>
                <p class="chat-message">Último mensaje de Usuario 1...</p>
            </div>
            <!-- Repite más chat-item para simular más conversaciones -->
        </div>

        <div class="chat-area" style="flex: 1; padding: 10px;">
            <h2>Chat con Usuario 1</h2>
            <div class="messages" style="border: 1px solid #ddd; border-radius: 8px; padding: 10px; height: 100%; overflow-y: auto;">
                <!-- Los mensajes se cargarán aquí mediante AJAX -->
            </div>

            <div class="input-area" style="margin-top: 10px;">
                <form id="chatForm" method="post" action="enviar_mensaje.php" enctype="multipart/form-data">
                    <input type="hidden" name="chat_id" value="1"> <!-- ID del chat actual -->
                    <input type="text" name="mensaje" placeholder="Escribe un mensaje..." style="width: 70%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                    <input type="file" name="imagen" accept="image/*">
                    <button type="submit" style="padding: 10px; background-color: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer;">Enviar</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
