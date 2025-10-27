<?php
include '../crud/conexion.php'; 
include('header_admin.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nombre = trim($_POST['nombre_mascota']);
    $fecha_nac = $_POST['fecha_de_nacimiento'];
    $raza = trim($_POST['raza']);
    $tamanio = trim($_POST['tamanio']);
    $color = trim($_POST['color']);
    $duenio = trim($_POST['duenio']);

    // Calcular edad automáticamente
    $fecha_nacimiento = new DateTime($fecha_nac);
    $hoy = new DateTime();
    $edad = $hoy->diff($fecha_nacimiento)->y;

    if ($nombre && $fecha_nac && $raza && $tamanio && $color && $duenio) {

        $stmt = $conn->prepare("INSERT INTO mascota 
            (nombre, fecha_de_nacimiento, edad, raza, tamanio, color, id_persona)
            VALUES (?, ?, ?, ?, ?, ?, ?)");
            if (!$stmt) {
    die("Error en la preparación de la consulta: " . $conn->error);
}
        $stmt->bind_param("ssissss", $nombre, $fecha_nac, $edad, $raza, $tamanio, $color, $duenio);

        if ($stmt->execute()) {
            echo "<script>alert('Mascota registrada con éxito 🐾'); window.location='mascotas_admin.php';</script>";
        } else {
            echo "<p style='color:red;'>Error al registrar: " . $stmt->error . "</p>";
        }

        $stmt->close();
    } else {
        echo "<p style='color:red;'>Por favor completá todos los campos.</p>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Mascotas registradas</title>
    <link rel="stylesheet" href="../../css/tablas_admin.css">
    <link rel="apple-touch-icon" sizes="180x180" href="../../favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../../favicon_io/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../../favicon_io/favicon-16x16.png">
</head>

<body>

    <h2>Registrar nueva mascota</h2>

    <form method="POST" action="">
        <div class="form-group">
            <label>Nombre:</label>
            <input type="text" name="nombre_mascota" required>
        </div>

        <div class="form-group">
            <label>Fecha de nacimiento:</label>
            <input type="date" name="fecha_de_nacimiento" required>
        </div>

        <div class="form-group">
            <label>Raza:</label>
            <input type="text" name="raza" required>
        </div>

        <div class="form-group">
            <label>Tamaño:</label>
            <select name="tamanio" required>
                <option value="">Seleccionar...</option>
                <option value="Pequeño">Pequeño</option>
                <option value="Mediano">Mediano</option>
                <option value="Grande">Grande</option>
            </select>
        </div>

        <div class="form-group">
            <label>Color:</label>
            <input type="text" name="color" required>
        </div>

        <div class="form-group">
            <label>Dueño:</label>
            <input type="text" name="duenio" required>
        </div>

        <button type="submit" class="btn btn-primary">Guardar Mascota</button>
    </form>

    <?php
        include('../footer.php');
        ?>

</body>

