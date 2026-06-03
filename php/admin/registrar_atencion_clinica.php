<?php
require_once('auth.php');
require_once('../crud/conexion.php');

if (!isset($_GET['id_mascota']) || empty($_GET['id_mascota'])) {
    header('Location: tabla_historias_clinicas.php');
    exit();
}

$id_mascota = (int) $_GET['id_mascota'];

/* Buscar datos de la mascota y dueño */
$sql = "
    SELECT 
        m.id_mascota,
        m.nombre AS nombre_mascota,
        p.nombre AS nombre_duenio,
        p.apellido AS apellido_duenio
    FROM mascota m
    INNER JOIN persona p 
        ON p.id_persona = m.id_persona
    WHERE m.id_mascota = ?
";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Error al preparar la consulta de mascota: " . $conn->error);
}

$stmt->bind_param("i", $id_mascota);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header('Location: tabla_historias_clinicas.php');
    exit();
}

$mascota = $result->fetch_assoc();
$stmt->close();

/* Profesionales opcionales */
$profesionales = $conn->query("
    SELECT id_persona, nombre, apellido
    FROM persona
    WHERE rol = 'trabajador'
    ORDER BY apellido, nombre
");

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Registrar atención clínica</title>

    <link rel="stylesheet" href="../../css/solicitar_turno.css?v=<?= time() ?>">
    <link rel="stylesheet" href="../../css/tablas_admin.css">
    <link rel="stylesheet" href="../../css/styles.css">
</head>

<body>

<?php include('header_admin.php'); ?>

<main>

    <fieldset>

        <h2>Registrar atención clínica</h2>

        <p>
            <strong>Mascota:</strong>
            <?= htmlspecialchars($mascota['nombre_mascota']) ?>
        </p>

        <p>
            <strong>Dueño:</strong>
            <?= htmlspecialchars($mascota['nombre_duenio'] . ' ' . $mascota['apellido_duenio']) ?>
        </p>

        <form action="crud/insert_atencion_clinica.php" method="POST">

            <input type="hidden" name="id_mascota" value="<?= htmlspecialchars($id_mascota) ?>">

            <label for="fecha_atencion">Fecha de atención:</label>
            <input
                type="date"
                id="fecha_atencion"
                name="fecha_atencion"
                required
                value="<?= date('Y-m-d') ?>">
            <br><br>

            <label for="id_profesional">Profesional:</label>
            <select name="id_profesional" id="id_profesional">
                <option value="">Sin asignar</option>

                <?php while ($prof = $profesionales->fetch_assoc()): ?>
                    <option value="<?= htmlspecialchars($prof['id_persona']) ?>">
                        <?= htmlspecialchars($prof['apellido'] . ', ' . $prof['nombre']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
            <br><br>


            <label for="motivo_consulta">Motivo de consulta:</label>
            <input
                type="text"
                id="motivo_consulta"
                name="motivo_consulta"
                maxlength="255">
            <br><br>

            <label for="diagnostico">Diagnóstico:</label>
            <textarea
                id="diagnostico"
                name="diagnostico"
                rows="4"></textarea>
            <br><br>

            <label for="tratamiento">Tratamiento:</label>
            <textarea
                id="tratamiento"
                name="tratamiento"
                rows="4"></textarea>
            <br><br>

            <label for="observaciones">Observaciones:</label>
            <textarea
                id="observaciones"
                name="observaciones"
                rows="4"></textarea>
            <br><br>

            <input
                type="submit"
                value="Guardar atención clínica"
                id="guardar_atencion_btn">

        </form>

    </fieldset>

    <section id="back_section">
        <a href="detalle_historia_clinica.php?id_mascota=<?= urlencode($id_mascota) ?>"
           class="btn-volver-admin">
            Volver a la historia clínica
        </a>
    </section>

</main>

<?php include('../footer.php'); ?>

</body>
</html>