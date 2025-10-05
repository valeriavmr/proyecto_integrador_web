<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/header_cliente.css?v=<?= time() ?>">
</head>
<body>
    <header id="header_cliente">
        <section>
            <img src="../recursos/menu_img.png" id="nav_menu_icon" alt="">
        </section>
        <section id="nav_cuenta">
            <a id="link_logout" href="main_guest.php" title="Cerrar sesión">
                <img src="../recursos/logout_img.png" alt="Cerrar sesión"></a>
        </section>
        <nav>
            <ul id="nav_cliente">
                <li><img src="../recursos/logsinfondo.png" alt=""></li>
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
                <li><a href="crud/perfil.php"><img src="../recursos/perfil_icon.png" alt="" class="iconos">Perfil</a></li>
                <li><a href="crud/mascotas.php"><img src="../recursos/mascotas_icon.png" alt="" class="iconos">Mis mascotas</a></li>
                <hr>
                <li><a href="./servicios_cliente.php"><img src="../recursos/servicio_icon.png" alt="" class="iconos">Servicios</a></li>
                <hr>
                <li><a href="./main_cliente.php"><img src="../recursos/home_icon.png" alt="" class="iconos">Home</a></li>
                <li><a href="main_guest.php" title="Cerrar sesión" id="link_logout_menu">
                    <img src="../recursos/logout_img.png" alt="Cerrar sesión"></a></li>
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