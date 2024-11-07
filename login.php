<?php
session_start();
include 'db_connection.php'; // Conectar a la base de datos

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Modificar la consulta para obtener la imagen de perfil
    $stmt = $conn->prepare("SELECT id, username, password, profile_pic FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Verificar la contraseña
        if (password_verify($password, $user['password'])) {
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_id'] = $user['id'];

            // Verificar si hay imagen de perfil, si no usar la predeterminada
            $_SESSION['profile_pic'] = !empty($user['profile_pic']) ? $user['profile_pic'] : 'imagenes_perfil/default.png'; 

            header("Location: chat.php");
            exit;
        } else {
            $error = "Contraseña incorrecta.";
        }
    } else {
        $error = "Usuario no encontrado.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="login-container">
        <h1>Bienvenido al Chat</h1>
        <p>Para unirte a la conversación, por favor inicia sesión o regístrate.</p>

        <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
        
        <form method="POST" action="">
            <input type="text" name="username" placeholder="Nombre de usuario" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <button type="submit" name="login">Iniciar Sesión</button>
        </form>

        <p>¿No tienes una cuenta? <a href="register.php" class="register-button">Regístrate aquí</a></p>
    </div>
</body>
</html>
