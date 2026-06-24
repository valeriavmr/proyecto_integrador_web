<?php
require_once dirname(__DIR__, 2) . '/config.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$rol = $_SESSION['rol'] ?? 'cliente';
$usuario = $_SESSION['username'] ?? 'Usuario';

// Obtener nombre real del usuario si es posible
require_once(BASE_PATH . '/php/crud/conexion.php');
include_once(BASE_PATH . '/php/crud/consultas_varias.php');
$nombreMostrar = obtenerNombreUsuario($conn, $usuario) ?? $usuario;
?>
<!-- Design System: Global Sidebar -->
<link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/theme.css?v=<?= time() ?>">
<link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/sidebar.css?v=<?= time() ?>">

<div class="sidebar" id="globalSidebar">
    <div class="sidebar-top">
        <div class="logo-area">
            <img src="<?php echo BASE_URL; ?>/recursos/logo_sidebar_white.png" class="logo-sidebar" alt="Logo">
            <div class="logo-text">
                <h2>Tahito</h2>
                <span>Centro de Cuidado Canino</span>
            </div>
            <button class="sidebar-toggle" id="btnToggleSidebar">☰</button>
        </div>

        <ul class="menu">
            <li>
            <?php if($rol == 'admin'): ?>
                <a href="<?php echo BASE_URL . '/php/admin/main_admin.php'; ?>">
                    <span class="menu-icon">🏠</span> <span class="menu-text">Inicio</span>
                </a>
            <?php elseif($rol == 'gestor'): ?>
                <a href="<?php echo BASE_URL . '/php/gestor_inventario/main_gestor.php'; ?>">
                    <span class="menu-icon">🏠</span> <span class="menu-text">Inicio</span>
                </a>
            <?php endif; ?>
            </li>

            <li>
                <a href="<?php echo BASE_URL; ?>/php/crud/perfil.php">
                    <span class="menu-icon">👤</span> <span class="menu-text">Perfil</span>
                </a>
            </li>

            <?php if($rol == 'admin'): ?>
            <li><a href="<?php echo BASE_URL; ?>/php/admin/add_turno_admin.php"><span class="menu-icon">📅</span> <span class="menu-text">Turnos</span></a></li>
            <li><a href="<?php echo BASE_URL; ?>/php/admin/mascotas_admin.php"><span class="menu-icon">🐶</span> <span class="menu-text">Pacientes</span></a></li>
            <li><a href="<?php echo BASE_URL; ?>/php/admin/historia_clinica_admin.php"><span class="menu-icon">🩺</span> <span class="menu-text">Historia Clínica</span></a></li>
            <li><a href="<?php echo BASE_URL; ?>/php/admin/servicios_admin.php"><span class="menu-icon">✂️</span> <span class="menu-text">Servicios</span></a></li>
            <?php endif; ?>

            <?php if($rol == 'admin' || $rol == 'gestor'): ?>
            <li><a href="<?php echo BASE_URL; ?>/php/gestor_inventario/gestion_insumos.php"><span class="menu-icon">📦</span> <span class="menu-text">Insumos</span></a></li>
            <li><a href="<?php echo BASE_URL; ?>/php/gestor_inventario/gestion_productos.php"><span class="menu-icon">🧾</span> <span class="menu-text">Productos</span></a></li>
            <li><a href="<?php echo BASE_URL; ?>/php/admin/venta_productos.php"><span class="menu-icon">🛒</span> <span class="menu-text">Venta de Productos</span></a></li>
            <li><a href="<?php echo BASE_URL; ?>/php/admin/proveedores_admin.php"><span class="menu-icon">🚚</span> <span class="menu-text">Proveedores</span></a></li>
            <li><a href="<?php echo BASE_URL; ?>/php/admin/reportes.php"><span class="menu-icon">📊</span> <span class="menu-text">Reportes</span></a></li>
            <li><a href="<?php echo BASE_URL; ?>/php/admin/rentabilidad.php"><span class="menu-icon">📈</span> <span class="menu-text">Rentabilidad</span></a></li>
            <?php endif; ?>

            <?php if($rol == 'admin'): ?>
            <li><a href="<?php echo BASE_URL; ?>/php/admin/personas_admin.php"><span class="menu-icon">👤</span> <span class="menu-text">Usuarios</span></a></li>
            <?php endif; ?>
        </ul>
    </div>

    <!-- FOOTER -->
    <div class="sidebar-footer">
        <div class="user-box">
            <div class="user-avatar">
                <?php echo strtoupper(substr($nombreMostrar, 0, 1)); ?>
            </div>
            <div class="user-info-text">
                <strong><?php echo htmlspecialchars($nombreMostrar); ?></strong>
                <br>
                <small>Rol: <?php echo htmlspecialchars($rol); ?></small>
            </div>
        </div>
        <a href="<?php echo BASE_URL; ?>/php/logout.php" class="btn-logout" title="Cerrar sesión" onclick="return confirm('¿Confirmar salir?');">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                <polyline points="16 17 21 12 16 7"></polyline>
                <line x1="21" y1="12" x2="9" y2="12"></line>
            </svg>
        </a>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const sidebar = document.getElementById('globalSidebar');
        const toggleBtn = document.getElementById('btnToggleSidebar');
        const body = document.body;

        // Recuperar estado del sidebar
        if (localStorage.getItem('sidebarCollapsed') === 'true') {
            sidebar.classList.add('collapsed');
            body.classList.add('sidebar-collapsed');
        }

        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
            body.classList.toggle('sidebar-collapsed');

            // Guardar estado
            const isCollapsed = sidebar.classList.contains('collapsed');
            localStorage.setItem('sidebarCollapsed', isCollapsed);
        });
    });
</script>