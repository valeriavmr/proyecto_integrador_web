<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle de usuario</title>
    <link rel="stylesheet" href="../../css/detalle_usuario.css">
</head>
<body>
    <?php
        if (session_status() == PHP_SESSION_NONE) { 
                session_start(); 
            }

        //Validacion de permisos
        require_once('auth.php');

        //Inserto el header
        include('header_admin.php');

        $id_persona = $_GET['id_persona'] ?? null;

        //Para traer la info de la persona
        require('../crud/conexion.php');
        include_once('../crud/consultas_varias.php');

        // Obtenemos los datos de la persona
        $persona = getPersonaPorId($conn, $id_persona);
        $datos_direccion = getDireccionPorId($conn, $id_persona);
        $datos_mascotas = obtenerMascotasPorUsuario($conn, $persona['nombre_de_usuario']);
        $columnas_mascotas = is_array($datos_mascotas) && count($datos_mascotas) > 0 ? array_keys($datos_mascotas[0]) : [];
        $datos_servicios = selectTurnosDePersona($conn, $id_persona);
         $columnas_servicios = is_array($datos_servicios) && count($datos_servicios) > 0 ? array_keys($datos_servicios[0]) : [];
    ?>
    <h1>Perfil de <?php echo htmlspecialchars($persona['nombre'])?></h1>
    <main>
        <section id="info_cuenta">
            <article>
            <h2>Datos de cuenta</h2>
            <p><strong>Nombre:</strong> <?php echo htmlspecialchars($persona['nombre']); ?></p>
            <p><strong>Apellido:</strong> <?php echo htmlspecialchars($persona['apellido']); ?></p>
            <p><strong>Usuario:</strong> <?php echo htmlspecialchars($persona['nombre_de_usuario']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($persona['correo']); ?></p>
            <p><strong>Teléfono:</strong> <?php echo htmlspecialchars($persona['telefono']); ?></p>
            <p><strong>Rol:</strong> <?php echo htmlspecialchars($persona['rol']); ?></p>
            <a href="editar_usuario.php?id_persona=<?php echo htmlspecialchars($id_persona)?>#form_editar_persona">Editar datos de cuenta</a>
            </article>
        </section>
        <section id="info_direccion">
            <article>
                <h2>Datos de dirección</h2>
            <?php if($datos_direccion):?>
                <p><strong>Provincia:</strong> <?php echo htmlspecialchars($datos_direccion['provincia']); ?></p>
                <p><strong>Barrio:</strong> <?php echo htmlspecialchars($datos_direccion['localidad']); ?></p>
                <p><strong>Calle:</strong> <?php echo htmlspecialchars($datos_direccion['calle']); ?></p>
                <p><strong>Altura:</strong> <?php echo htmlspecialchars($datos_direccion['altura']); ?></p>
                <br><br>
                <a href="editar_usuario.php?id_persona=<?php echo htmlspecialchars($id_persona)?>#form_editar_direccion">Editar datos de dirección</a>
            <?php else:?>
                <p>No hay datos de direccion</p>
            <?php endif;?>
            </article>
        </section>
        <?php if($datos_mascotas):?>
            <section id="info_mascotas">
            <br>
            <h2>Datos de mascotas</h2>
            <table>
                <thead>
                    <tr>
                    <?php
                    foreach($columnas_mascotas as $columna){
                        echo '<th>'. $columna . '</th>';
                    }
                    ?>
                    </tr>
                </thead>
                <tbody>
                     <?php
                        foreach($datos_mascotas as $fila){
                            echo '<a href="'. $fila['id_mascota'] .'"><tr>';
                            foreach($columnas_mascotas as $columna){
                                echo '<td>' . htmlspecialchars($fila[$columna]) .'</td>';
                            }
                            echo '</a></tr>';
                        }
                        ?>
                </tbody>
            </table>
        </section>
        <?php endif;?>
        <?php if($datos_servicios):?>
        <section id="info_turnos">
            <br>
            <h2>Datos de turnos</h2>
            <a href="tabla_historico_servicios.php?id_persona=<?php echo htmlspecialchars($id_persona)?>">
                <table>
                <thead>
                    <tr>
                    <?php
                    foreach($columnas_servicios as $columna){
                        echo '<th>'. $columna . '</th>';
                    }
                    ?>
                    </tr>
                </thead>
                <tbody>
                     <?php
                        foreach($datos_servicios as $fila){
                            echo '<tr>';
                            foreach($columnas_servicios as $columna){
                                echo '<td>' . htmlspecialchars($fila[$columna]) .'</td>';
                            }
                            echo '</tr>';
                        }
                        ?>
                </tbody>
            </table>
            </a>
        </section>
        <?php endif;?>
    </main>
    <section id="volver_s">
        <a href="personas_admin.php">Volver a Administración de personas</a>
    </section>
    <?php
    include('../footer.php');
    ?>
</body>
</html>