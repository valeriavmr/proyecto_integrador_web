<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de trabajadores registrados</title>
    <link rel="stylesheet" href="../../css/tablas_admin.css">
    <link rel="apple-touch-icon" sizes="180x180" href="../../favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../../favicon_io/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../../favicon_io/favicon-16x16.png">
</head>
<body>
    <?php
    require_once('auth.php');
    //Incluyo el header
    include('header_admin.php');

    //Recupero los datos de la tabla
    require_once('../crud/conexion.php');
    include_once('../crud/consultas_varias.php');
    require_once('../../config.php');

    // Si se envió el formulario de eliminación
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'eliminar_trabajador') {
        $id_persona = $_POST['id_persona'] ?? null;
        if ($id_persona) {
            if (deleteTrabajadorPorId($conn, $id_persona)) {
                echo "<script>alert('Trabajador eliminado correctamente'); window.location.href='tabla_trabajadores.php';</script>";
            } else {
                echo "<script>alert('Error al eliminar el trabajador');</script>";
            }
        }
    }

    [$datos_trabajadores, $columnas] = selectAllTrabajadores($conn);

    //Agrego un crud en las columnas
    if(count($columnas)>0) $columnas[] = 'acciones';

    ?>
    <main>
        <section id="lista_personas_sec">
            <h2>Usuarios registrados</h2>
            <table>
                <thead>
                    <tr><?php
                     foreach($columnas as $nombre_columna){echo '<th>' . $nombre_columna . '</th>';}
                    ?></tr></thead>
                <tbody>
                    <?php
                    //Ahora creamos el cuerpo de la tabla
                    foreach($datos_trabajadores as $fila){
                        echo '<tr>';
                        foreach ($columnas as $columna) {
                        if($columna != 'acciones'){
                            echo '<td><a href="detalle_usuario.php?id_persona='.$fila['id_persona'].'">' . htmlspecialchars($fila[$columna]) . '</a></td>';
                        }
                        else{
                            echo '<td class="acciones">
                                <a href="editar_trabajadores.php?id_persona='.$fila['id_persona'].'" class="edit_btn">
                                <img src="../../recursos/edit_icon.png"></a>
                                <form method="POST" action="">
                                    <input type="hidden" name="id_persona" value="'.$fila['id_persona'].'">
                                    <input type="hidden" name="accion" value="eliminar_trabajador">
                                    <button type="submit" class="delete_btn" onclick="return confirm(\'¿Seguro que quieres eliminar este trabajador?\');">
                                        <img src="../../recursos/delete_icon.png">
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
        </section>
    <section id="volver_s">
        <a href="trabajadores_admin.php">Volver a Administración de trabajadores</a>
    </section>
    </main>
    <?php
        //Muestro mensaje (para ediciones y eliminaciones) y luego lo quito
        if (isset($_SESSION['mensaje'])) {
            $mensaje = htmlspecialchars($_SESSION['mensaje']);
            echo "<script>alert('$mensaje');</script>";
            unset($_SESSION['mensaje']);
        }
    ?>
    <?php
    include('../footer.php');
    ?>
</body>
<script>
//Coloco los eventos a los botones de edición y borrado
document.querySelectorAll('.edit_btn').forEach(btn => {
    btn.addEventListener('click', () => {
        const id = btn.dataset.id;
        location.href = 'edit_usuario.php?id=' + id;
    });
});

document.querySelectorAll('.form_eliminar').forEach(form => {
    form.addEventListener('submit', e => {
        if (!confirm("¿Estás seguro de que quieres eliminar este usuario?")) {
            e.preventDefault();
        }
    });
});
</script>
</html>