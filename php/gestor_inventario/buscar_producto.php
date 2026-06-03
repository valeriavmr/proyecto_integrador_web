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

    'id_producto' => [
        'label' => 'ID del Producto',
        'tipo' => 'number'
    ],

    'nombre_producto' => [
        'label' => 'Nombre del Producto',
        'tipo' => 'text'
    ],

    'tipo' => [
        'label' => 'Tipo de Producto',
        'tipo' => 'select'
    ]
];

$resultados = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $campo = $_POST['filtro'] ?? '';
    $valor = $_POST['valor'] ?? '';

    if (array_key_exists($campo, $filtros)) {

        $sql = "
            SELECT
                id_producto,
                nombre_producto,
                descripcion_producto,
                precio_unitario,
                tipo
            FROM productos
        ";

        $tipo_param = "s";
        $param = $valor;

        if ($campo == 'id_producto') {

            $sql .= " WHERE id_producto = ?";
            $tipo_param = "i";
            $param = (int)$valor;
        }

        elseif ($campo == 'nombre_producto') {

            $sql .= " WHERE LOWER(nombre_producto) LIKE ?";
            $param = "%" . strtolower($valor) . "%";
        }

        elseif ($campo == 'tipo') {

            $sql .= " WHERE tipo = ?";
        }

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
    <title>Buscar Productos</title>

    <link rel="stylesheet" href="../../css/tablas_admin.css">
    <link rel="stylesheet" href="../../css/buscar_persona.css">
</head>
<body>

<main>

<h1>Buscar Productos</h1>

<form action="" method="post">

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

    <div id="contenedorInput">

        <input
            type="text"
            name="valor"
            id="valor"
            required
            size="50"
        >

    </div>

    <input type="submit" value="Buscar" id="botonBuscar">

</form>

<?php if (!empty($resultados)): ?>

<section>

<table>

    <thead>

        <tr>

            <th>ID</th>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Precio</th>
            <th>Tipo</th>

        </tr>

    </thead>

    <tbody>

        <?php foreach($resultados as $fila): ?>

            <tr>

                <td><?= htmlspecialchars($fila['id_producto']) ?></td>

                <td><?= htmlspecialchars($fila['nombre_producto']) ?></td>

                <td><?= htmlspecialchars($fila['descripcion_producto']) ?></td>

                <td>$<?= htmlspecialchars($fila['precio_unitario']) ?></td>

                <td><?= htmlspecialchars($fila['tipo']) ?></td>

            </tr>

        <?php endforeach; ?>

    </tbody>

</table>

</section>

<?php endif; ?>

</main>

<script>

const filtro = document.getElementById('filtro');
const contenedor = document.getElementById('contenedorInput');

filtro.addEventListener('change', function() {

    const valor = this.value;

    let html = '';

    if (valor === 'nombre_producto') {

        html = `
            <input
                type="text"
                name="valor"
                required
            >
        `;
    }

    else if (valor === 'id_producto') {

        html = `
            <input
                type="number"
                name="valor"
                required
            >
        `;
    }

    else if (valor === 'tipo') {

        html = `
            <select name="valor" required>

                <option value="Otro">Otro</option>
                <option value="Vacuna">Vacuna</option>
                <option value="Medicamento">Medicamento</option>

            </select>
        `;
    }

    contenedor.innerHTML = html;
});

</script>

</body>
</html>