<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle de usuario</title>
    <link rel="stylesheet" href="../../css/detalle_usuario.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="apple-touch-icon" sizes="180x180" href="../../favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../../favicon_io/favicon-32x32.png">
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
        $datos_trabajador = obtenerTrabajadorPorId($conn,$id_persona) ?? [];
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
        </section>
        <?php if($persona['rol'] == 'trabajador' || $persona['rol']=='admin'):?>
            <section id="info_trabajador">
                <article>
                    <h2>Datos de Trabajador</h2>
                <?php if($datos_trabajador):?>
                    <p><strong>Tipo de trabajador:</strong><?php echo htmlspecialchars($datos_trabajador['rol'])?></p>
                    <?php if($persona['rol']== 'admin'):?><p><strong>Pass App:</strong> <?php echo htmlspecialchars($datos_trabajador['pass_app']); ?></p><?php endif;?>
                    <?php if($persona['rol']== 'trabajador'):?><p><strong>Especialidad:</strong> <?php echo htmlspecialchars($datos_trabajador['tipo_de_servicio']); ?></p><?php endif;?>
                    <br><br>
                    <a href="editar_trabajadores.php?id_persona=<?php echo htmlspecialchars($id_persona)?>">Editar datos de trabajador</a>
                <?php else:?>
                    <p>No hay datos de trabajador</p>
                <?php endif;?>
                </article>
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