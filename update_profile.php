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
$stmt = $conn->prepare("SELECT username, profile_pic, hobbies, school, about FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($username, $profile_pic, $hobbies, $school, $about);
$stmt->fetch();
$stmt->close();

// Procesar el formulario al guardar cambios
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_hobbies = $_POST['hobbies'];
    $new_school = $_POST['school'];
    $new_about = $_POST['about'];

    // Procesar carga de la nueva foto de perfil
    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['profile_pic']['tmp_name'];
        $file_name = basename($_FILES['profile_pic']['name']);
        $upload_dir = 'imagenes_perfil/'; // Directorio donde se guardan las imágenes
        $upload_file = $upload_dir . $file_name;

        // Mover el archivo a la carpeta de imágenes
        if (move_uploaded_file($file_tmp, $upload_file)) {
            // Actualizar la base de datos con la nueva ruta de la imagen
            $stmt = $conn->prepare("UPDATE users SET hobbies = ?, school = ?, about = ?, profile_pic = ? WHERE id = ?");
            $stmt->bind_param("ssssi", $new_hobbies, $new_school, $new_about, $upload_file, $user_id);
            $stmt->execute();
            $stmt->close();
        } else {
            echo "<script>alert('Error al cargar la imagen.');</script>";
        }
    } else {
        // Si no se subió una nueva imagen, solo actualiza los otros campos
        $stmt = $conn->prepare("UPDATE users SET hobbies = ?, school = ?, about = ? WHERE id = ?");
        $stmt->bind_param("sssi", $new_hobbies, $new_school, $new_about, $user_id);
        $stmt->execute();
        $stmt->close();
    }

    // Confirmación de cambios guardados
    echo "<script>alert('Cambios guardados con éxito.'); window.location.href = 'profile.php';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil</title>
    <link rel="stylesheet" href="css/up.css">
</head>
<body>
    <header class="main-header">
        <a href="profile.php" class="back-button">Volver</a>
        <h1>Editar Perfil</h1>
    </header>
    <div class="profile-container">
        <form action="update_profile.php" method="post" enctype="multipart/form-data" class="profile-form">
            <div class="profile-info">
                <?php if (!empty($profile_pic)) : ?>
                    <img src="<?php echo $profile_pic; ?>" alt="Foto de perfil" class="profile-pic">
                <?php else : ?>
                    <img src="avatar.png" alt="Foto de perfil" class="profile-pic">
                <?php endif; ?>
            </div>

            <div class="additional-info">
                <h2><?php echo htmlspecialchars($username); ?></h2>
                <label for="hobbies">Pasatiempos:</label>
                <input type="text" id="hobbies" name="hobbies" value="<?php echo htmlspecialchars($hobbies); ?>">

                <label for="school">Escuela:</label>
                <input type="text" id="school" name="school" value="<?php echo htmlspecialchars($school); ?>">

                <label for="about">Sobre mí:</label>
                <textarea id="about" name="about"><?php echo htmlspecialchars($about); ?></textarea>

                <label for="profile_pic">Cambiar Foto de Perfil:</label>
                <input type="file" id="profile_pic" name="profile_pic" accept="image/*">
            </div>
            <div class="edit-button-container">
                <button type="submit" class="save-button">Guardar Cambios</button>
            </div>
        </form>
    </div>
</body>
</html>
