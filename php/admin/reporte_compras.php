<?php
require_once __DIR__ . '/../../config.php';
require_once(BASE_PATH . '/php/admin/auth.php');
if ($_SESSION['rol'] !== 'admin' && $_SESSION['rol'] !== 'gestor') { header("Location: ../no_autorizado.php"); exit; }

require_once(BASE_PATH . '/php/crud/conexion.php');
require_once(BASE_PATH . '/php/crud/consultas_varias.php');

$desde = $_GET['desde'] ?? date('Y-m-01');
$hasta = $_GET['hasta'] ?? date('Y-m-d');
$compras = getReporteCompras($conn, $desde, $hasta);
$total = array_sum(array_column($compras, 'total'));

// Agrupar por proveedor
$por_proveedor = [];
foreach ($compras as $c) {
    $prov = $c['proveedor'];
    if (!isset($por_proveedor[$prov])) {
        $por_proveedor[$prov] = ['count' => 0, 'total' => 0];
    }
    $por_proveedor[$prov]['count']++;
    $por_proveedor[$prov]['total'] += floatval($c['total']);
}
arsort($por_proveedor);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Compras — Tahito</title>
    <link rel="stylesheet" href="../../css/theme.css?v=<?= time() ?>">
    <link rel="stylesheet" href="../../css/reportes.css?v=<?= time() ?>">
    <link rel="apple-touch-icon" sizes="180x180" href="../../favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../../favicon_io/favicon-32x32.png">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
</head>
<body>
    <?php include_once(__DIR__ . '/../includes/sidebar.php'); ?>

    <div class="page-reportes">
        <h1>🚚 Reporte de Compras</h1>
        <p class="page-subtitle">Gastos en compras a proveedores en el período seleccionado</p>

        <form method="GET" class="toolbar">
            <label for="desde">Desde:</label>
            <input type="date" id="desde" name="desde" value="<?= htmlspecialchars($desde) ?>">
            <label for="hasta">Hasta:</label>
            <input type="date" id="hasta" name="hasta" value="<?= htmlspecialchars($hasta) ?>">
            <button type="submit" class="btn">🔍 Filtrar</button>
            <div class="toolbar-spacer"></div>
            <a href="reporte_pdf.php?tipo=compras&desde=<?= $desde ?>&hasta=<?= $hasta ?>" class="btn-pdf" target="_blank">📄 Exportar PDF</a>
            <a href="reportes.php" class="btn-outline">← Volver</a>
        </form>

        <!-- KPI Cards -->
        <section class="kpi-grid" style="grid-template-columns: repeat(3, 1fr);">
            <div class="kpi-card kpi-costos">
                <div class="kpi-header">
                    <div class="kpi-icon">💸</div>
                    <span class="kpi-label">Total Gastado</span>
                </div>
                <p class="kpi-value value-negative">$ <?= number_format($total, 2, ',', '.') ?></p>
            </div>
            <div class="kpi-card kpi-margen">
                <div class="kpi-header">
                    <div class="kpi-icon">📦</div>
                    <span class="kpi-label">Compras Realizadas</span>
                </div>
                <p class="kpi-value"><?= count($compras) ?></p>
            </div>
            <div class="kpi-card kpi-ganancia">
                <div class="kpi-header">
                    <div class="kpi-icon">🏢</div>
                    <span class="kpi-label">Proveedores</span>
                </div>
                <p class="kpi-value"><?= count($por_proveedor) ?></p>
            </div>
        </section>

        <section class="charts-grid">
            <!-- Tabla detallada -->
            <div class="report-table-wrapper" style="animation-delay: 0s;">
                <h3 style="padding: 1.25rem 1.5rem 0.5rem;">Detalle de Compras</h3>
                <div style="overflow-x: auto;">
                    <table class="report-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Fecha</th>
                                <th>Proveedor</th>
                                <th>Detalle</th>
                                <th style="text-align:right">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($compras)): ?>
                                <tr><td colspan="5"><div class="empty-state"><div class="empty-state-icon">📭</div><p>No hay compras en el período</p></div></td></tr>
                            <?php else: ?>
                                <?php foreach ($compras as $c): ?>
                                <tr>
                                    <td class="col-number"><?= $c['id_compra'] ?></td>
                                    <td><?= date('d/m/Y', strtotime($c['fecha_compra'])) ?></td>
                                    <td><strong><?= htmlspecialchars($c['proveedor']) ?></strong></td>
                                    <td style="max-width:300px; font-size:0.82rem;"><?= htmlspecialchars($c['detalle'] ?? '-') ?></td>
                                    <td class="col-money">$ <?= number_format($c['total'], 2, ',', '.') ?></td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                        <?php if (!empty($compras)): ?>
                        <tfoot>
                            <tr>
                                <td colspan="4"><strong>TOTAL</strong></td>
                                <td class="col-money"><strong>$ <?= number_format($total, 2, ',', '.') ?></strong></td>
                            </tr>
                        </tfoot>
                        <?php endif; ?>
                    </table>
                </div>
            </div>

            <!-- Chart por proveedor -->
            <?php if (!empty($por_proveedor)): ?>
            <div class="chart-card">
                <h3>Gasto por Proveedor</h3>
                <div class="chart-container">
                    <canvas id="chartCompras"></canvas>
                </div>
            </div>
            <?php endif; ?>
        </section>
    </div>

    <?php include('../footer.php'); ?>

    <?php if (!empty($por_proveedor)): ?>
    <script>
    const proveedores = <?= json_encode(array_keys($por_proveedor)) ?>;
    const totales = <?= json_encode(array_values(array_map(function($p) { return $p['total']; }, $por_proveedor))) ?>;
    const colores = ['#EF4444','#F97316','#F59E0B','#84CC16','#10B981','#14B8A6','#3B82F6','#8B5CF6','#EC4899','#6366F1'];

    new Chart(document.getElementById('chartCompras'), {
        type: 'doughnut',
        data: {
            labels: proveedores,
            datasets: [{
                data: totales,
                backgroundColor: colores.slice(0, proveedores.length),
                borderWidth: 2,
                borderColor: '#fff',
                hoverOffset: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '55%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        usePointStyle: true,
                        pointStyle: 'circle',
                        padding: 12,
                        font: { family: "'Satoshi', sans-serif", size: 11, weight: 600 }
                    }
                },
                tooltip: {
                    backgroundColor: '#18181B',
                    padding: 12,
                    cornerRadius: 8,
                    callbacks: {
                        label: ctx => `${ctx.label}: $ ${ctx.parsed.toLocaleString('es-AR', {minimumFractionDigits: 2})}`
                    }
                }
            }
        }
    });
    </script>
    <?php endif; ?>
</body>
</html>
