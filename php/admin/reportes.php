<?php
require_once __DIR__ . '/../../config.php';
require_once(BASE_PATH . '/php/admin/auth.php');

// Solo admin
if ($_SESSION['rol'] !== 'admin') {
    header("Location: ../no_autorizado.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportes — Tahito</title>
    <meta name="description" content="Centro de reportes del sistema de gestión Tahito">
    <link rel="stylesheet" href="../../css/theme.css?v=<?= time() ?>">
    <link rel="stylesheet" href="../../css/reportes.css?v=<?= time() ?>">
    <link rel="apple-touch-icon" sizes="180x180" href="../../favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../../favicon_io/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../../favicon_io/favicon-16x16.png">
</head>
<body>
    <?php include_once(__DIR__ . '/../includes/sidebar.php'); ?>

    <div class="page-reportes" style="display:flex; flex-direction:column; align-items:center;">
        <h1 style="text-align:center;">📊 Centro de Reportes</h1>
        <p class="page-subtitle" style="text-align:center;">Generá reportes detallados de todas las operaciones del centro</p>

        <div class="report-menu-grid">
            <div class="report-menu-card">
                <a href="reporte_ventas.php">
                    <div class="report-card-icon">🛒</div>
                    <div class="report-card-text">
                        <h3>Reporte de Ventas</h3>
                        <p>Historial de ventas de productos con detalle por cliente y fecha</p>
                    </div>
                </a>
            </div>

            <div class="report-menu-card">
                <a href="reporte_servicios.php">
                    <div class="report-card-icon">✂️</div>
                    <div class="report-card-text">
                        <h3>Reporte de Servicios</h3>
                        <p>Ingresos por tipo de servicio, turnos cobrados y pendientes</p>
                    </div>
                </a>
            </div>

            <div class="report-menu-card">
                <a href="reporte_compras.php">
                    <div class="report-card-icon">🚚</div>
                    <div class="report-card-text">
                        <h3>Reporte de Compras</h3>
                        <p>Gastos en compras a proveedores por período</p>
                    </div>
                </a>
            </div>

            <div class="report-menu-card">
                <a href="reporte_productos_top.php">
                    <div class="report-card-icon">🏆</div>
                    <div class="report-card-text">
                        <h3>Productos Más Vendidos</h3>
                        <p>Ranking de productos por cantidad vendida y monto generado</p>
                    </div>
                </a>
            </div>

            <div class="report-menu-card">
                <a href="rentabilidad.php">
                    <div class="report-card-icon">📈</div>
                    <div class="report-card-text">
                        <h3>Dashboard de Rentabilidad</h3>
                        <p>Análisis financiero con gráficos, ganancias y costos</p>
                    </div>
                </a>
            </div>
        </div>

        <div style="margin-top:2rem; text-align:center;">
            <a href="main_admin.php" class="btn-volver-admin">← Volver al menú</a>
        </div>
    </div>

    <?php include('../footer.php'); ?>
</body>
</html>
