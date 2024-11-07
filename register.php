<?php
session_start();
include 'db_connection.php'; // Conexión a la base de datos

// Verificar si el formulario ha sido enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $profile_pic = "default.jpg"; // Puedes cambiar esto a la lógica que necesites para la imagen de perfil

    // Validar que los campos no estén vacíos
    if (!empty($username) && !empty($password)) {
        // Hashear la contraseña para mayor seguridad
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Insertar el usuario en la base de datos
        $stmt = $conn->prepare("INSERT INTO users (username, password, profile_pic) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $hashed_password, $profile_pic);

        if ($stmt->execute()) {
            echo "Registro exitoso. <a href='login.php'>Inicia sesión aquí</a>.";
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Por favor, llena todos los campos.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link rel="stylesheet" href="css/register.css">
</head>
<body>
    <div class="register-container">
        <h2>Registro</h2>
        <form method="POST" action="">
            <label for="username">Nombre de usuario</label>
            <input type="text" id="username" name="username" required>
            
            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password" required>
            
            <button type="submit">Registrarse</button>
        </form>
        <p>¿Ya tienes una cuenta? <a href="login.php">Inicia sesión</a></p>
    </div>
</body>
</html>
