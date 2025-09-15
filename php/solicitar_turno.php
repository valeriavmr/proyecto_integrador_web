<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitar Turno</title>
    <link rel="stylesheet" href="../css/servicios_cliente.css">
     <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="apple-touch-icon" sizes="180x180" href="../favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../favicon_io/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../favicon_io/favicon-16x16.png">    
</head>
<body>
    <?php include('header_cliente.php'); ?>
    <main>
        <fieldset>
            <form action="crud/insert_servicio.php" method="post">
                <h2>Solicitar Turno</h2>
                <label for="mascota">Mascota:</label><br>
                <select name="mascota" id="mascota" required>
                    <option value="" disabled selected>Seleccione una mascota</option>
                <?php
                    require('crud/conexion.php');
                    if (session_status() == PHP_SESSION_NONE) {
                        session_start();
                    }
                    $usuario = $_SESSION['username'];
                    include_once('crud/consultas_varias.php');
                    $mascotas = obtenerMascotasPorUsuario($conn, $usuario);
                    if (empty($mascotas)) {
                        echo "<option value=''>No tienes mascotas registradas</option>";
                    }

                    foreach ($mascotas as $mascota) {
                        echo $mascota['id_mascota'];
                        echo "<option value='{$mascota["id_mascota"]}'>{$mascota['nombre']}</option>";
                    }
                    ?>
                </select>
                <br><br>
                <label for="tipo_servicio">Tipo de Servicio:</label><br>
                <select name="tipo_servicio" id="tipo_servicio" required>
                    <option value="" disabled selected>Seleccione un servicio</option>
                    <option value="Adiestramiento canino">Adiestramiento canino</option>
                    <option value="Paseo canino">Paseo canino</option>
                    <option value="Banio y peluqueria">Baño y peluquería</option>
                </select>
                <br><br>
                <label for="trabajador">Trabajador asignado:</label><br>
                <select name="trabajador" id="trabajador" required>
                    <option value="" disabled selected>Selecciona un trabajador</option>
                    <?php
                    require('crud/conexion.php');
                    include_once('crud/consultas_varias.php');
                    $trabajadores = obtenerTrabajadores($conn);
                    if (empty($trabajadores)) {
                        echo "<option value=''>No hay trabajadores disponibles</option>";
                    }
                    foreach ($trabajadores as $trabajador) {
                        echo "<option value='{$trabajador["id_trabajador"]}'>{$trabajador['nombre_completo']}</option>";
                    }
                    ?>
                </select>
                <br><br>
                <label for="fecha">Fecha:</label><br>
                <input type="date" id="fecha" name="fecha" required>
                <br><br>
                <label for="hora">Hora:</label><br>
                <input type="time" id="hora" name="hora" required>
                <br><br>
                <label for="detalles">Observaciones a tener en cuenta:</label><br>
                <textarea id="detalles" name="detalles" rows="4" cols="50"></textarea>
                <br><br>
                <input type="submit" value="Solicitar Turno">
            </form>
        </fieldset>
    </main>
    <?php include('footer.php'); ?>
</body>
<link rel="stylesheet" href="../css/footer_styles.css?v=<?= time() ?>">
</html>