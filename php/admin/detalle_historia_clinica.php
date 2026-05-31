<?php
require_once('auth.php');
require_once('../crud/conexion.php');

if (!isset($_GET['id_mascota']) || empty($_GET['id_mascota'])) {
    header('Location: tabla_historias_clinicas.php');
    exit();
}

$id_mascota = (int) $_GET['id_mascota'];

$sql_mascota = "
    SELECT 
        m.id_mascota,
        m.nombre AS nombre_mascota,
        m.fecha_de_nacimiento,
        m.edad,
        m.raza,
        m.tamanio,
        m.color,
        m.imagen_url,
        p.id_persona,
        p.nombre AS nombre_duenio,
        p.apellido AS apellido_duenio,
        p.telefono,
        p.correo
    FROM mascota m
    INNER JOIN persona p ON p.id_persona = m.id_persona
    WHERE m.id_mascota = ?
";

$stmt = $conn->prepare($sql_mascota);
$stmt->bind_param("i", $id_mascota);
$stmt->execute();
$result_mascota = $stmt->get_result();

if ($result_mascota->num_rows === 0) {
    header('Location: tabla_historias_clinicas.php');
    exit();
}

$mascota = $result_mascota->fetch_assoc();
$stmt->close();

$sql_historia = "
    SELECT id_historia, fecha_apertura, observaciones_generales
    FROM historia_clinica
    WHERE id_mascota = ?
";

$stmt = $conn->prepare($sql_historia);
$stmt->bind_param("i", $id_mascota);
$stmt->execute();
$result_historia = $stmt->get_result();



$historia = null;
$id_historia = null;

if ($result_historia->num_rows > 0) {
    $historia = $result_historia->fetch_assoc();
    $id_historia = (int) $historia['id_historia'];
}
$stmt->close();

$sql_atenciones = "
    SELECT
        a.id_atencion,
        a.fecha_atencion,
        a.motivo_consulta,
        a.diagnostico,
        a.tratamiento,
        a.observaciones,
        s.tipo_de_servicio,
        prof.nombre AS nombre_profesional,
        prof.apellido AS apellido_profesional
    FROM atencion_clinica a
    LEFT JOIN servicio s ON s.id_servicio = a.id_servicio
    LEFT JOIN persona prof ON prof.id_persona = a.id_profesional
    WHERE a.id_historia = ?
    ORDER BY a.fecha_atencion DESC,
         a.id_atencion DESC
";

$atenciones = null;

if ($id_historia !== null) {
    $stmt = $conn->prepare($sql_atenciones);
    $stmt->bind_param("i", $id_historia);
    $stmt->execute();
    $atenciones = $stmt->get_result();
    $stmt->close();
}

include('header_admin.php');
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Detalle historia clínica</title>
    <link rel="stylesheet" href="../../css/tablas_admin.css?v=<?= time() ?>">
    <link rel="stylesheet" href="../../css/menus_admin.css">
    <link rel="stylesheet" href="../../css/styles.css">
</head>

