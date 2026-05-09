<?php 
require_once dirname(__DIR__, 2) . '/config.php'; 
require_once('../admin/auth.php');
?>
<!-- Design System: Sidebar header -->
<link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/theme.css?v=<?= time() ?>">
<link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/header_cliente.css?v=<?= time() ?>">

<style>
/* Botón Volver - estilo global para todo el panel admin */
.btn-volver-admin {
    display: inline-block;
    margin-top: 1.5rem;
    margin-bottom: 1rem;
    padding: 0.65rem 1.4rem;
    background-color: transparent;
    color: var(--forest-green, #2E6009);
    border: 2px solid var(--forest-green, #2E6009);
    border-radius: 6px;
    text-decoration: none;
    font-weight: 600;
    font-size: 0.9rem;
    font-family: inherit;
    transition: background-color 0.2s ease, color 0.2s ease;
}
.btn-volver-admin:hover {
    background-color: var(--forest-green, #2E6009);
    color: #fff;
}
</style>

<header id="header_cliente">
    <div class="header-toggle">
        <img src="<?php echo BASE_URL; ?>/recursos/menu_img.png" id="nav_menu_icon" alt="Menú">
    </div>
    <div class="header-logo">
        <a href="<?php echo BASE_URL; ?>/php/gestor_inventario/main_gestor.php">
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
            <span>
                <?php       
                    require_once(BASE_PATH . '/php/crud/conexion.php');
                    if (session_status() == PHP_SESSION_NONE) {
                        session_start();
                    }
                    $usuario = $_SESSION['username'];
                    include_once(BASE_PATH . '/php/crud/consultas_varias.php');
                    echo obtenerNombreUsuario($conn, $usuario); 
                ?>
            </span>
        <h3>Gestor de Inventario</h3>
        </li>
        <hr class="nav-divider">
        <li><a href="<?php echo BASE_URL; ?>/php/gestor_inventario/gestion_productos.php"><img src="<?php echo BASE_URL; ?>/recursos/img/gestion_productos_icon.png" alt="" class="iconos">Productos</a></li>
        <li><a href="<?php echo BASE_URL; ?>/php/gestor_inventario/gestion_insumos.php"><img src="<?php echo BASE_URL; ?>/recursos/img/gestion_insumos_icon.png" alt="" class="iconos">Insumos</a></li>
        <hr class="nav-divider">
        <li><a href="#"><img src="<?php echo BASE_URL; ?>/recursos/img/ventas_icon.png" alt="" class="iconos">Ventas</a></li>
        <li><a href="#"><img src="<?php echo BASE_URL; ?>/recursos/trabajador_icon.png" alt="" class="iconos">Proveedores</a></li>
        <li><a href="#"><img src="<?php echo BASE_URL; ?>/recursos/pdf_icon.png" alt="" class="iconos">Reportes</a></li>
        <hr class="nav-divider">
        <li><a href="<?php echo BASE_URL; ?>/php/gestor_inventario/main_gestor.php"><img src="<?php echo BASE_URL; ?>/recursos/home_icon.png" alt="" class="iconos">Home</a></li>
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
        if (menuOverlay) menuOverlay.classList.toggle('visible');
    });

    if (menuOverlay) {
        menuOverlay.addEventListener('click', () => {
            menuSidebar.classList.remove('menu_desplegado');
            menuOverlay.classList.remove('visible');
        });
    }
</script>