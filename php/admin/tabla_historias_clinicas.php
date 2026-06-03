<?php
require_once('auth.php');
include_once(__DIR__ . '/../includes/sidebar.php');
require_once('../crud/conexion.php');
include_once('../crud/consultas_varias.php');

$filtro = $_POST['filtro'] ?? '';
$valor = $_POST['valor'] ?? '';

$historias = obtenerHistoriasClinicas($conn, $filtro, $valor);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Historias clínicas</title>
    <link rel="stylesheet" href="../../css/tablas_admin.css?v=<?= time() ?>">
    <link rel="stylesheet" href="../../css/buscar_persona.css">
    
</head>

<body>

    <main>
        <br><br>
        <h2>Listado de historias clínicas</h2>
        <section style="margin:20px 0; text-align:center;">

            <form action="" method="POST">

                <select name="filtro" id="filtro" required>
                    <option value="" disabled selected>
                        Seleccione filtro
                    </option>

                    <option value="mascota">Mascota</option>
                    <option value="duenio">Dueño</option>
                    <option value="raza">Raza</option>
                    <option value="fecha_apertura">Fecha apertura</option>
                </select>

                <input
                    type="text"
                    name="valor"
                    id="valor"
                    placeholder="Ingrese el valor de búsqueda"
                    required
                    size="40">

                <input
                    type="submit"
                    value="Buscar"
                    id="botonBuscar">

            </form>

        </section>
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
    <section id="volver_s">
        <a href="historia_clinica_admin.php" class="btn-volver-admin">
            Volver a Historia Clínica
        </a>
    </section>

    <?php include('../footer.php'); ?>

</body>

</html>