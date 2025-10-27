<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/proyecto_adiestramiento_tahito/css/header_cliente.css?v=<?= time() ?>">
    <link rel="apple-touch-icon" sizes="180x180" href="../../favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../../favicon_io/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../../favicon_io/favicon-16x16.png">
</head>
<body>
    <?php
    require_once('auth.php');
    ?>
    <header id="header_cliente">
        <section>
            <img src="/proyecto_adiestramiento_tahito/recursos/menu_img.png" id="nav_menu_icon" alt="">
        </section>
        <section id="nav_cuenta">
            <a id="link_logout" href="/proyecto_adiestramiento_tahito/php/logout.php" title="Cerrar sesión">
                <img src="/proyecto_adiestramiento_tahito/recursos/logout_img.png" alt="Cerrar sesión"></a>
        </section>
        <nav>
            <h2>Perfil de Administrador</h2>
            <ul id="nav_cliente">
                <li><img src="/proyecto_adiestramiento_tahito/recursos/logsinfondo.png" alt=""></li>
                <li><a href="../crud/perfil.php">
                    <?php       
                        require_once('../crud/conexion.php');
                        if (session_status() == PHP_SESSION_NONE) {
                            session_start();
                        }
                        $usuario = $_SESSION['username'];
                        include_once('../crud/consultas_varias.php');
                        echo obtenerNombreUsuario($conn, $usuario); ?>
                </a></li>
                <hr>
                <li><a href="personas_admin.php"><img src="/proyecto_adiestramiento_tahito/recursos/personas_icon.png" alt="" class="iconos">Personas</a></li>
                <li><a href="#"><img src="/proyecto_adiestramiento_tahito/recursos/mascotas_icon.png" alt="" class="iconos">Mascotas</a></li>
                <hr>
                <li><a href="servicios_admin.php"><img src="/proyecto_adiestramiento_tahito/recursos/servicio_icon.png" alt="" class="iconos">Servicios</a></li>
                <li><a href="#"><img src="/proyecto_adiestramiento_tahito/recursos/trabajador_icon.png" alt="" class="iconos">Trabajadores</a></li>
                <hr>
                <li><a href="main_admin.php"><img src="/proyecto_adiestramiento_tahito/recursos/home_icon.png" alt="" class="iconos">Home</a></li>
                <li><a href="/proyecto_adiestramiento_tahito/php/logout.php" title="Cerrar sesión" id="link_logout_menu">
                    <img src="/proyecto_adiestramiento_tahito/recursos/logout_img.png" alt="Cerrar sesión"></a></li>
            </ul>
        </nav>
    </header>
</body>
<script>
    // Script para mostrar y ocultar el menú al hacer clic en el ícono
    let menu = document.getElementById("nav_cliente");
    let icono = document.getElementById("nav_menu_icon");

    console.log(menu);


    icono.addEventListener('click', (event) => {
            event.stopPropagation();
            menu.classList.toggle('menu_desplegado');
        });

    document.addEventListener('click', (event) => {
    if (!menu.contains(event.target) && event.target !== icono) {
        menu.classList.remove('menu_desplegado');
    }});

</script>
</html>