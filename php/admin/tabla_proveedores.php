<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de proveedores</title>
    <link rel="stylesheet" href="../../css/tablas_admin.css">
    <link rel="apple-touch-icon" sizes="180x180" href="../../favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../../favicon_io/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../../favicon_io/favicon-16x16.png">
</head>
<body>
    <?php
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

    require_once('../crud/conexion.php');

    $sql = "SELECT 
                id_proveedor,
                nombre,
                cuit,
                telefono,
                correo,
                direccion,
                activo,
                fecha_alta
            FROM proveedores
            ORDER BY id_proveedor DESC";

    $resultado = mysqli_query($conn, $sql);

    $datos_proveedores = [];
    if ($resultado) {
        while ($row = mysqli_fetch_assoc($resultado)) {
            $datos_proveedores[] = $row;
        }
    }

    $columnas = [];
    if (count($datos_proveedores) > 0) {
        $columnas = array_keys($datos_proveedores[0]);
    }

    if (count($columnas) > 0) {
        $columnas[] = 'acciones';
    }
    ?>

    <main>
        <section id="lista_proveedores_sec">
            <br><br>
            <h2>Proveedores registrados</h2>

            <?php if (count($datos_proveedores) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <?php
                            foreach ($columnas as $nombre_columna) {
                                echo '<th>' . htmlspecialchars($nombre_columna) . '</th>';
                            }
                            ?>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        foreach ($datos_proveedores as $fila) {
                            echo '<tr>';

                            foreach ($columnas as $columna) {
                                if ($columna != 'acciones') {
                                    if ($columna == 'nombre') {
                                        echo '<td><a href="detalle_proveedor.php?id_proveedor=' . htmlspecialchars($fila['id_proveedor']) . '">' . htmlspecialchars($fila[$columna]) . '</a></td>';
                                    } elseif ($columna == 'activo') {
                                        echo '<td>' . ($fila[$columna] ? 'Sí' : 'No') . '</td>';
                                    } else {
                                        echo '<td>' . htmlspecialchars($fila[$columna] ?? '') . '</td>';
                                    }
                                } else {
                                    echo '<td class="acciones">
                                        <a href="editar_proveedor.php?id_proveedor=' . htmlspecialchars($fila['id_proveedor']) . '" class="edit_btn">
                                            <img src="../../recursos/edit_icon.png" alt="Editar">
                                        </a>

                                        <form method="POST" action="crud/eliminar_proveedor.php" class="form_eliminar">
                                            <input type="hidden" name="id_proveedor" value="' . htmlspecialchars($fila['id_proveedor']) . '">
                                            <button type="submit" class="delete_btn">
                                                <img src="../../recursos/delete_icon.png" alt="Eliminar">
                                            </button>
                                        </form>
                                    </td>';
                                }
                            }

                            echo '</tr>';
                        }
                        ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No hay proveedores registrados.</p>
            <?php endif; ?>
        </section>

        <section id="volver_s">
            <a href="proveedores_admin.php" class="btn-volver-admin">Volver a Gestión de proveedores</a>
        </section>
    </main>

    <?php
    if (isset($_SESSION['mensaje'])) {
        $mensaje = htmlspecialchars($_SESSION['mensaje']);
        echo "<script>alert('$mensaje');</script>";
        unset($_SESSION['mensaje']);
    }

    include('../footer.php');
    ?>
</body>

<script>
document.querySelectorAll('.form_eliminar').forEach(form => {
    form.addEventListener('submit', e => {
        if (!confirm(
            "Si el proveedor no tiene compras asociadas será eliminado definitivamente.\n\n" +
            "Si posee compras registradas, será marcado como inactivo para conservar el historial.\n\n" +
            "¿Desea continuar?"
        )) {
            e.preventDefault();
        }
    });
});
</script>
</html>