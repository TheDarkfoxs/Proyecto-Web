<?php
include 'includes/connection.php';
$chat_id = $_GET['chat_id'];

$query = "SELECT m.ContenidoMensaje, m.Tipo, m.FechaEnvio, u.NombreUsuario
          FROM mensajes m
          JOIN usuarios u ON m.RemitenteID = u.UsuarioID
          WHERE m.ChatID = ?
          ORDER BY m.FechaEnvio ASC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $chat_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    if ($row['Tipo'] == 'texto') {
        echo "<p><strong>{$row['NombreUsuario']}:</strong> {$row['ContenidoMensaje']} <small>{$row['FechaEnvio']}</small></p>";
    } elseif ($row['Tipo'] == 'imagen') {
        echo "<p><strong>{$row['NombreUsuario']}:</strong><br><img src='{$row['ContenidoMensaje']}' style='max-width: 100%; border-radius: 8px;'> <small>{$row['FechaEnvio']}</small></p>";
    }
}
$stmt->close();
$conn->close();
?>
