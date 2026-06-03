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

    'id_producto' => [
        'label' => 'ID del Producto',
        'tipo' => 'number'
    ],

    'nombre_producto' => [
        'label' => 'Nombre del Producto',
        'tipo' => 'text'
    ],

    'tipo_movimiento' => [
        'label' => 'Tipo de Movimiento',
        'tipo' => 'select'
    ],

    'fecha_movimiento' => [
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
                mi.id_movimiento_stock,
                p.id_producto,
                p.nombre_producto,
                mi.tipo_movimiento,
                mi.cantidad_producto,
                mi.fecha_movimiento

            FROM inventario_movimientos mi

            INNER JOIN inventario i
            ON mi.id_producto_stock = i.id_producto_stock

            INNER JOIN productos p
            ON i.id_producto = p.id_producto
            ";

        $tipo_param = "s";
        $param = $valor;

        /*
        |--------------------------------------------------------------------------
        | FILTROS
        |--------------------------------------------------------------------------
        */

        if ($campo == 'id_producto') {

            $sql .= " WHERE p.id_producto = ?";
            $tipo_param = "i";
            $param = (int)$valor;
        }

        elseif ($campo == 'nombre_producto') {

            $sql .= " WHERE LOWER(p.nombre_producto) LIKE ?";
            $param = "%" . strtolower($valor) . "%";
        }

        elseif ($campo == 'tipo_movimiento') {

            $sql .= " WHERE mi.tipo_movimiento = ?";
        }

        elseif ($campo == 'fecha_movimiento') {

            $sql .= " WHERE DATE(mi.fecha_movimiento) = ?";
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
    <title>Buscar Movimiento</title>

    <link rel="stylesheet" href="../../css/tablas_admin.css">
    <link rel="stylesheet" href="../../css/buscar_persona.css">
</head>
<body>

<main>

    <h1>Buscar Movimiento de Inventario</h1>

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
                        <th>ID Producto</th>
                        <th>Producto</th>
                        <th>Tipo</th>
                        <th>Cantidad</th>
                        <th>Fecha</th>

                    </tr>

                </thead>

                <tbody>

                    <?php foreach($resultados as $fila): ?>

                        <tr>

                            <td>

                                <a href="lista_trazabilidad_productos.php?id_movimiento=<?= htmlspecialchars($fila['id_movimiento_stock']) ?>">

                                    <?= htmlspecialchars($fila['id_movimiento_stock']) ?>

                                </a>

                            </td>

                            <td><?= htmlspecialchars($fila['id_producto']) ?></td>

                            <td><?= htmlspecialchars($fila['nombre_producto']) ?></td>

                            <td><?= htmlspecialchars($fila['tipo_movimiento']) ?></td>

                            <td><?= htmlspecialchars($fila['cantidad_producto']) ?></td>

                            <td><?= htmlspecialchars($fila['fecha_movimiento']) ?></td>

                        </tr>

                    <?php endforeach; ?>

                </tbody>

            </table>

        </section>

    <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>

        <p>No se encontraron resultados.</p>

    <?php endif; ?>

    <section id="volver_s">

        <a href="trazabilidad_productos.php" class="btn-volver-admin">

            Volver a Trazabilidad de Productos

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

    if (valor === 'nombre_producto') {

        html = `
            <input
                type="text"
                name="valor"
                id="valor"
                placeholder="Ingrese el nombre del producto"
                required
            >
        `;
    }

    else if (valor === 'id_producto') {

        html = `
            <input
                type="number"
                name="valor"
                id="valor"
                placeholder="Ingrese el ID del producto"
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

                <option value="Entrada">
                    Entrada
                </option>

                <option value="Salida">
                    Salida
                </option>

            </select>
        `;
    }

    else if (valor === 'fecha_movimiento') {

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