<body>

    <main>

        <h1>Historia clínica de <?= htmlspecialchars($mascota['nombre_mascota']) ?></h1>

        <section class="contenedor-tabla">

            <table>
                <tr>
                    <th>Mascota</th>
                    <td><?= htmlspecialchars($mascota['nombre_mascota']) ?></td>
                </tr>

                <tr>
                    <th>Dueño</th>
                    <td><?= htmlspecialchars($mascota['nombre_duenio'] . ' ' . $mascota['apellido_duenio']) ?></td>
                </tr>
            </table>

            <div class="resumen-mascota">

                <div class="dato-resumen">
                    <strong>Edad</strong>
                    <span><?= htmlspecialchars($mascota['edad'] ?? 'Sin dato') ?></span>
                </div>

                <div class="dato-resumen">
                    <strong>Raza</strong>
                    <span><?= htmlspecialchars($mascota['raza'] ?? 'Sin dato') ?></span>
                </div>

                <div class="dato-resumen">
                    <strong>Tamaño</strong>
                    <span><?= htmlspecialchars($mascota['tamanio'] ?? 'Sin dato') ?></span>
                </div>

                <div class="dato-resumen">
                    <strong>Color</strong>
                    <span><?= htmlspecialchars($mascota['color'] ?? 'Sin dato') ?></span>
                </div>

                <div class="dato-resumen">
                    <strong>Teléfono</strong>
                    <span><?= htmlspecialchars($mascota['telefono'] ?? 'Sin dato') ?></span>
                </div>

                <div class="dato-resumen">
                    <strong>Email</strong>
                    <span><?= htmlspecialchars($mascota['correo'] ?? 'Sin dato') ?></span>
                </div>

            </div>


            <br>
            <h2>Datos de la historia clínica</h2>

            <?php if ($historia): ?>

                <table>
                    <tr>
                        <th>N° Paciente</th>
                        <td><?= str_pad($historia['id_historia'], 5, '0', STR_PAD_LEFT) ?></td>
                    </tr>
                    <tr>
                        <th>Fecha de apertura</th>
                        <td><?= date('d/m/Y H:i', strtotime($historia['fecha_apertura'])) ?></td>
                    </tr>
                    <tr>
                        <th>Observaciones generales</th>
                        <td>
                            <?= !empty($historia['observaciones_generales'])
                                ? nl2br(htmlspecialchars($historia['observaciones_generales']))
                                : 'Sin observaciones generales' ?>
                        </td>
                    </tr>
                </table>

            <?php else: ?>

                <p style="text-align:center; margin:20px 0;">
                    Esta mascota todavía no tiene historia clínica registrada.
                </p>

            <?php endif; ?>

            <div style="
            margin: 20px 0;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 15px;
        ">
                <a href="registrar_atencion_clinica.php?id_mascota=<?= urlencode($id_mascota) ?>"
                    class="btn-volver-admin">
                    Registrar atención clínica
                </a>

                <a href="tabla_historias_clinicas.php"
                    class="btn-volver-admin">
                    Volver al listado
                </a>
            </div>

            <h3>Atenciones clínicas registradas</h3>

            <?php if ($atenciones && $atenciones->num_rows > 0): ?>

                <section class="evolucion-clinica">

                    <?php while ($atencion = $atenciones->fetch_assoc()): ?>

                        <article class="atencion-card">

                            <header class="atencion-header">
                                <h4>
                                    Atención del <?= date('d/m/Y', strtotime($atencion['fecha_atencion'])) ?>
                                </h4>

                                <span class="atencion-profesional">
                                    <?= !empty($atencion['nombre_profesional'])
                                        ? htmlspecialchars($atencion['nombre_profesional'] . ' ' . $atencion['apellido_profesional'])
                                        : 'Profesional sin asignar' ?>
                                </span>
                            </header>

                            <p class="servicio-clinico">
                                <strong>Servicio:</strong>
                                <?= htmlspecialchars($atencion['tipo_de_servicio'] ?? 'Sin servicio asociado') ?>
                            </p>

                            <div class="bloque-clinico">
                                <h5>Motivo de consulta</h5>
                                <p><?= nl2br(htmlspecialchars($atencion['motivo_consulta'] ?: '-')) ?></p>
                            </div>

                            <div class="bloque-clinico">
                                <h5>Diagnóstico</h5>
                                <p><?= nl2br(htmlspecialchars($atencion['diagnostico'] ?: '-')) ?></p>
                            </div>

                            <div class="bloque-clinico">
                                <h5>Tratamiento indicado</h5>
                                <p><?= nl2br(htmlspecialchars($atencion['tratamiento'] ?: '-')) ?></p>
                            </div>

                            <div class="bloque-clinico">
                                <h5>Observaciones</h5>
                                <p><?= nl2br(htmlspecialchars($atencion['observaciones'] ?: '-')) ?></p>
                            </div>

                            <div class="acciones-clinicas">
                                <a href="editar_atencion_clinica.php?id_atencion=<?= urlencode($atencion['id_atencion']) ?>"
                                    class="edit_btn">
                                    <img src="../../recursos/edit_icon.png" alt="Editar">
                                </a>

                                <form action="crud/eliminar_atencion_clinica.php"
                                    method="POST"
                                    class="form_eliminar"
                                    style="display:inline;">

                                    <input type="hidden" name="id_atencion" value="<?= htmlspecialchars($atencion['id_atencion']) ?>">
                                    <input type="hidden" name="id_mascota" value="<?= htmlspecialchars($id_mascota) ?>">

                                    <button type="submit"
                                        class="delete_btn"
                                        onclick="return confirm('¿Eliminar esta atención clínica?');">
                                        <img src="../../recursos/delete_icon.png" alt="Eliminar">
                                    </button>
                                </form>
                            </div>

                        </article>

                    <?php endwhile; ?>

                </section>

            <?php else: ?>

                <p style="text-align:center;">No hay atenciones clínicas registradas.</p>

            <?php endif; ?>

        </section>

    </main>

    <?php include('../footer.php'); ?>

</body>

</html>