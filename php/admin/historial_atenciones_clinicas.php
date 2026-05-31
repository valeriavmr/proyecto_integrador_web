<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once('auth.php');
include('header_admin.php');
require('../crud/conexion.php');

/* ==========================
   Búsqueda
========================== */

$filtro = $_POST['filtro'] ?? '';
$valor = trim($_POST['valor'] ?? '');

$atenciones = [];

$sql = "
    SELECT
        a.id_atencion,
        a.fecha_atencion,
        a.motivo_consulta,
        a.diagnostico,
        a.tratamiento,
        a.observaciones,
        m.id_mascota,
        m.nombre AS mascota,
        p.nombre AS nombre_duenio,
        p.apellido AS apellido_duenio,
        prof.nombre AS nombre_profesional,
        prof.apellido AS apellido_profesional,
        s.tipo_de_servicio
    FROM atencion_clinica a
    INNER JOIN historia_clinica h
        ON h.id_historia = a.id_historia
    INNER JOIN mascota m
        ON m.id_mascota = h.id_mascota
    INNER JOIN persona p
        ON p.id_persona = m.id_persona
    LEFT JOIN persona prof
        ON prof.id_persona = a.id_profesional
    LEFT JOIN servicio s
        ON s.id_servicio = a.id_servicio
";

$param = null;

if (!empty($filtro) && !empty($valor)) {
    $valorLower = '%' . strtolower($valor) . '%';

    if ($filtro === 'mascota') {
        $sql .= " WHERE LOWER(m.nombre) LIKE ? ";
        $param = $valorLower;
    } elseif ($filtro === 'duenio') {
        $sql .= " WHERE LOWER(CONCAT(p.nombre, ' ', p.apellido)) LIKE ? ";
        $param = $valorLower;
    } elseif ($filtro === 'profesional') {
        $sql .= " WHERE LOWER(CONCAT(prof.nombre, ' ', prof.apellido)) LIKE ? ";
        $param = $valorLower;
    } elseif ($filtro === 'servicio') {
        $sql .= " WHERE LOWER(s.tipo_de_servicio) LIKE ? ";
        $param = $valorLower;
    } elseif ($filtro === 'diagnostico') {
        $sql .= " WHERE LOWER(a.diagnostico) LIKE ? ";
        $param = $valorLower;
    } elseif ($filtro === 'fecha_atencion') {
        $sql .= " WHERE DATE(a.fecha_atencion) = ? ";
        $param = $valor;
    }
}

$sql .= "
    ORDER BY a.fecha_atencion DESC,
             a.id_atencion DESC
";

if ($param !== null) {
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $param);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $atenciones = $resultado->fetch_all(MYSQLI_ASSOC);
} else {
    $resultado = $conn->query($sql);
    $atenciones = $resultado->fetch_all(MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de Atenciones Clínicas</title>

    <link rel="stylesheet" href="../../css/tablas_admin.css?v=<?= time() ?>">
    <link rel="stylesheet" href="../../css/buscar_persona.css?v=<?= time() ?>">
</head>

<body>

    <main>

        <h1>Historial de Atenciones Clínicas</h1>

        <form action="" method="POST">

            <select name="filtro" id="filtro" required>
                <option value="" disabled <?= empty($filtro) ? 'selected' : '' ?>>
                    Seleccione filtro
                </option>

                <option value="mascota" <?= $filtro === 'mascota' ? 'selected' : '' ?>>
                    Mascota
                </option>

                <option value="duenio" <?= $filtro === 'duenio' ? 'selected' : '' ?>>
                    Dueño
                </option>

                <option value="profesional" <?= $filtro === 'profesional' ? 'selected' : '' ?>>
                    Profesional
                </option>

                <option value="servicio" <?= $filtro === 'servicio' ? 'selected' : '' ?>>
                    Servicio
                </option>

                <option value="diagnostico" <?= $filtro === 'diagnostico' ? 'selected' : '' ?>>
                    Diagnóstico
                </option>

                <option value="fecha_atencion" <?= $filtro === 'fecha_atencion' ? 'selected' : '' ?>>
                    Fecha atención
                </option>
            </select>

            <input
                type="text"
                name="valor"
                id="valor"
                placeholder="Ingrese valor de búsqueda"
                value="<?= htmlspecialchars($valor) ?>"
                required>

            <input
                type="submit"
                value="Buscar"
                id="botonBuscar">

        </form>

        <?php if (!empty($atenciones)): ?>

            <section class="resultados-ancho">

                <h3>Atenciones registradas</h3>

                <table>

                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Mascota</th>
                            <th>Dueño</th>
                            <th>Profesional</th>
                            <th>Servicio</th>
                            <th>Motivo</th>
                            <th>Diagnóstico</th>
                            <th>Tratamiento</th>
                            <th>Observaciones</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php foreach ($atenciones as $atencion): ?>

                            <tr>
                                <td><?= htmlspecialchars($atencion['fecha_atencion']) ?></td>

                                <td><?= htmlspecialchars($atencion['mascota']) ?></td>

                                <td>
                                    <?= htmlspecialchars($atencion['nombre_duenio'] . ' ' . $atencion['apellido_duenio']) ?>
                                </td>

                                <td>
                                    <?= !empty($atencion['nombre_profesional'])
                                        ? htmlspecialchars($atencion['nombre_profesional'] . ' ' . $atencion['apellido_profesional'])
                                        : 'Sin asignar' ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($atencion['tipo_de_servicio'] ?? 'Sin servicio asociado') ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($atencion['motivo_consulta'] ?? '-') ?>
                                </td>

                                <td>
                                    <?= nl2br(htmlspecialchars($atencion['diagnostico'] ?? '-')) ?>
                                </td>

                                <td>
                                    <?= nl2br(htmlspecialchars($atencion['tratamiento'] ?? '-')) ?>
                                </td>

                                <td>
                                    <?= nl2br(htmlspecialchars($atencion['observaciones'] ?? '-')) ?>
                                </td>

                                <td class="acciones">
                                    <a
                                        href="editar_atencion_clinica.php?id_atencion=<?= urlencode($atencion['id_atencion']) ?>"
                                        class="edit_btn">
                                        <img src="../../recursos/edit_icon.png" alt="Editar">
                                    </a>

                                    <form
                                        action="crud/eliminar_atencion_clinica.php"
                                        method="POST"
                                        class="form_eliminar"
                                        style="display:inline;">
                                        <input
                                            type="hidden"
                                            name="id_atencion"
                                            value="<?= htmlspecialchars($atencion['id_atencion']) ?>">

                                        <input
                                            type="hidden"
                                            name="id_mascota"
                                            value="<?= htmlspecialchars($atencion['id_mascota']) ?>">

                                        <input
                                            type="hidden"
                                            name="origen"
                                            value="historial">

                                        <button
                                            type="submit"
                                            class="delete_btn"
                                            onclick="return confirm('¿Eliminar esta atención clínica?');">
                                            <img src="../../recursos/delete_icon.png" alt="Eliminar">
                                        </button>
                                    </form>
                                </td>
                            </tr>

                        <?php endforeach; ?>

                    </tbody>

                </table>

            </section>

        <?php else: ?>

            <p>No se encontraron atenciones clínicas.</p>

        <?php endif; ?>

        <section id="volver_s">
            <a href="historia_clinica_admin.php" class="btn-volver-admin">
                Volver a Historia Clínica
            </a>
        </section>

    </main>

    <?php include('../footer.php'); ?>

</body>

</html>