<?php
require_once __DIR__ . '/../../config.php';
require_once(BASE_PATH . '/php/admin/auth.php');
if ($_SESSION['rol'] !== 'admin') { header("Location: ../no_autorizado.php"); exit; }

require_once(BASE_PATH . '/php/crud/conexion.php');
require_once(BASE_PATH . '/php/crud/consultas_varias.php');

$desde = $_GET['desde'] ?? date('Y-m-01');
$hasta = $_GET['hasta'] ?? date('Y-m-d');
$productos = getProductosTopVendidos($conn, $desde, $hasta, 15);

$max_cantidad = !empty($productos) ? max(array_column($productos, 'cantidad_vendida')) : 1;
$total_monto = array_sum(array_column($productos, 'monto_total'));
$total_unidades = array_sum(array_column($productos, 'cantidad_vendida'));
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos Más Vendidos — Tahito</title>
    <link rel="stylesheet" href="../../css/theme.css?v=<?= time() ?>">
    <link rel="stylesheet" href="../../css/reportes.css?v=<?= time() ?>">
    <link rel="apple-touch-icon" sizes="180x180" href="../../favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../../favicon_io/favicon-32x32.png">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
</head>
<body>
    <?php include_once(__DIR__ . '/../includes/sidebar.php'); ?>

    <div class="page-reportes">
        <h1>🏆 Productos Más Vendidos</h1>
        <p class="page-subtitle">Ranking de productos por cantidad vendida en el período</p>

        <form method="GET" class="toolbar">
            <label for="desde">Desde:</label>
            <input type="date" id="desde" name="desde" value="<?= htmlspecialchars($desde) ?>">
            <label for="hasta">Hasta:</label>
            <input type="date" id="hasta" name="hasta" value="<?= htmlspecialchars($hasta) ?>">
            <button type="submit" class="btn">🔍 Filtrar</button>
            <div class="toolbar-spacer"></div>
            <a href="reporte_pdf.php?tipo=productos_top&desde=<?= $desde ?>&hasta=<?= $hasta ?>" class="btn-pdf" target="_blank">📄 Exportar PDF</a>
            <a href="reportes.php" class="btn-outline">← Volver</a>
        </form>

        <!-- KPI Cards -->
        <section class="kpi-grid" style="grid-template-columns: repeat(3, 1fr);">
            <div class="kpi-card kpi-ingresos">
                <div class="kpi-header">
                    <div class="kpi-icon">💵</div>
                    <span class="kpi-label">Monto Total</span>
                </div>
                <p class="kpi-value value-positive">$ <?= number_format($total_monto, 2, ',', '.') ?></p>
            </div>
            <div class="kpi-card kpi-margen">
                <div class="kpi-header">
                    <div class="kpi-icon">📦</div>
                    <span class="kpi-label">Unidades Vendidas</span>
                </div>
                <p class="kpi-value"><?= $total_unidades ?></p>
            </div>
            <div class="kpi-card kpi-ganancia">
                <div class="kpi-header">
                    <div class="kpi-icon">🏷️</div>
                    <span class="kpi-label">Productos Distintos</span>
                </div>
                <p class="kpi-value"><?= count($productos) ?></p>
            </div>
        </section>

        <section class="charts-grid">
            <!-- Tabla ranking -->
            <div class="report-table-wrapper" style="animation-delay: 0s;">
                <div style="overflow-x: auto;">
                    <table class="report-table">
                        <thead>
                            <tr>
                                <th style="width:40px">#</th>
                                <th>Producto</th>
                                <th style="text-align:right">Precio Actual</th>
                                <th class="col-number">Unidades</th>
                                <th>Progreso</th>
                                <th class="col-number">Ventas</th>
                                <th style="text-align:right">Monto Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($productos)): ?>
                                <tr><td colspan="7"><div class="empty-state"><div class="empty-state-icon">📭</div><p>No hay ventas de productos en el período</p></div></td></tr>
                            <?php else: ?>
                                <?php foreach ($productos as $i => $p): 
                                    $pct = ($p['cantidad_vendida'] / $max_cantidad) * 100;
                                ?>
                                <tr>
                                    <td class="col-number">
                                        <?php if ($i < 3): ?>
                                            <span style="font-size:1.2rem"><?= ['🥇','🥈','🥉'][$i] ?></span>
                                        <?php else: ?>
                                            <?= $i + 1 ?>
                                        <?php endif; ?>
                                    </td>
                                    <td><strong><?= htmlspecialchars($p['nombre_producto']) ?></strong></td>
                                    <td class="col-money">$ <?= number_format($p['precio_actual'], 2, ',', '.') ?></td>
                                    <td class="col-number"><strong><?= $p['cantidad_vendida'] ?></strong></td>
                                    <td>
                                        <div class="ranking-bar-wrapper">
                                            <div class="ranking-bar">
                                                <div class="ranking-bar-fill" style="width: <?= $pct ?>%"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="col-number"><?= $p['num_ventas'] ?></td>
                                    <td class="col-money"><strong>$ <?= number_format($p['monto_total'], 2, ',', '.') ?></strong></td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                        <?php if (!empty($productos)): ?>
                        <tfoot>
                            <tr>
                                <td colspan="3"><strong>TOTAL</strong></td>
                                <td class="col-number"><strong><?= $total_unidades ?></strong></td>
                                <td></td>
                                <td></td>
                                <td class="col-money"><strong>$ <?= number_format($total_monto, 2, ',', '.') ?></strong></td>
                            </tr>
                        </tfoot>
                        <?php endif; ?>
                    </table>
                </div>
            </div>

            <!-- Chart horizontal bar -->
            <?php if (!empty($productos)): ?>
            <div class="chart-card">
                <h3>Top Productos por Unidades</h3>
                <div class="chart-container">
                    <canvas id="chartProductos"></canvas>
                </div>
            </div>
            <?php endif; ?>
        </section>
    </div>

    <?php include('../footer.php'); ?>

    <?php if (!empty($productos)): ?>
    <script>
    const prods = <?= json_encode($productos) ?>;
    const top10 = prods.slice(0, 10);
    const colores = ['#10B981','#14B8A6','#3B82F6','#6366F1','#8B5CF6','#EC4899','#F59E0B','#F97316','#EF4444','#84CC16'];

    new Chart(document.getElementById('chartProductos'), {
        type: 'bar',
        data: {
            labels: top10.map(p => p.nombre_producto.length > 18 ? p.nombre_producto.substring(0, 18) + '…' : p.nombre_producto),
            datasets: [{
                label: 'Unidades vendidas',
                data: top10.map(p => parseInt(p.cantidad_vendida)),
                backgroundColor: colores.slice(0, top10.length).map(c => c + 'CC'),
                borderColor: colores.slice(0, top10.length),
                borderWidth: 1,
                borderRadius: 6,
                borderSkipped: false
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#18181B',
                    padding: 12,
                    cornerRadius: 8,
                    callbacks: {
                        label: ctx => `${ctx.parsed.x} unidades`
                    }
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    ticks: { font: { family: "'JetBrains Mono', monospace", size: 11 } },
                    grid: { color: 'rgba(0,0,0,0.04)' }
                },
                y: {
                    ticks: { font: { family: "'Satoshi', sans-serif", size: 11, weight: 600 } },
                    grid: { display: false }
                }
            }
        }
    });
    </script>
    <?php endif; ?>
</body>
</html>
