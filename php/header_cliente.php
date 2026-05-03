<!-- Shared Header for authenticated clients -->
<link rel="stylesheet" href="../css/theme.css?v=<?= time() ?>">
<link rel="stylesheet" href="../css/header_cliente.css?v=<?= time() ?>">

<header id="header_cliente">
    <div class="header-toggle">
        <img src="../recursos/menu_img.png" id="nav_menu_icon" alt="Menú">
    </div>
    <div class="header-logo">
        <img src="../recursos/logsinfondo.png" alt="Tahito">
    </div>
    <div id="nav_cuenta">
        <a id="link_logout" href="../php/logout.php" title="Cerrar sesión">
            <img src="../recursos/logout_img.png" alt="Cerrar sesión">
        </a>
    </div>
</header>

<nav id="nav_sidebar">
    <ul id="nav_cliente">
        <li class="nav-user-name">
            <?php       
                require_once('crud/conexion.php');
                if (session_status() == PHP_SESSION_NONE) { session_start(); }
                $usuario = $_SESSION['username'];
                if($usuario == null){ header("Location: no_autorizado.php"); exit; }
                include_once('crud/consultas_varias.php');
                echo '<span>' . htmlspecialchars(obtenerNombreUsuario($conn, $usuario)) . '</span>';
            ?>
        </li>
        <hr class="nav-divider">
        <li><a href="../php/crud/perfil.php"><img src="../recursos/perfil_icon.png" alt="" class="iconos">Perfil</a></li>
        <li><a href="crud/mascotas.php"><img src="../recursos/mascotas_icon.png" alt="" class="iconos">Mis mascotas</a></li>
        <hr class="nav-divider">
        <li><a href="../php/servicios_cliente.php"><img src="../recursos/servicio_icon.png" alt="" class="iconos">Turnos y servicios</a></li>
        <hr class="nav-divider">
        <li><a href="../php/main_cliente.php"><img src="../recursos/home_icon.png" alt="" class="iconos">Home</a></li>
        <li><a href="../php/logout.php" id="link_logout_menu">
            <img src="../recursos/logout_img.png" alt="Cerrar sesión">Cerrar sesión</a></li>
    </ul>
</nav>

<div id="nav_overlay"></div>

<script>
    const menu = document.getElementById("nav_sidebar");
    const overlay = document.getElementById("nav_overlay");
    const icono = document.getElementById("nav_menu_icon");

    icono.addEventListener('click', (event) => {
        event.stopPropagation();
        menu.classList.toggle('menu_desplegado');
        overlay.classList.toggle('visible');
    });

    overlay.addEventListener('click', () => {
        menu.classList.remove('menu_desplegado');
        overlay.classList.remove('visible');
    });
</script>