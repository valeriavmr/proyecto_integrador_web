<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include_once __DIR__ . '\..\..\config.php';
require_once(BASE_PATH . '/php/admin/auth.php');

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

require(BASE_PATH . '/php/crud/conexion.php');

/*
|--------------------------------------------------------------------------
| FILTROS
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

    'tipo_insumo' => [
        'label' => 'Tipo de Insumo',
        'tipo' => 'text'
    ]
];

$resultados = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $campo = $_POST['filtro'] ?? '';
    $valor = $_POST['valor'] ?? '';

    if (array_key_exists($campo, $filtros)) {

        $sql = "
            SELECT
                id_insumo,
                nombre_insumo,
                descripcion_insumo,
                tipo_insumo,
                costo_unidad
            FROM insumo
        ";

        $tipo_param = "s";
        $param = $valor;

        /*
        |--------------------------------------------------------------------------
        | FILTROS
        |--------------------------------------------------------------------------
        */

        if ($campo == 'id_insumo') {

            $sql .= " WHERE id_insumo = ?";
            $tipo_param = "i";
            $param = (int)$valor;
        }

        elseif ($campo == 'nombre_insumo') {

            $sql .= " WHERE LOWER(nombre_insumo) LIKE ?";
            $param = "%" . strtolower($valor) . "%";
        }

        elseif ($campo == 'tipo_insumo') {

            $sql .= " WHERE LOWER(tipo_insumo) LIKE ?";
            $param = "%" . strtolower($valor) . "%";
        }

        /*
        |--------------------------------------------------------------------------
        | EJECUTAR
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
    <title>Buscar Insumos</title>

    <link rel="stylesheet" href="../../css/tablas_admin.css">
    <link rel="stylesheet" href="../../css/buscar_persona.css">
</head>
<body>

<main>

    <h1>Buscar Insumos</h1>

    <form action="" method="post">

        <!-- SELECT FILTROS -->

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

                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Tipo</th>
                        <th>Costo por Unidad</th>

                    </tr>

                </thead>

                <tbody>

                    <?php foreach($resultados as $fila): ?>

                        <tr>

                            <td>

                                <?= htmlspecialchars($fila['id_insumo']) ?>

                            </td>

                            <td>

                                <?= htmlspecialchars($fila['nombre_insumo']) ?>

                            </td>

                            <td>

                                <?= htmlspecialchars($fila['descripcion_insumo']) ?>

                            </td>

                            <td>

                                <?= htmlspecialchars($fila['tipo_insumo']) ?>

                            </td>

                            <td>

                                $<?= htmlspecialchars($fila['costo_unidad']) ?>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                </tbody>

            </table>

        </section>

    <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>

        <p>No se encontraron resultados.</p>

    <?php endif; ?>

    <section id="volver_s">

        <a href="gestion_insumos.php" class="btn-volver-admin">

            Volver a Gestión de Insumos

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

    if (
        valor === 'nombre_insumo'
    ) {

        html = `
            <input
                type="text"
                name="valor"
                id="valor"
                placeholder="Ingrese un valor"
                required
            >
        `;
    }

    else if (valor === 'tipo_insumo') {

    html = `
        <select name="valor" id="valor" required>

            <option value="" disabled selected>
                Seleccione un tipo
            </option>

            <option value="equipamiento">
                Equipamiento
            </option>

            <option value="medicinas">
                Medicinas
            </option>

            <option value="consumibles">
                Consumibles
            </option>

            <option value="cuidado_y_aseo">
                Cuidado y Aseo
            </option>

            <option value="otro">
                Otro
            </option>

        </select>
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

    contenedor.innerHTML = html;
});

</script>

<?php include_once(BASE_PATH . '/php/footer.php'); ?>

</body>
</html>