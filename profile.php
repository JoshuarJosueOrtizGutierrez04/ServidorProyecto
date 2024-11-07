<?php
session_start();
include 'db_connection.php';

// Verifica si el usuario está autenticado
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Obtener datos del usuario
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT username, profile_pic, hobbies, school, about, group_id FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($username, $profile_pic, $hobbies, $school, $about, $group_id);
$stmt->fetch();
$stmt->close();

// Obtener el nombre del grupo si el usuario está en uno
$group_name = '';
if ($group_id) {
    $stmt = $conn->prepare("SELECT group_name FROM groups WHERE id = ?");
    $stmt->bind_param("i", $group_id);
    $stmt->execute();
    $stmt->bind_result($group_name);
    $stmt->fetch();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de Usuario</title>
    <link rel="stylesheet" href="css/profile.css">
</head>
<body>
    <header class="main-header">
        <a href="chat.php" class="back-button">Volver al Chat</a>
        <h1>Perfil de <?php echo htmlspecialchars($username); ?></h1>
    </header>

    <div class="profile-container">
        <div class="profile-info">
            <?php if (!empty($profile_pic)) : ?>
                <img src="<?php echo htmlspecialchars($profile_pic); ?>" alt="Foto de perfil" class="profile-pic">
            <?php else : ?>
                <img src="default.png" alt="Foto de perfil" class="profile-pic">
            <?php endif; ?>
        </div>

        <div class="additional-info">
            <h2><?php echo htmlspecialchars($username); ?></h2>
            <p><strong>Pasatiempos:</strong> <?php echo htmlspecialchars($hobbies); ?></p>
            <p><strong>Escuela:</strong> <?php echo htmlspecialchars($school); ?></p>
            <p><strong>Sobre mí:</strong> <?php echo htmlspecialchars($about); ?></p>
            <p><strong>Grupo Actual:</strong> <?php echo $group_name ? htmlspecialchars($group_name) : 'No estás en ningún grupo'; ?></p>
        </div>

        <div class="edit-button-container">
            <a href="update_profile.php" class="edit-profile-btn">Editar Perfil</a>
            <a href="login.php" class="logout-btn">Cerrar Sesión</a>
        </div>
    </div>
</body>
</html>
