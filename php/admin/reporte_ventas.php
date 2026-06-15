<?php
require_once __DIR__ . '/../../config.php';
require_once(BASE_PATH . '/php/admin/auth.php');
if ($_SESSION['rol'] !== 'admin' && $_SESSION['rol'] !== 'gestor') { header("Location: ../no_autorizado.php"); exit; }

require_once(BASE_PATH . '/php/crud/conexion.php');
require_once(BASE_PATH . '/php/crud/consultas_varias.php');

$desde = $_GET['desde'] ?? date('Y-m-01');
$hasta = $_GET['hasta'] ?? date('Y-m-d');
$ventas = getReporteVentas($conn, $desde, $hasta);
$total = array_sum(array_column($ventas, 'total'));
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Ventas — Tahito</title>
    <link rel="stylesheet" href="../../css/theme.css?v=<?= time() ?>">
    <link rel="stylesheet" href="../../css/reportes.css?v=<?= time() ?>">
    <link rel="apple-touch-icon" sizes="180x180" href="../../favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../../favicon_io/favicon-32x32.png">
</head>
<body>
    <?php include_once(__DIR__ . '/../includes/sidebar.php'); ?>

    <div class="page-reportes">
        <h1>🛒 Reporte de Ventas</h1>
        <p class="page-subtitle">Detalle de ventas realizadas en el período seleccionado</p>

        <form method="GET" class="toolbar">
            <label for="desde">Desde:</label>
            <input type="date" id="desde" name="desde" value="<?= htmlspecialchars($desde) ?>">
            <label for="hasta">Hasta:</label>
            <input type="date" id="hasta" name="hasta" value="<?= htmlspecialchars($hasta) ?>">
            <button type="submit" class="btn">🔍 Filtrar</button>
            <div class="toolbar-spacer"></div>
            <a href="reporte_pdf.php?tipo=ventas&desde=<?= $desde ?>&hasta=<?= $hasta ?>" class="btn-pdf" target="_blank">📄 Exportar PDF</a>
            <a href="reportes.php" class="btn-outline">← Volver</a>
        </form>

        <!-- KPI Summary -->
        <section class="kpi-grid" style="grid-template-columns: repeat(3, 1fr);">
            <div class="kpi-card kpi-ingresos">
                <div class="kpi-header">
                    <div class="kpi-icon">💵</div>
                    <span class="kpi-label">Total Ventas</span>
                </div>
                <p class="kpi-value value-positive">$ <?= number_format($total, 2, ',', '.') ?></p>
            </div>
            <div class="kpi-card kpi-margen">
                <div class="kpi-header">
                    <div class="kpi-icon">🧾</div>
                    <span class="kpi-label">Cantidad</span>
                </div>
                <p class="kpi-value"><?= count($ventas) ?></p>
            </div>
            <div class="kpi-card kpi-ganancia">
                <div class="kpi-header">
                    <div class="kpi-icon">📊</div>
                    <span class="kpi-label">Promedio</span>
                </div>
                <p class="kpi-value">$ <?= count($ventas) > 0 ? number_format($total / count($ventas), 2, ',', '.') : '0,00' ?></p>
            </div>
        </section>

        <div class="report-table-wrapper">
            <div style="overflow-x: auto;">
                <table class="report-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Fecha</th>
                            <th>Cliente</th>
                            <th>Mascota</th>
                            <th>Productos</th>
                            <th style="text-align:right">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($ventas)): ?>
                            <tr><td colspan="6"><div class="empty-state"><div class="empty-state-icon">📭</div><p>No hay ventas en el período seleccionado</p></div></td></tr>
                        <?php else: ?>
                            <?php foreach ($ventas as $v): ?>
                            <tr>
                                <td class="col-number"><?= $v['id_venta'] ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($v['fecha'])) ?></td>
                                <td><?= htmlspecialchars($v['cliente']) ?></td>
                                <td><?= htmlspecialchars($v['mascota']) ?></td>
                                <td><?= htmlspecialchars($v['productos']) ?></td>
                                <td class="col-money">$ <?= number_format($v['total'], 2, ',', '.') ?></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                    <?php if (!empty($ventas)): ?>
                    <tfoot>
                        <tr>
                            <td colspan="5"><strong>TOTAL</strong></td>
                            <td class="col-money"><strong>$ <?= number_format($total, 2, ',', '.') ?></strong></td>
                        </tr>
                    </tfoot>
                    <?php endif; ?>
                </table>
            </div>
        </div>
    </div>

    <?php include('../footer.php'); ?>
</body>
</html>
