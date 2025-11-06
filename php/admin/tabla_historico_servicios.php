<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de todos los turnos de servicios</title>
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

    //Reviso si es redireccionado desde detalle_usuario.php
    if(isset($_GET['id_persona']) && !empty($_GET['id_persona'])){
        $id_persona = $_GET['id_persona'];
        $datos_personas = selectTurnosDePersona($conn, $id_persona);
        $columnas = is_array($datos_personas) && count($datos_personas) > 0 ? array_keys($datos_personas[0]) : [];
    }
    else{
        [$datos_personas, $columnas] = selectAllServicios($conn, false);
    }

    //Agrego un crud en las columnas
    if(count($columnas)>0) $columnas[] = 'acciones';
    ?>
    <main>
        <section id="lista_personas_sec">
            <br>
            <h2>Histórico de servicios prestados</h2>
            <table cellspacing="0" cellpadding="4">
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
                            echo '<td><a href="detalle_turno.php?id_servicio=' . $fila['id_servicio'] . '">' . htmlspecialchars($fila[$columna]) . '</a></td>';
                        }
                        else{
                            echo '<td>';
                            date_default_timezone_set('America/Argentina/Buenos_Aires');
                            $horarioTurno = new DateTime($fila['horario']);
                            $now = new DateTime();
                            if($horarioTurno >= $now)
                            {
                                echo '<a href="editar_turno.php?id_servicio='.$fila['id_servicio'].'" class="edit_btn" data-id="'.$fila['id_servicio'].'">
                                    <img src="../../recursos/edit_icon.png">
                                </a>';
                            }
                            echo '<form method="GET" action="../crud/eliminar_servicio.php?id_servicio='.$fila['id_servicio'].'" class="form_eliminar">
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
        <section>
        <form method="post" action="pdfs/excel_turnos.php">
            <button type="submit" name="exportar_excel" class="boton_excel">Exportar a Excel</button>
        </form>
    </section>
        <section id="volver_s">
            <a href="servicios_admin.php">Volver a Administración de servicios</a>
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