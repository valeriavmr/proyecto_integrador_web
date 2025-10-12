<?php

$nombre_usuario_logueado = 'Usuario'; // Un valor por defecto

// Verificamos si la sesión existe para buscar el nombre
if (isset($_SESSION['username'])) {
    

    require_once(BASE_PATH . '/php/crud/conexion.php');
    require_once(BASE_PATH . '/php/crud/consultas_varias.php');

    // Obtenemos el nombre del usuario para mostrarlo
    $nombre_usuario_logueado = obtenerNombreUsuario($conn, $_SESSION['username']);
}

?>

<header id="header_perfil">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Perfil</title>

    <nav class="menu">
        <ul>
        <li><a href="<?php echo BASE_URL; ?>/php/main_cliente.php">Home</a></li>
        <li><a href="<?php echo BASE_URL; ?>/php/crud/perfil.php">Mi Perfil</a></li>
        <li><a href="<?php echo BASE_URL; ?>/php/servicios_cliente.php">Servicios</a></li>
        <li><a href="<?php echo BASE_URL; ?>/php/main_guest.php">Cerrar Sesión</a></li>
        </ul>
    </nav>
</header>