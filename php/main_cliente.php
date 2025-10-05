<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adiestramiento Tahito</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="apple-touch-icon" sizes="180x180" href="../favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../favicon_io/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../favicon_io/favicon-16x16.png">
    <link rel="stylesheet" href="../css/main_cliente_style.css?v=<?= time() ?>">
    <link rel="stylesheet" href="../css/servicios_cliente.css?v=<?= time() ?>">
</head>
<body>
    <?php include('header_cliente.php'); ?>
    <main>
        <section id="servicios_contratados">
            <h2>Turnos pendientes</h2>
            <br>
            <?php include_once('crud/select_servicios_contratados.php'); ?>
        </section>
        <section id="mis_mascotas_main">
            <h2>Mis mascotas</h2>
            <?php 
            require('crud/conexion.php');
            if (session_status() == PHP_SESSION_NONE) { 
                session_start(); 
            }

            $usuario = $_SESSION['username'];
            
            include_once('crud/consultas_varias.php');
            $mascotas = obtenerMascotasPorUsuario($conn, $usuario);
            if (empty($mascotas)) {
                echo "<p>No tienes mascotas registradas.</p>";
            } else {
                echo "<div id='mascotas_main_container'>";
                foreach ($mascotas as $mascota) {
                    echo "<article class='mascotas_main'>
                    <h3>" . $mascota['nombre'] . "</h3>
                    <p>Color: " . $mascota['color'] . "</p>
                    <p>Raza: " . $mascota['raza'] . "</p>
                    <p>Edad: " . $mascota['edad'] . " años</p>
                    </article>";
                }
                echo "</div>";
            }
            ?>
            <button id='agregar_mascota_btn' class='btn'>Agregar mascota</button>
        </section>
    </main>
    <?php include('footer.php'); ?>
<<<<<<< HEAD
    <link rel="stylesheet" href="../css/footer_styles.css?v=<?= time() ?>">
=======
>>>>>>> 83af6d2b3b41e3066e08b2b90fb992b5ed7a0a45
</body>
</html>