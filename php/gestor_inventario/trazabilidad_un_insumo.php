<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Validación de permisos
include_once __DIR__ . '\..\..\config.php';
require_once(BASE_PATH . '/php/admin/auth.php');

// Header
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

// Conexión
require(BASE_PATH . '/php/crud/conexion.php');

/*
|--------------------------------------------------------------------------
| FILTROS DISPONIBLES
|--------------------------------------------------------------------------
*/

$filtros = [

    'id_insumo' => [
        'label' => 'ID del Insumo',
        'tipo' => 'number'
    ],

    'nombre_insumo' => [
        'label' => 'Nombre del Insumo',
        'tipo' => 'text'
    ],

    'tipo_movimiento' => [
        'label' => 'Tipo de Movimiento',
        'tipo' => 'select'
    ],

    'fecha' => [
        'label' => 'Fecha del Movimiento',
        'tipo' => 'date'
    ]
];

$resultados = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $campo = $_POST['filtro'] ?? '';
    $valor = $_POST['valor'] ?? '';

    // Validar que el filtro exista
    if (array_key_exists($campo, $filtros)) {

        /*
        |--------------------------------------------------------------------------
        | QUERY BASE
        |--------------------------------------------------------------------------
        */

        $sql = "
            SELECT
                mi.id_movimiento,
                i.id_insumo,
                i.nombre_insumo,
                mi.tipo_movimiento,
                mi.cantidad,
                mi.fecha

            FROM movimientos_insumo mi

            INNER JOIN inventario_insumo ii
            ON mi.id_stock_insumo = ii.id_stock_insumo

            INNER JOIN insumo i
            ON ii.id_insumo = i.id_insumo
        ";

        $tipo_param = "s";
        $param = $valor;

        /*
        |--------------------------------------------------------------------------
        | FILTROS
        |--------------------------------------------------------------------------
        */

        if ($campo == 'id_insumo') {

            $sql .= " WHERE i.id_insumo = ?";
            $tipo_param = "i";
            $param = (int)$valor;
        }

        elseif ($campo == 'nombre_insumo') {

            $sql .= " WHERE LOWER(i.nombre_insumo) LIKE ?";
            $param = "%" . strtolower($valor) . "%";
        }

        elseif ($campo == 'tipo_movimiento') {

            $sql .= " WHERE mi.tipo_movimiento = ?";
        }

        elseif ($campo == 'fecha') {

            $sql .= " WHERE DATE(mi.fecha) = ?";
        }

        /*
        |--------------------------------------------------------------------------
        | EJECUTAR QUERY
        |--------------------------------------------------------------------------
        */

        $stmt = $conn->prepare($sql);

        if ($stmt) {

            $stmt->bind_param($tipo_param, $param);

            $stmt->execute();

            $result = $stmt->get_result();

            $resultados = $result->fetch_all(MYSQLI_ASSOC);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar Movimiento de Insumos</title>

    <link rel="stylesheet" href="../../css/tablas_admin.css">
    <link rel="stylesheet" href="../../css/buscar_persona.css">
</head>
<body>

<main>

    <h1>Buscar Movimiento de Insumos</h1>

    <form action="" method="post">

        <!-- SELECT DE FILTROS -->

        <select name="filtro" id="filtro" required>

            <option value="" disabled selected>
                Seleccione un filtro
            </option>

            <?php foreach($filtros as $campo => $datos): ?>

                <option value="<?= $campo ?>">

                    <?= $datos['label'] ?>

                </option>

            <?php endforeach; ?>

        </select>

        <!-- INPUT DINÁMICO -->

        <div id="contenedorInput">

            <input
                type="text"
                name="valor"
                id="valor"
                placeholder="Ingrese un valor"
                required
                size="50"
            >

        </div>

        <input type="submit" value="Buscar" id="botonBuscar">

    </form>

    <!-- RESULTADOS -->

    <?php if (!empty($resultados)): ?>

        <section>

            <h3>Resultados:</h3>

            <table>

                <thead>

                    <tr>

                        <th>ID Movimiento</th>
                        <th>ID Insumo</th>
                        <th>Insumo</th>
                        <th>Tipo</th>
                        <th>Cantidad</th>
                        <th>Fecha</th>

                    </tr>

                </thead>

                <tbody>

                    <?php foreach($resultados as $fila): ?>

                        <tr>

                            <td>

                                <a href="lista_trazabilidad_insumos.php?id_movimiento=<?= htmlspecialchars($fila['id_movimiento']) ?>">

                                    <?= htmlspecialchars($fila['id_movimiento']) ?>

                                </a>

                            </td>

                            <td><?= htmlspecialchars($fila['id_insumo']) ?></td>

                            <td><?= htmlspecialchars($fila['nombre_insumo']) ?></td>

                            <td><?= htmlspecialchars($fila['tipo_movimiento']) ?></td>

                            <td><?= htmlspecialchars($fila['cantidad']) ?></td>

                            <td><?= htmlspecialchars($fila['fecha']) ?></td>

                        </tr>

                    <?php endforeach; ?>

                </tbody>

            </table>

        </section>

    <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>

        <p>No se encontraron resultados.</p>

    <?php endif; ?>

    <section id="volver_s">

        <a href="trazabilidad_insumos.php" class="btn-volver-admin">

            Volver a Trazabilidad de Insumos

        </a>

    </section>

</main>

<!-- SCRIPT INPUTS DINÁMICOS -->

<script>

const filtro = document.getElementById('filtro');
const contenedor = document.getElementById('contenedorInput');

filtro.addEventListener('change', function() {

    const valor = this.value;

    let html = '';

    /*
    |--------------------------------------------------------------------------
    | INPUT SEGÚN FILTRO
    |--------------------------------------------------------------------------
    */

    if (valor === 'nombre_insumo') {

        html = `
            <input
                type="text"
                name="valor"
                id="valor"
                placeholder="Ingrese el nombre del insumo"
                required
            >
        `;
    }

    else if (valor === 'id_insumo') {

        html = `
            <input
                type="number"
                name="valor"
                id="valor"
                placeholder="Ingrese el ID del insumo"
                required
            >
        `;
    }

    else if (valor === 'tipo_movimiento') {

        html = `
            <select name="valor" id="valor" required>

                <option value="" disabled selected>
                    Seleccione un tipo
                </option>

                <option value="entrada">
                    Entrada
                </option>

                <option value="salida">
                    Salida
                </option>

            </select>
        `;
    }

    else if (valor === 'fecha') {

        html = `
            <input
                type="date"
                name="valor"
                id="valor"
                required
            >
        `;
    }

    contenedor.innerHTML = html;
});

</script>

<?php include_once(BASE_PATH . '/php/footer.php'); ?>

</body>
</html>