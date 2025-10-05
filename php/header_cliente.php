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
</head>
<body>
    <header id="header_cliente">
        <section>
            <img src="/proyecto_adiestramiento_tahito/recursos/menu_img.png" id="nav_menu_icon" alt="">
        </section>
        <section id="nav_cuenta">
            <a id="link_logout" href="/proyecto_adiestramiento_tahito/php/logout.php" title="Cerrar sesión">
                <img src="/proyecto_adiestramiento_tahito/recursos/logout_img.png" alt="Cerrar sesión"></a>
        </section>
        <nav>
            <ul id="nav_cliente">
                <li><img src="/proyecto_adiestramiento_tahito/recursos/logsinfondo.png" alt=""></li>
                <li><a href="#">
                    <?php       
                        require_once('crud/conexion.php');
                        if (session_status() == PHP_SESSION_NONE) {
                            session_start();
                        }
                        $usuario = $_SESSION['username'];
                        include_once('crud/consultas_varias.php');
                        echo obtenerNombreUsuario($conn, $usuario); ?>
                </a></li>
                <hr>
                <li><a href="/proyecto_adiestramiento_tahito/php/crud/perfil.php"><img src="/proyecto_adiestramiento_tahito/recursos/perfil_icon.png" alt="" class="iconos">Perfil</a></li>
                <li><a href="#"><img src="/proyecto_adiestramiento_tahito/recursos/mascotas_icon.png" alt="" class="iconos">Mis mascotas</a></li>
                <hr>
                <li><a href="/proyecto_adiestramiento_tahito/php/servicios_cliente.php"><img src="/proyecto_adiestramiento_tahito/recursos/servicio_icon.png" alt="" class="iconos">Servicios</a></li>
                <hr>
                <li><a href="/proyecto_adiestramiento_tahito/php/main_cliente.php"><img src="/proyecto_adiestramiento_tahito/recursos/home_icon.png" alt="" class="iconos">Home</a></li>
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