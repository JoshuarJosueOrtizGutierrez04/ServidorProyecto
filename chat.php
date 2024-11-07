<?php
session_start();
include 'db_connection.php'; // Asegúrate de incluir la conexión a la base de datos

// Verifica si el usuario está autenticado
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Obtener datos del usuario
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT profile_pic FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($profile_pic);
$stmt->fetch();
$stmt->close();

// Si profile_pic está vacío o nulo, asignar la imagen predeterminada
if (empty($profile_pic)) {
    $profile_pic = 'imagenes_perfil/default.png'; // Asegúrate de que la ruta sea correcta
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat en Vivo</title>
    <link rel="stylesheet" href="css/chat.css">
</head>
<body>
<header class="main-header">
    <h1>Bienvenido al Chat en Vivo, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
    <a href="profile.php" class="profile-icon">
        <img src="<?php echo htmlspecialchars($profile_pic); ?>" alt="Imagen de perfil" class="profile-img">
    </a>
</header>

<div class="main-container">
    <aside class="groups-section">
        <h2>Grupos</h2>
        <ul>
            <?php
            // Obtener grupos desde la base de datos
            $result = $conn->query("SELECT id, group_name FROM groups");
            while ($row = $result->fetch_assoc()) {
                echo "<li><a href='group_chat.php?group=" . $row['id'] . "'>" . htmlspecialchars($row['group_name']) . "</a></li>";
            }
            ?>
        </ul>
    </aside>
    <section class="chat-section">
        <div class="chat-messages" id="chat-messages">
            <!-- Los mensajes se cargarán aquí mediante JavaScript -->
        </div>
        <form id="chat-form">
            <input type="text" id="message-input" placeholder="Escribe tu mensaje..." required>
            <button type="submit">Enviar</button>
        </form>
    </section>
</div>
<script src="chat.js"></script>
</body>
</html>
