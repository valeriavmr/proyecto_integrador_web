<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar Proveedor</title>
    <link rel="stylesheet" href="../../css/tablas_admin.css?v=<?= time() ?>">
    <link rel="stylesheet" href="../../css/buscar_persona.css?v=<?= time() ?>">
</head>
<body>
<?php
    if (session_status() == PHP_SESSION_NONE) { 
        session_start(); 
    }

    include_once __DIR__ . '\..\..\config.php';
    require_once(BASE_PATH . '/php/admin/auth.php');
    $rol = $_SESSION['rol'];
    if ($rol == 'admin') {
        include_once(BASE_PATH . '/php/admin/header_admin.php');
    } elseif ($rol == 'gestor') {
        include_once(BASE_PATH . '/php/gestor_inventario/header_gi.php');
    } else {
        header('Location: ' . BASE_URL . '/php/login.php');
        exit();
    }

    require('../crud/conexion.php');

    $queryCols = "SHOW COLUMNS FROM proveedores";
    $colsResult = $conn->query($queryCols);
    $columnNames = [];

    while ($col = $colsResult->fetch_assoc()) {
        if ($col['Field'] !== 'id_proveedor') {
            $columnNames[] = $col['Field'];
        }
    }

    $resultados = [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['filtro']) && isset($_POST['valor'])) {
        $campo_proveedor = $_POST['filtro'] ?? '';
        $valor_campo = $_POST['valor'] ?? '';

        if (in_array($campo_proveedor, $columnNames)) {
            $sql = "SELECT * FROM proveedores WHERE LOWER($campo_proveedor) LIKE ?";
            $stmt = $conn->prepare($sql);
            $param = '%' . strtolower($valor_campo) . '%';
            $stmt->bind_param("s", $param);

            $stmt->execute();
            $result = $stmt->get_result();
            $resultados = $result->fetch_all(MYSQLI_ASSOC);
        }
    }
?>

<main>
    <h1>Buscar Proveedor</h1>

    <form action="" method="post">
        <label for="filtro"></label>
        <select name="filtro" id="filtro" required>
            <option value="" disabled selected>Seleccione el filtro de búsqueda</option>
            <?php foreach($columnNames as $column): ?>
                <option value="<?= htmlspecialchars($column) ?>">
                    <?= ucfirst(str_replace('_', ' ', $column)) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="valor"></label>
        <input type="text" name="valor" id="valor" placeholder="Ingrese el valor de búsqueda" required size="50">

        <input type="submit" value="Buscar" id="botonBuscar">
    </form>

    <?php if (!empty($resultados)): ?>
        <section class="resultados-ancho">
            <h2>Resultados:</h2>
            <table>
                <thead>
                    <tr>
                        <?php foreach (array_keys($resultados[0]) as $col): ?>
                            <th><?= ucfirst(str_replace('_', ' ', $col)) ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($resultados as $fila): ?>
                        <tr>
                            <?php foreach ($fila as $clave => $valor): ?>
                                <td style="max-width: 200px;">
                                    <a href="detalle_proveedor.php?id_proveedor=<?= htmlspecialchars($fila['id_proveedor']) ?>">
                                        <?php
                                            if ($clave === 'activo') {
                                                echo $valor ? 'Sí' : 'No';
                                            } else {
                                                echo htmlspecialchars($valor ?? '');
                                            }
                                        ?>
                                    </a>
                                </td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['filtro'])): ?>
        <p>No se encontraron resultados.</p>
    <?php endif; ?>

    <section id="volver_s">
        <a href="proveedores_admin.php" class="btn-volver-admin">Volver a Gestión de Proveedores</a>
    </section>
</main>

<?php include('../footer.php'); ?>
</body>
</html>