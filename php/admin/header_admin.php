<?php
require_once(__DIR__ . '/../../config.php');
require_once(__DIR__ . '/auth.php');
require_once(BASE_PATH . '/php/crud/conexion.php');
require_once(BASE_PATH . '/php/crud/consultas_varias.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$usuario = $_SESSION['username'] ?? null;
?>
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
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/header_cliente.css?v=<?= time() ?>">
    <link rel="apple-touch-icon" sizes="180x180" href="<?= BASE_URL ?>/favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= BASE_URL ?>/favicon_io/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= BASE_URL ?>/favicon_io/favicon-16x16.png">
</head>
<body>
    <header id="header_cliente">
        <section>
            <img src="<?= BASE_URL ?>/recursos/menu_img.png" id="nav_menu_icon" alt="">
        </section>

        <section id="nav_cuenta">
            <a id="link_logout" href="<?= BASE_URL ?>/php/logout.php" title="Cerrar sesión">
                <img src="<?= BASE_URL ?>/recursos/logout_img.png" alt="Cerrar sesión">
            </a>
        </section>

        <nav>
            <h2>Perfil de Administrador</h2>
            <ul id="nav_cliente">
                <li>
                    <img src="<?= BASE_URL ?>/recursos/logsinfondo.png" alt="">
                </li>

                <li>
                    <a href="<?= BASE_URL ?>/php/crud/perfil.php">
                        <?= $usuario ? obtenerNombreUsuario($conn, $usuario) : 'Usuario' ?>
                    </a>
                </li>

                <hr>

                <li>
                    <a href="<?= BASE_URL ?>/php/admin/personas_admin.php">
                        <img src="<?= BASE_URL ?>/recursos/personas_icon.png" alt="" class="iconos">Personas
                    </a>
                </li>

                <li>
                    <a href="<?= BASE_URL ?>/php/admin/mascotas_admin.php">
                        <img src="<?= BASE_URL ?>/recursos/mascotas_icon.png" alt="" class="iconos">Mascotas
                    </a>
                </li>

                <hr>

                <li>
                    <a href="<?= BASE_URL ?>/php/admin/servicios_admin.php">
                        <img src="<?= BASE_URL ?>/recursos/servicio_icon.png" alt="" class="iconos">Servicios
                    </a>
                </li>

                <li>
                    <a href="<?= BASE_URL ?>/php/admin/trabajadores_admin.php">
                        <img src="<?= BASE_URL ?>/recursos/trabajador_icon.png" alt="" class="iconos">Trabajadores
                    </a>
                </li>

                <hr>

                <li>
                    <a href="<?= BASE_URL ?>/php/admin/main_admin.php">
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
    </header>

    <script>
        let menu = document.getElementById("nav_cliente");
        let icono = document.getElementById("nav_menu_icon");

        icono.addEventListener('click', (event) => {
            event.stopPropagation();
            menu.classList.toggle('menu_desplegado');
        });

        document.addEventListener('click', (event) => {
            if (!menu.contains(event.target) && event.target !== icono) {
                menu.classList.remove('menu_desplegado');
            }
        });
    </script>
</body>
</html>