<?php
session_start();
include 'db_connection.php';

// Habilitar reporte de errores para depuración
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id']) || !isset($_SESSION['username'])) {
    echo json_encode(['error' => 'Usuario no autenticado']);
    exit;
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
$group_id = intval($_GET['group']); // Obtener el ID del grupo desde la URL

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = trim($_POST['message']);

    // Verificar si el mensaje no está vacío
    if (!empty($message)) {
        // Insertar el mensaje en la base de datos
        $stmt = $conn->prepare("INSERT INTO messages (user_id, username, message, group_id, created_at) VALUES (?, ?, ?, ?, NOW())");
        $stmt->bind_param("issi", $user_id, $username, $message, $group_id);
        
        if (!$stmt->execute()) {
            echo json_encode(['error' => 'Error al guardar el mensaje']);
            exit;
        }
        
        $stmt->close();
    } else {
        echo json_encode(['error' => 'Mensaje vacío']);
        exit;
    }
}

// Recuperar los mensajes del grupo específico ordenados por fecha ascendente
$stmt = $conn->prepare("SELECT username, message, created_at, user_id FROM messages WHERE group_id = ? ORDER BY created_at ASC");
$stmt->bind_param("i", $group_id);
$stmt->execute();
$result = $stmt->get_result();

$messages = [];
while ($row = $result->fetch_assoc()) {
    $messages[] = [
        'username' => $row['username'],
        'message' => $row['message'],
        'created_at' => $row['created_at'],
        'is_current_user' => $row['user_id'] == $user_id // Marcar si el mensaje es del usuario actual
    ];
}

$stmt->close();

// Enviar los mensajes en formato JSON para ser utilizados en el front-end
header('Content-Type: application/json');
echo json_encode($messages);
exit;



?>



