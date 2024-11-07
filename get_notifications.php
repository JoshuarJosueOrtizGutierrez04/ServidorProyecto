<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Usuario no autenticado']);
    exit;
}

$user_id = $_SESSION['user_id'];

// Obtener las notificaciones de grupos con mensajes no leídos
$stmt = $conn->prepare("SELECT group_id, COUNT(*) AS new_messages FROM notifications WHERE user_id = ? AND is_read = FALSE GROUP BY group_id");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$notifications = [];
while ($row = $result->fetch_assoc()) {
    $notifications[] = [
        'group_id' => $row['group_id'],
        'new_messages' => $row['new_messages']
    ];
}

header('Content-Type: application/json');
echo json_encode($notifications);
exit;
?>
