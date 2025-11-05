<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Mascotas registradas</title>
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

    [$datos_mascotas, $columnas] = selectAllMascotas($conn);

    //Agrego un crud en las columnas
    if(count($columnas)>0) $columnas[] = 'acciones';

    ?>
    <main>
        <section id="lista_mascotas_sec">
            <h2>Mascotas registradas</h2>
            <table cellspacing="0" cellpadding="4">
                <thead>
                    <tr><?php
                     foreach($columnas as $nombre_columna){echo '<th>' . $nombre_columna . '</th>';}
                    ?></tr></thead>
                <tbody>
                    <?php
                    //Ahora creamos el cuerpo de la tabla
                    foreach($datos_mascotas as $fila){
                        echo '<tr>';
                        foreach ($columnas as $columna) {
                        if($columna != 'acciones'){
                            if($columna == 'password'){
                                echo "<td>••••••••</td>";
                            }else{
                                echo '<td>' . htmlspecialchars($fila[$columna]) . '</td>';
                            }
                        }
                        else{
                            echo '<td>
                                <button class="edit_btn" data-id="'.$fila['id_mascota'].'">
                                    <img src="../../recursos/edit_icon.png">
                                </button>
                                <form method="POST" action="eliminar_mascota.php" class="form_eliminar">
                                <input type="hidden" name="id_mascota" value="'.$fila['id_mascota'].'">
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
        <form method="post" action="pdfs/excel_mascotas.php">
            <button type="submit" name="exportar_excel" class="boton_excel">Exportar a Excel</button>
        </form>
    </section>
    <section id="volver_s">
        <a href="mascotas_admin.php">Volver a Administración de mascotas</a>
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