<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de personas registradas</title>
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

    [$datos_personas, $columnas] = selectAllPersonas($conn);

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
                    foreach($datos_personas as $fila){
                        echo '<tr>';
                        foreach ($columnas as $columna) {
                        if($columna != 'acciones'){
                            if($columna == 'password'){
                                echo "<td><a href=\"#\">••••••••</a></td>";
                            }else{
                                echo '<td><a href="detalle_usuario.php?id_persona='.$fila['id_persona'].'">' . htmlspecialchars($fila[$columna]) . '</a></td>';
                            }
                        }
                        else{
                            echo '<td class="acciones">
                                <a href="editar_usuario.php?id_persona='.$fila['id_persona'].'" class="edit_btn">
                                <img src="../../recursos/edit_icon.png"></a>
                                <form method="POST" action="' . BASE_URL . '/php/admin/crud/eliminar_persona.php" class="form_eliminar">
                                <input type="hidden" name="id_persona" value="'.$fila['id_persona'].'">
                                <button type="submit" class="delete_btn"><img src="../../recursos/delete_icon.png"></button>
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
        <a href="personas_admin.php">Volver a Administración de personas</a>
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