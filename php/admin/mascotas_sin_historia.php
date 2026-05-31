<?php
require_once('auth.php');
include('header_admin.php');
require_once('../crud/conexion.php');

$filtro = $_POST['filtro'] ?? '';
$valor = trim($_POST['valor'] ?? '');

$sql = "
    SELECT
        m.id_mascota,
        m.nombre AS mascota,
        m.raza,
        m.tamanio,
        m.color,
        m.edad,
        p.nombre AS nombre_duenio,
        p.apellido AS apellido_duenio
    FROM mascota m
    INNER JOIN persona p
        ON p.id_persona = m.id_persona
    LEFT JOIN historia_clinica h
        ON h.id_mascota = m.id_mascota
    WHERE h.id_historia IS NULL
";

$param = null;

if (!empty($filtro) && !empty($valor)) {
    $valorLower = '%' . strtolower($valor) . '%';

    if ($filtro === 'mascota') {
        $sql .= " AND LOWER(m.nombre) LIKE ? ";
        $param = $valorLower;
    } elseif ($filtro === 'duenio') {
        $sql .= " AND LOWER(CONCAT(p.nombre, ' ', p.apellido)) LIKE ? ";
        $param = $valorLower;
    } elseif ($filtro === 'raza') {
        $sql .= " AND LOWER(m.raza) LIKE ? ";
        $param = $valorLower;
    } elseif ($filtro === 'color') {
        $sql .= " AND LOWER(m.color) LIKE ? ";
        $param = $valorLower;
    }
}

$sql .= " ORDER BY m.nombre";

if ($param !== null) {
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $param);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $mascotas = $resultado->fetch_all(MYSQLI_ASSOC);
} else {
    $resultado = $conn->query($sql);
    $mascotas = $resultado->fetch_all(MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Registrar primera atención</title>

    <link rel="stylesheet" href="../../css/tablas_admin.css?v=<?= time() ?>">
    <link rel="stylesheet" href="../../css/buscar_persona.css?v=<?= time() ?>">
</head>

<body>

<main>

    <h1>Registrar primera atención</h1>

    <form action="" method="POST">

        <select name="filtro" id="filtro">
            <option value="" disabled <?= empty($filtro) ? 'selected' : '' ?>>
                Seleccione filtro
            </option>

            <option value="mascota" <?= $filtro === 'mascota' ? 'selected' : '' ?>>
                Mascota
            </option>

            <option value="duenio" <?= $filtro === 'duenio' ? 'selected' : '' ?>>
                Dueño
            </option>

            <option value="raza" <?= $filtro === 'raza' ? 'selected' : '' ?>>
                Raza
            </option>

            <option value="color" <?= $filtro === 'color' ? 'selected' : '' ?>>
                Color
            </option>
        </select>

        <input
            type="text"
            name="valor"
            id="valor"
            placeholder="Ingrese valor de búsqueda"
            value="<?= htmlspecialchars($valor) ?>"
        >

        <input
            type="submit"
            value="Buscar"
            id="botonBuscar"
        >

    </form>

    <?php if (!empty($mascotas)): ?>

        <section class="resultados-ancho">

            <h3>Mascotas sin historia clínica</h3>

            <table>
                <thead>
                    <tr>
                        <th>Mascota</th>
                        <th>Dueño</th>
                        <th>Raza</th>
                        <th>Tamaño</th>
                        <th>Color</th>
                        <th>Edad</th>
                        <th>Acción</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($mascotas as $mascota): ?>
                        <tr>
                            <td><?= htmlspecialchars($mascota['mascota']) ?></td>

                            <td>
                                <?= htmlspecialchars($mascota['nombre_duenio'] . ' ' . $mascota['apellido_duenio']) ?>
                            </td>

                            <td><?= htmlspecialchars($mascota['raza'] ?? 'Sin dato') ?></td>
                            <td><?= htmlspecialchars($mascota['tamanio'] ?? 'Sin dato') ?></td>
                            <td><?= htmlspecialchars($mascota['color'] ?? 'Sin dato') ?></td>
                            <td><?= htmlspecialchars($mascota['edad'] ?? 'Sin dato') ?></td>

                            <td>
                                <a href="registrar_atencion_clinica.php?id_mascota=<?= urlencode($mascota['id_mascota']) ?>"
                                   class="btn-volver-admin">
                                    Registrar atención
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        </section>

    <?php else: ?>

        <p>No hay mascotas pendientes de primera atención.</p>

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