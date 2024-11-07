<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['group'])) {
    die("Grupo no especificado.");
}

$group_id = intval($_GET['group']);

// Verificar si el grupo existe
$stmt = $conn->prepare("SELECT group_name FROM groups WHERE id = ?");
$stmt->bind_param("i", $group_id);
$stmt->execute();
$stmt->bind_result($group_name);
$stmt->fetch();
$stmt->close();

if (!$group_name) {
    die("Grupo no encontrado.");
}

$user_id = $_SESSION['user_id'];

// Verificar si el usuario ya está en un grupo
$stmt = $conn->prepare("SELECT group_id FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($user_group_id);
$stmt->fetch();
$stmt->close();

// Si el usuario ya está en un grupo diferente, mostrar alerta y redirigir
if ($user_group_id && $user_group_id !== $group_id) {
    echo "<script>alert('Ya estás en un grupo. Sal primero antes de unirte a otro.'); window.location.href='chat.php';</script>";
    exit; // Asegúrate de terminar la ejecución después de la redirección
}

// Procesar salir del grupo
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['leave_group'])) {
    $stmt = $conn->prepare("UPDATE users SET group_id = NULL WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();
    
    // Redirigir al chat principal después de salir del grupo
    header("Location: chat.php");
    exit;
}

// Procesar unirse al grupo
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['leave_group'])) {
    // Verifica que no esté en otro grupo antes de unirse
    if (!$user_group_id) {
        $stmt = $conn->prepare("UPDATE users SET group_id = ? WHERE id = ?");
        $stmt->bind_param("ii", $group_id, $user_id);
        $stmt->execute();
        $stmt->close();

        // Redirigir al chat del grupo después de unirse
        header("Location: group_chat.php?group=" . $group_id);
        exit;
    } else {
        // Mostrar alerta con JavaScript
        echo "<script>alert('Ya estás en un grupo. Sal primero antes de unirte a otro.');</script>";
    }
}


?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat del Grupo <?php echo htmlspecialchars($group_name); ?></title>
    <link rel="stylesheet" href="css/chat.css">
</head>
<body>
<header class="main-header">
    <a href="chat.php" class="back-button">Volver al Chat Principal</a>
    <h1>Chat del Grupo <?php echo htmlspecialchars($group_name); ?></h1>
</header>

<div class="main-container">
    <?php if (!$user_group_id): ?>
        <form method="post">
            <p>¿Quieres unirte al grupo <?php echo htmlspecialchars($group_name); ?>?</p>
            <button type="submit">Unirme al grupo</button>
        </form>
    <?php else: ?>
        <section class="chat-section">
            <div class="chat-messages" id="chat-messages">
                <!-- Los mensajes se cargarán aquí mediante JavaScript -->
            </div>
            <form id="chat-form">
                <input type="text" id="message-input" placeholder="Escribe tu mensaje..." required>
                <button type="submit">Enviar</button>
            </form>
        </section>
        <form method="post">
            <button type="submit" name="leave_group">Salir del grupo</button>
        </form>
    <?php endif; ?>
</div>

<script src="chat.js"></script>
</body>
</html>
