<?php
require_once(__DIR__ . '/../../config.php');
require_once(BASE_PATH . '/php/admin/auth.php');
require_once(BASE_PATH . '/php/crud/conexion.php');
require_once(BASE_PATH . '/php/crud/consultas_varias.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$usuario = $_SESSION['username'] ?? null;
?>

<link rel="stylesheet" href="<?= BASE_URL ?>/css/theme.css?v=<?= time() ?>">
<link rel="stylesheet" href="<?= BASE_URL ?>/css/header_cliente.css?v=<?= time() ?>">

<header id="header_cliente">
    <div class="header-toggle">
        <img src="<?= BASE_URL ?>/recursos/menu_img.png" id="nav_menu_icon" alt="Menú">
    </div>
    <div class="header-logo">
        <img src="<?= BASE_URL ?>/recursos/logsinfondo.png" alt="Tahito">
    </div>
    <div id="nav_cuenta">
        <a id="link_logout" href="<?= BASE_URL ?>/php/logout.php" title="Cerrar sesión">
            <img src="<?= BASE_URL ?>/recursos/logout_img.png" alt="Cerrar sesión">
        </a>
    </div>
</header>

<nav id="nav_sidebar">
    <ul id="nav_cliente">
        <li class="nav-user-name">
            <?php       
                if($usuario == null){ header("Location: no_autorizado.php"); exit; }
                echo '<span>' . htmlspecialchars(obtenerNombreUsuario($conn, $usuario)) . '</span>';
            ?>
        </li>
        <hr class="nav-divider">
                <li>
                    <a href="<?= BASE_URL ?>/php/trabajador/perfil_trabajador.php">
                        <img src="<?= BASE_URL ?>/recursos/perfil_icon.png" alt="" class="iconos">Perfil
                    </a>
                </li>

                <li>
                    <a href="<?= BASE_URL ?>/php/trabajador/balances_cuenta.php">
                        <img src="<?= BASE_URL ?>/recursos/balance_icono.png" alt="" class="iconos">Balance de cuenta
                    </a>
                </li>

                <hr>

                <li>
                    <a href="<?= BASE_URL ?>/php/trabajador/servicios_trabajador.php">
                        <img src="<?= BASE_URL ?>/recursos/servicio_icon.png" alt="" class="iconos">Turnos
                    </a>
                </li>

                <hr>

                <li>
                    <a href="<?= BASE_URL ?>/php/trabajador/main_trabajador.php">
                        <img src="<?= BASE_URL ?>/recursos/home_icon.png" alt="" class="iconos">Home
                    </a>
                </li>

                <li>
                    <a href="<?= BASE_URL ?>/php/logout.php" title="Cerrar sesión" id="link_logout_menu">
                        <img src="<?= BASE_URL ?>/recursos/logout_img.png" alt="Cerrar sesión">
                    </a>
                </li>
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