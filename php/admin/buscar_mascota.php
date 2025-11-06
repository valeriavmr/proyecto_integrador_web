<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar Mascota</title>
    <link rel="stylesheet" href="../../css/tablas_admin.css">
    <link rel="stylesheet" href="../../css/buscar_persona.css">
</head>
<body>
<?php
    if (session_status() == PHP_SESSION_NONE) { 
        session_start(); 
    }

    // Validación de permisos
    require_once('auth.php');

    // Header admin
    include('header_admin.php');

    // Conexión y consultas
    require('../crud/conexion.php');
    include_once('../crud/consultas_varias.php');

    // Usuario actual (por si querés filtrar mascotas según quién busca)
    $usuario = $_SESSION['username'] ?? null;

    // Obtenemos todas las columnas de la tabla mascota (excepto campos internos)
    $queryCols = "SHOW COLUMNS FROM mascota_g3";
    $colsResult = $conn->query($queryCols);
    $columnNames = [];

    while ($col = $colsResult->fetch_assoc()) {
        if ($col['Field'] !== 'id_mascota') { // podés excluir más si querés
            $columnNames[] = $col['Field'];
        }
    }

    // Inicializo los resultados
    $resultados = [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['filtro']) && isset($_POST['valor'])) {
        $campo_mascota = $_POST['filtro'] ?? '';
        $valor_campo = $_POST['valor'] ?? '';

        if (in_array($campo_mascota, $columnNames)) {
            // Búsqueda insensible a mayúsculas
            $sql = "SELECT * FROM mascota_g3 WHERE LOWER($campo_mascota) LIKE ?";
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
    <h1>Buscar Mascota</h1>
    <form action="" method="post">
        <label for="filtro"></label>
        <select name="filtro" id="filtro" required>
            <option value="" disabled selected>Seleccione el filtro de búsqueda</option>
            <?php foreach($columnNames as $column): ?>
                <option value="<?= htmlspecialchars($column) ?>"><?= ucfirst($column) ?></option>
            <?php endforeach; ?>
        </select>

        <label for="valor"></label>
        <input type="text" name="valor" id="valor" placeholder="Ingrese el valor de búsqueda" required size="50">
        <input type="submit" value="Buscar" id="botonBuscar">
    </form>

    <?php if (!empty($resultados)): ?>
        <section>
            <h3>Resultados:</h3>
            <table>
                <thead>
                    <tr>
                        <?php foreach (array_keys($resultados[0]) as $col): ?>
                            <th><?= ucfirst($col) ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($resultados as $fila): ?>
                        <tr>
                            <?php foreach ($fila as $clave => $valor): ?>
                                <td style="max-width: 200px;">
                                    <a href="detalle_mascota.php?id_mascota=<?= htmlspecialchars($fila['id_mascota']) ?>">
                                        <?= htmlspecialchars($valor) ?>
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
        <a href="mascotas_admin.php">Volver a Administración de Mascotas</a>
    </section>
</main>

<?php include('../footer.php'); ?>
</body>
</html>
