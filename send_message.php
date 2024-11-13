<?php
include 'includes/connection.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $remitente_id = $_SESSION['UsuarioID'];
    $chat_id = $_POST['chat_id'];
    $mensaje = $_POST['mensaje'];
    $tipo = 'texto'; // Tipo por defecto

    // Comprobación de si hay una imagen subida
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
        $tipo = 'imagen';
        $ruta_imagen = 'uploads/' . basename($_FILES['imagen']['name']);
        move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta_imagen);
        $mensaje = $ruta_imagen; // El contenido del mensaje es la ruta de la imagen
    }

    $stmt = $conn->prepare("INSERT INTO mensajes (ChatID, RemitenteID, Tipo, ContenidoMensaje) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiss", $chat_id, $remitente_id, $tipo, $mensaje);

    if ($stmt->execute()) {
        echo "Mensaje enviado con éxito";
    } else {
        echo "Error al enviar el mensaje";
    }
    $stmt->close();
}
$conn->close();
?>
