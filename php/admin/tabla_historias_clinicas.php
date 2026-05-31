<?php
require_once('auth.php');
include('header_admin.php');
require_once('../crud/conexion.php');
include_once('../crud/consultas_varias.php');

$historias = obtenerHistoriasClinicas($conn);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Historias clínicas</title>
    <link rel="stylesheet" href="../../css/tablas_admin.css">
</head>

<body>

<main>
    <h2>Listado de historias clínicas</h2>

    <table>
        <thead>
            <tr>
                <th>Mascota</th>
                <th>Dueño</th>
                <th>Raza</th>
                <th>Estado</th>
                <th>Fecha apertura</th>
                <th>Acción</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($historias as $fila): ?>
                <tr>
                    <td><?= htmlspecialchars($fila['mascota']) ?></td>

                    <td>
                        <?= htmlspecialchars($fila['nombre_duenio'] . ' ' . $fila['apellido_duenio']) ?>
                    </td>

                    <td><?= htmlspecialchars($fila['raza'] ?? 'Sin dato') ?></td>

                    <td>
                        <?= empty($fila['id_historia']) ? 'Sin historia' : 'Con historia' ?>
                    </td>

                    <td>
                        <?= empty($fila['fecha_apertura']) ? '-' : htmlspecialchars($fila['fecha_apertura']) ?>
                    </td>

                    <td>
                        <a href="detalle_historia_clinica.php?id_mascota=<?= urlencode($fila['id_mascota']) ?>">
                            Ver historia
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</main>

<?php include('../footer.php'); ?>

</body>
</html>