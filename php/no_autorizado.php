<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ingreso no autorizado</title>
    <link rel="apple-touch-icon" sizes="180x180" href="../../favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../../favicon_io/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../../favicon_io/favicon-16x16.png">
</head>
<body>
    <h2>Ingreso No Autorizado</h2>
    <?php
    if (session_status() == PHP_SESSION_NONE) { 
                session_start(); 
            }
            
    if(isset($_SESSION['rol']) && $_SESSION['rol'] == 'cliente'){

        echo '<button onclick="window.location.href=\'main_cliente.php\'">Volver a la página de inicio</button>';
    } else {
        echo '<button onclick="window.location.href=\'main_guest.php\'">Volver a la página de inicio</button>';
    }
    ?>
</body>
</html>