<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de turnos activos</title>
    <link rel="stylesheet" href="../../css/tablas_admin.css">
</head>
<body>
 <?php
    require_once('auth.php');
    //Incluyo el header
    include('header_admin.php');

    //Recupero los datos de la tabla
    require_once('../crud/conexion.php');
    include_once('../crud/consultas_varias.php');

    [$datos_turnos, $columnas] = selectAllServicios($conn, true);

    //Agrego un crud en las columnas
    if(count($columnas)>0) $columnas[] = 'acciones';
    ?>
    <main>
        <section id="lista_personas_sec">
            <br>
            <h2>Turnos activos</h2>
            <table>
                <thead>
                    <tr><?php
                     foreach($columnas as $nombre_columna){echo '<th>' . $nombre_columna . '</th>';}
                    ?></tr></thead>
                <tbody>
                    <?php
                    //Ahora creamos el cuerpo de la tabla
                    foreach($datos_turnos as $fila){
                        echo '<tr>';
                        foreach ($columnas as $columna) {
                        if($columna != 'acciones'){
                            echo '<td>' . htmlspecialchars($fila[$columna]) . '</td>';
                        }
                        else{
                            echo '<td>
                                <button class="edit_btn" data-id="'.$fila['id_servicio'].'">
                                    <img src="../../recursos/edit_icon.png">
                                </button>
                                <form method="GET" action="../crud/eliminar_servicio.php?id_servicio='.$fila['id_servicio'].'" class="form_eliminar">
                                <input type="hidden" name="id_servicio" value="'.$fila['id_servicio'].'">
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
        if (!confirm("¿Estás seguro de que quieres eliminar este turno?")) {
            e.preventDefault();
        }
    });
});
</script>   
</body>
</html>