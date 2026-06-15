<?php

require_once('auth.php');
require_once('../crud/conexion.php');

if (!isset($_GET['id_atencion']) || empty($_GET['id_atencion'])) {
    header('Location: historia_clinica_admin.php');
    exit();
}

$id_atencion = (int) $_GET['id_atencion'];

$sql = "
    SELECT
        a.id_atencion,
        a.id_historia,
        a.id_servicio,
        a.id_profesional,
        a.fecha_atencion,
        a.motivo_consulta,
        a.diagnostico,
        a.tratamiento,
        a.observaciones,
        h.id_mascota,
        m.nombre AS nombre_mascota,
        p.nombre AS nombre_duenio,
        p.apellido AS apellido_duenio
    FROM atencion_clinica a
    INNER JOIN historia_clinica h ON h.id_historia = a.id_historia
    INNER JOIN mascota m ON m.id_mascota = h.id_mascota
    INNER JOIN persona p ON p.id_persona = m.id_persona
    WHERE a.id_atencion = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_atencion);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header('Location: historia_clinica_admin.php');
    exit();
}

$atencion = $result->fetch_assoc();
$stmt->close();

$id_mascota = (int) $atencion['id_mascota'];

$profesionales = $conn->query("
    SELECT id_persona, nombre, apellido
    FROM persona
    WHERE rol = 'trabajador'
      AND activo = 1
    ORDER BY apellido, nombre
");

$stmt = $conn->prepare("
    SELECT id_servicio, tipo_de_servicio, horario
    FROM servicio
    WHERE id_mascota = ?
    ORDER BY horario DESC
");
$stmt->bind_param("i", $id_mascota);
$stmt->execute();
$servicios = $stmt->get_result();
$stmt->close();

$fecha = '';
if (!empty($atencion['fecha_atencion'])) {
    $fecha = date('Y-m-d', strtotime($atencion['fecha_atencion']));
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Editar atención clínica</title>

    <link rel="stylesheet" href="../../css/solicitar_turno.css?v=<?= time() ?>">
    <link rel="stylesheet" href="../../css/tablas_admin.css">
    <link rel="stylesheet" href="../../css/styles.css">
</head>

<body>

<?php require_once dirname(__DIR__, 2) . '/config.php';
include_once(BASE_PATH . '/php/includes/sidebar.php'); ?>

<main>

    <fieldset>

        <h2>Editar atención clínica</h2>

        <p>
            <strong>Mascota:</strong>
            <?= htmlspecialchars($atencion['nombre_mascota']) ?>
        </p>

        <p>
            <strong>Dueño:</strong>
            <?= htmlspecialchars($atencion['nombre_duenio'] . ' ' . $atencion['apellido_duenio']) ?>
        </p>

        <form action="crud/update_atencion_clinica.php" method="POST">

            <input type="hidden" name="id_atencion" value="<?= htmlspecialchars($atencion['id_atencion']) ?>">
            <input type="hidden" name="id_mascota" value="<?= htmlspecialchars($id_mascota) ?>">

            <label for="fecha_atencion">Fecha de atención:</label>
            <input
                type="date"
                id="fecha_atencion"
                name="fecha_atencion"
                required
                value="<?= htmlspecialchars($fecha) ?>">
            <br><br>

            <label for="id_profesional">Profesional:</label>
            <select name="id_profesional" id="id_profesional">
                <option value="">Sin asignar</option>

                <?php while ($prof = $profesionales->fetch_assoc()): ?>
                    <option
                        value="<?= htmlspecialchars($prof['id_persona']) ?>"
                        <?= ((string)$atencion['id_profesional'] === (string)$prof['id_persona']) ? 'selected' : '' ?>>
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
                maxlength="255"
                value="<?= htmlspecialchars($atencion['motivo_consulta'] ?? '') ?>">
            <br><br>

            <label for="diagnostico">Diagnóstico:</label>
            <textarea
                id="diagnostico"
                name="diagnostico"
                rows="4"><?= htmlspecialchars($atencion['diagnostico'] ?? '') ?></textarea>
            <br><br>

            <label for="tratamiento">Tratamiento:</label>
            <textarea
                id="tratamiento"
                name="tratamiento"
                rows="4"><?= htmlspecialchars($atencion['tratamiento'] ?? '') ?></textarea>
            <br><br>

            <label for="observaciones">Observaciones:</label>
            <textarea
                id="observaciones"
                name="observaciones"
                rows="4"><?= htmlspecialchars($atencion['observaciones'] ?? '') ?></textarea>
            <br><br>

            <input
                type="submit"
                value="Actualizar atención clínica"
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