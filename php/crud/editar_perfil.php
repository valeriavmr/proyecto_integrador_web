<?php
session_start();
require 'conexion.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $nombre_de_usuario = $_POST['usuario'];
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];

    $sql = "UPDATE persona SET nombre=?, apellido=?, nombre_de_usuario=?, correo=?, telefono=? WHERE nombre_de_usuario=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $nombre, $apellido, $nombre_de_usuario, $correo, $telefono, $username);

    if ($stmt->execute()) {
        $_SESSION['username'] = $nombre_de_usuario;
        header("Location: perfil.php");
        exit();
    } else {
        echo "Error al actualizar.";
    }
}

$sql = "SELECT * FROM persona WHERE nombre_de_usuario=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$datos_usuario = $result->fetch_assoc();

if (!$datos_usuario || !is_array($datos_usuario)) {
    echo "No se pudo cargar la información del usuario.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil</title>    
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="apple-touch-icon" sizes="180x180" href="../favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../favicon_io/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../favicon_io/favicon-16x16.png">
    <link rel="stylesheet" href="../css/main_cliente_style.css?v=<?= time() ?>">
    <link rel="stylesheet" href="../css/servicios_cliente.css?v=<?= time() ?>">
    

    <!-- Fuentes -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="/2C2025/proyecto_integrador_web/favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/2C2025/proyecto_integrador_web/favicon_io/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/2C2025/proyecto_integrador_web/favicon_io/favicon-16x16.png">

    <!-- Estilos -->
    <link rel="stylesheet" href="/2C2025/proyecto_integrador_web/css/main_cliente_style.css?v=<?= time() ?>">
    <link rel="stylesheet" href="../css/footer_styles.css?v=<?= time() ?>">
    <link rel="stylesheet" href="/2C2025/proyecto_integrador_web/css/servicios_cliente.css?v=<?= time() ?>">
</head>
<body>

<?php include('../header_cliente.php'); ?>

<main>
    <section class="formulario-edicion">
        <h1>Editar Perfil</h1>
        <form method="POST">
            <label>Usuario:</label>
            <input type="text" name="usuario" value="<?php echo htmlspecialchars($datos_usuario['nombre_de_usuario']); ?>" required><br>

            <label>Nombre:</label>
            <input type="text" name="nombre" value="<?php echo htmlspecialchars($datos_usuario['nombre']); ?>" required><br>

            <label>Apellido:</label>
            <input type="text" name="apellido" value="<?php echo htmlspecialchars($datos_usuario['apellido']); ?>" required><br>

            <label>Correo:</label>
            <input type="email" name="correo" value="<?php echo htmlspecialchars($datos_usuario['correo']); ?>" required><br>

            <label>Teléfono:</label>
            <input type="text" name="telefono" value="<?php echo htmlspecialchars($datos_usuario['telefono']); ?>" required><br><br>

            <button type="submit">Guardar cambios</button>
        </form>
    </section>
</main>

<?php include '../footer.php'; ?>

<link rel="stylesheet" href="../../css/footer_styles.css?v=<?= time() ?>">

</body>
</html>