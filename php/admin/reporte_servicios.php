<?php
require_once __DIR__ . '/../../config.php';
require_once(BASE_PATH . '/php/admin/auth.php');
if ($_SESSION['rol'] !== 'admin' && $_SESSION['rol'] !== 'gestor') { header("Location: ../no_autorizado.php"); exit; }

require_once(BASE_PATH . '/php/crud/conexion.php');
require_once(BASE_PATH . '/php/crud/consultas_varias.php');

$desde = $_GET['desde'] ?? date('Y-m-01');
$hasta = $_GET['hasta'] ?? date('Y-m-d');
$servicios = getReporteServicios($conn, $desde, $hasta);

$total_turnos = array_sum(array_column($servicios, 'total_turnos'));
$total_cobrado = array_sum(array_column($servicios, 'ingresos_cobrados'));
$total_pendiente = array_sum(array_column($servicios, 'ingresos_pendientes'));
$total_general = array_sum(array_column($servicios, 'ingresos_total'));
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Servicios — Tahito</title>
    <link rel="stylesheet" href="../../css/theme.css?v=<?= time() ?>">
    <link rel="stylesheet" href="../../css/reportes.css?v=<?= time() ?>">
    <link rel="apple-touch-icon" sizes="180x180" href="../../favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../../favicon_io/favicon-32x32.png">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
</head>
<body>
    <?php include_once(__DIR__ . '/../includes/sidebar.php'); ?>

    <div class="page-reportes">
        <h1>✂️ Reporte de Servicios</h1>
        <p class="page-subtitle">Ingresos por tipo de servicio en el período seleccionado</p>

        <form method="GET" class="toolbar">
            <label for="desde">Desde:</label>
            <input type="date" id="desde" name="desde" value="<?= htmlspecialchars($desde) ?>">
            <label for="hasta">Hasta:</label>
            <input type="date" id="hasta" name="hasta" value="<?= htmlspecialchars($hasta) ?>">
            <button type="submit" class="btn">🔍 Filtrar</button>
            <div class="toolbar-spacer"></div>
            <a href="reporte_pdf.php?tipo=servicios&desde=<?= $desde ?>&hasta=<?= $hasta ?>" class="btn-pdf" target="_blank">📄 Exportar PDF</a>
            <a href="reportes.php" class="btn-outline">← Volver</a>
        </form>

        <!-- KPI Cards -->
        <section class="kpi-grid">
            <div class="kpi-card kpi-margen">
                <div class="kpi-header">
                    <div class="kpi-icon">📅</div>
                    <span class="kpi-label">Total Turnos</span>
                </div>
                <p class="kpi-value"><?= $total_turnos ?></p>
            </div>
            <div class="kpi-card kpi-ingresos">
                <div class="kpi-header">
                    <div class="kpi-icon">✅</div>
                    <span class="kpi-label">Cobrado</span>
                </div>
                <p class="kpi-value value-positive">$ <?= number_format($total_cobrado, 2, ',', '.') ?></p>
            </div>
            <div class="kpi-card kpi-costos">
                <div class="kpi-header">
                    <div class="kpi-icon">⏳</div>
                    <span class="kpi-label">Pendiente</span>
                </div>
                <p class="kpi-value value-negative">$ <?= number_format($total_pendiente, 2, ',', '.') ?></p>
            </div>
            <div class="kpi-card kpi-ganancia">
                <div class="kpi-header">
                    <div class="kpi-icon">💰</div>
                    <span class="kpi-label">Total General</span>
                </div>
                <p class="kpi-value">$ <?= number_format($total_general, 2, ',', '.') ?></p>
            </div>
        </section>

        <!-- Chart + Table -->
        <section class="charts-grid">
            <div class="report-table-wrapper" style="animation-delay: 0s;">
                <div style="overflow-x: auto;">
                    <table class="report-table">
                        <thead>
                            <tr>
                                <th>Tipo de Servicio</th>
                                <th class="col-number">Turnos</th>
                                <th class="col-number">Pagados</th>
                                <th class="col-number">Pendientes</th>
                                <th style="text-align:right">Cobrado</th>
                                <th style="text-align:right">Pendiente</th>
                                <th style="text-align:right">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($servicios)): ?>
                                <tr><td colspan="7"><div class="empty-state"><div class="empty-state-icon">📭</div><p>No hay servicios en el período</p></div></td></tr>
                            <?php else: ?>
                                <?php foreach ($servicios as $s): ?>
                                <tr>
                                    <td><strong><?= htmlspecialchars($s['tipo_de_servicio']) ?></strong></td>
                                    <td class="col-number"><?= $s['total_turnos'] ?></td>
                                    <td class="col-number"><span class="status-badge pagado"><?= $s['turnos_pagados'] ?></span></td>
                                    <td class="col-number"><span class="status-badge pendiente"><?= $s['turnos_no_pagados'] ?></span></td>
                                    <td class="col-money value-positive">$ <?= number_format($s['ingresos_cobrados'], 2, ',', '.') ?></td>
                                    <td class="col-money value-negative">$ <?= number_format($s['ingresos_pendientes'], 2, ',', '.') ?></td>
                                    <td class="col-money"><strong>$ <?= number_format($s['ingresos_total'], 2, ',', '.') ?></strong></td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                        <?php if (!empty($servicios)): ?>
                        <tfoot>
                            <tr>
                                <td><strong>TOTAL</strong></td>
                                <td class="col-number"><strong><?= $total_turnos ?></strong></td>
                                <td class="col-number"><strong><?= array_sum(array_column($servicios, 'turnos_pagados')) ?></strong></td>
                                <td class="col-number"><strong><?= array_sum(array_column($servicios, 'turnos_no_pagados')) ?></strong></td>
                                <td class="col-money value-positive"><strong>$ <?= number_format($total_cobrado, 2, ',', '.') ?></strong></td>
                                <td class="col-money value-negative"><strong>$ <?= number_format($total_pendiente, 2, ',', '.') ?></strong></td>
                                <td class="col-money"><strong>$ <?= number_format($total_general, 2, ',', '.') ?></strong></td>
                            </tr>
                        </tfoot>
                        <?php endif; ?>
                    </table>
                </div>
            </div>

            <?php if (!empty($servicios)): ?>
            <div class="chart-card">
                <h3>Distribución por Servicio</h3>
                <div class="chart-container">
                    <canvas id="chartServicios"></canvas>
                </div>
            </div>
            <?php endif; ?>
        </section>
    </div>

    <?php include('../footer.php'); ?>

    <?php if (!empty($servicios)): ?>
    <script>
    const datosServicios = <?= json_encode($servicios) ?>;
    const colores = ['#10B981','#3B82F6','#F59E0B','#8B5CF6','#EF4444','#EC4899','#14B8A6','#F97316','#6366F1','#84CC16'];

    new Chart(document.getElementById('chartServicios'), {
        type: 'doughnut',
        data: {
            labels: datosServicios.map(s => s.tipo_de_servicio),
            datasets: [{
                data: datosServicios.map(s => parseFloat(s.ingresos_total)),
                backgroundColor: colores.slice(0, datosServicios.length),
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
