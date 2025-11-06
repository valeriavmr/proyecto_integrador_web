<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adiestramiento Tahito</title>
    <link rel="stylesheet" href="../css/servicios_cliente.css">
     <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="apple-touch-icon" sizes="180x180" href="../favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../favicon_io/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../favicon_io/favicon-16x16.png"> 
    <link rel="stylesheet" href="../css/footer_styles.css?v=<?= time() ?>">
<link rel="stylesheet" href="../css/servicios_cliente.css?v=<?= time() ?>">   
</head>
<body>
    <?php 
    if (session_status() == PHP_SESSION_NONE) { 
                session_start(); 
            }

    $usuario = $_SESSION['username'];

    if($usuario == null){
        header("Location: no_autorizado.php");
        exit;
            }
    include('header_cliente.php'); ?>
    <main id="servicios_main">
        <section id="servicios_contratados">
            <h2>Turnos pendientes</h2>
            <br>
                <?php include_once('crud/select_servicios_contratados.php'); ?>
        </section>
        <hr>
        <section>
            <h2>Historial de servicios</h2>
            <?php include_once('crud/select_servicios_pasados.php'); ?>
        </section>
        <hr>
        <section>
            <h2>Solicitar turno para tu mascota</h2>
            <button id="solicitar_turno_btn" 
            onclick="window.location.href='solicitar_turno.php'">Nuevo turno</button>
        </section>
    </main>
    <?php include('footer.php'); ?>
</body>
</html>