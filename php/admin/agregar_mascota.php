<?php
include '../crud/conexion.php'; 

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

        // Resolver dueño: puede venir como ID o como nombre/apellido
        $id_persona = null;

        if (is_numeric($duenio)) {
            $stmt_duenio = $conn->prepare("
                SELECT id_persona
                FROM persona
                WHERE id_persona = ?
                AND rol = 'cliente'
                AND activo = 1
                LIMIT 1
            ");
            $stmt_duenio->bind_param("i", $duenio);
        } else {
            $buscar = "%$duenio%";

            $stmt_duenio = $conn->prepare("
                SELECT id_persona
                FROM persona
                WHERE CONCAT(nombre, ' ', apellido) LIKE ?
                AND rol = 'cliente'
                AND activo = 1
                LIMIT 1
            ");
            $stmt_duenio->bind_param("s", $buscar);
        }

        $stmt_duenio->execute();
        $result_duenio = $stmt_duenio->get_result();

        if ($row_duenio = $result_duenio->fetch_assoc()) {
            $id_persona = (int)$row_duenio['id_persona'];
        } else {
            echo "<p class='mensaje-error'>No se encontró un cliente activo con ese ID o nombre.</p>";
            exit;
        }

        $stmt_duenio->close();

        $stmt = $conn->prepare("INSERT INTO mascota 
            (nombre, fecha_de_nacimiento, edad, raza, tamanio, color, id_persona)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");

        if (!$stmt) {
            die("Error en la preparación de la consulta: " . $conn->error);
        }

        $stmt->bind_param(
            "ssisssi",
            $nombre,
            $fecha_nac,
            $edad,
            $raza,
            $tamanio,
            $color,
            $id_persona
        );

        if ($stmt->execute()) {
            echo "<script>alert('Mascota registrada con éxito 🐾'); window.location='mascotas_admin.php';</script>";
        } else {
            echo "<p class='mensaje-error'>Error al registrar: " . $stmt->error . "</p>";
        }

        $stmt->close();
    } else {
        echo "<p class='mensaje-error'>Por favor completá todos los campos.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar nueva mascota</title>
    <link rel="stylesheet" href="../../css/tabla_style.css">
</head>

<body>
    <?php include('header_admin.php'); ?>
<main>
    <h1>Registrar nueva mascota</h1>

    <form method="POST" action="" class="form-admin">
        <div class="form-grupo">
            <label for="nombre_mascota">Nombre:</label>
            <input type="text" id="nombre_mascota" name="nombre_mascota" required>
        </div>

        <div class="form-grupo">
            <label for="fecha_de_nacimiento">Fecha de nacimiento:</label>
            <input type="date" id="fecha_de_nacimiento" name="fecha_de_nacimiento" required>
        </div>

        <div class="form-grupo">
            <label for="raza">Raza:</label>
            <input type="text" id="raza" name="raza" required>
        </div>

        <div class="form-grupo">
            <label for="tamanio">Tamaño:</label>
            <select id="tamanio" name="tamanio" required>
                <option value="">Seleccionar...</option>
                <option value="Pequeño">Pequeño</option>
                <option value="Mediano">Mediano</option>
                <option value="Grande">Grande</option>
            </select>
        </div>

        <div class="form-grupo">
            <label for="color">Color:</label>
            <input type="text" id="color" name="color" required>
        </div>

        <div class="form-grupo">
            <label for="duenio">Dueño (ID o nombre):</label>
            <input type="text" id="duenio" name="duenio" required>
        </div>

        <div class="acciones-form">
            <button type="submit" class="btn-primario">Guardar Mascota</button>
            <a href="mascotas_admin.php" class="btn-secundario">Volver</a>
        </div>
    </form>
</main>

<?php include('../footer.php'); ?>
</body>
</html>
