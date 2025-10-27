<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar trbajador</title>
    <link rel="stylesheet" href="../../css/editar_usuario_admin.css">
    <link rel="apple-touch-icon" sizes="180x180" href="../../favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../../favicon_io/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../../favicon_io/favicon-16x16.png">
</head>
<body>
    <?php
    //Validacion de permisos
    require_once('auth.php');

    //Inserto el header
    include('header_admin.php');

    $id_persona = $_GET['id_persona'] ?? null;
    if (!$id_persona) {
        echo "<p>ID de trabajador no proporcionado.</p>";
        exit();
    }else{
        //Conecto a la base de datos
        require_once('../crud/conexion.php');
        include_once('../crud/consultas_varias.php');
        $trabajador = obtenerTrabajadorPorId($conn, $id_persona);
        if (!$trabajador) {
            echo "<p>Trabajador no encontrado.</p>";
            exit();
        }
    }
    ?>
        <h1>Editar trabajador</h1>
        <main>
        <form action="crud/update_trabajador.php" id="form_cuenta" method="POST">
        <fieldset>
            <h2>Datos del trabajador</h2>
            <input type="hidden" name="id_persona" value="<?php echo htmlspecialchars($trabajador['id_persona'] ?? '');?>">
            <label for="rol">Rol:</label>
            <input type="text" name="rol" id="rol" required size="50"
            value="<?php echo htmlspecialchars($trabajador['rol'] ?? ''); ?>">
            <?php
            //Para el rol trabajador, muestro el campo especialidad
            if($trabajador['rol'] == 'trabajador'){
                ?>
                <label for="tipo_de_servicio">Especialidad:</label>
                <select name="tipo_de_servicio" id="tipo_de_servicio">
                    <option value="" disabled 
                    <?php if(!isset($trabajador['tipo_de_servicio'])) echo 'selected'; ?>>Seleccione una especialidad</option>
                    <?php $especialidades = obtenerTiposDeServicios($conn);
                    foreach($especialidades as $especialidad){
                        $selected = ($trabajador['tipo_de_servicio'] == $especialidad['tipo_de_servicio']) ? 'selected' : '';
                        echo '<option value="' . htmlspecialchars($especialidad['tipo_de_servicio']) . '" ' . $selected . '>'
                            . htmlspecialchars($especialidad['tipo_de_servicio']) . '</option>';
                    }
                    ?>
                </select>
                <?php
            }

            //Para los admin, se puede setear el pass_app
            if($trabajador['rol'] =='admin'){
                ?>
                <label for="pass_app">Pass App:</label>
                <input type="text" required name="pass_app" id="pass_app" value="<?php echo htmlspecialchars($trabajador['pass_app'] ?? $_POST['pass_app'] ?? null)?>">
                <label for="correo_host">Correo del hosting:</label>
                <input type="mail" required name="correo_host" id="correo_host" value="<?php echo htmlspecialchars($trabajador['correo_host'] ?? $_POST['correo_host'] ?? null)?>">
                <?php
            }
            ?>
            <input type="submit" id="btn_guardar_trabajador" value="Guardar Cambios"/>
        </fieldset>
        </form>
        <section id="volver_s">
        <a href="tabla_trabajadores.php">
        Volver a la lista de trabajadores</a>
        </section>
        </main>
        <?php include('../footer.php');?>
</body>
</html>