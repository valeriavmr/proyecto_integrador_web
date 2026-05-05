<?php

$nombre_usuario_logueado = 'Usuario';

if (isset($_SESSION['username'])) {
    require_once(BASE_PATH . '/php/crud/conexion.php');
    require_once(BASE_PATH . '/php/crud/consultas_varias.php');
    $nombre_usuario_logueado = obtenerNombreUsuario($conn, $_SESSION['username']);
}
?>

<!-- Design System: Sidebar header -->
<link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/theme.css?v=<?= time() ?>">
<link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/header_cliente.css?v=<?= time() ?>">

<header id="header_cliente">
    <div class="header-toggle">
        <img src="<?php echo BASE_URL; ?>/recursos/menu_img.png" id="nav_menu_icon" alt="Menú">
    </div>
    <div class="header-logo">
        <a href="<?php echo BASE_URL; ?>/php/main_cliente.php">
            <img src="<?php echo BASE_URL; ?>/recursos/logsinfondo.png" alt="Tahito">
        </a>
    </div>
    <div id="nav_cuenta">
        <a id="link_logout" href="<?php echo BASE_URL; ?>/php/logout.php" title="Cerrar sesión">
            <img src="<?php echo BASE_URL; ?>/recursos/logout_img.png" alt="Cerrar sesión">
        </a>
    </div>
</header>

<nav id="nav_sidebar">
    <ul id="nav_cliente">
        <li class="nav-user-name">
            <span><?php echo htmlspecialchars($nombre_usuario_logueado); ?></span>
        </li>
        <hr class="nav-divider">
        <li><a href="<?php echo BASE_URL; ?>/php/crud/perfil.php">
            <img src="<?php echo BASE_URL; ?>/recursos/perfil_icon.png" alt="" class="iconos">Perfil</a></li>
        <li><a href="<?php echo BASE_URL; ?>/php/crud/mascotas.php">
            <img src="<?php echo BASE_URL; ?>/recursos/mascotas_icon.png" alt="" class="iconos">Mis mascotas</a></li>
        <hr class="nav-divider">
        <li><a href="<?php echo BASE_URL; ?>/php/servicios_cliente.php">
            <img src="<?php echo BASE_URL; ?>/recursos/servicio_icon.png" alt="" class="iconos">Turnos y servicios</a></li>
        <hr class="nav-divider">
        <li><a href="<?php echo BASE_URL; ?>/php/main_cliente.php">
            <img src="<?php echo BASE_URL; ?>/recursos/home_icon.png" alt="" class="iconos">Home</a></li>
        <li><a href="<?php echo BASE_URL; ?>/php/logout.php" id="link_logout_menu">
            <img src="<?php echo BASE_URL; ?>/recursos/logout_img.png" alt="Cerrar sesión">Cerrar sesión</a></li>
    </ul>
</nav>

<div id="nav_overlay"></div>

<script>
    const menuSidebar  = document.getElementById("nav_sidebar");
    const menuOverlay  = document.getElementById("nav_overlay");
    const menuIcono    = document.getElementById("nav_menu_icon");

    menuIcono.addEventListener('click', (e) => {
        e.stopPropagation();
        menuSidebar.classList.toggle('menu_desplegado');
        menuOverlay.classList.toggle('visible');
    });

    menuOverlay.addEventListener('click', () => {
        menuSidebar.classList.remove('menu_desplegado');
        menuOverlay.classList.remove('visible');
    });
</script>
