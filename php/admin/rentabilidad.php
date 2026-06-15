<?php
require_once __DIR__ . '/../../config.php';
require_once(BASE_PATH . '/php/admin/auth.php');

// Restricción extra: solo admin
if ($_SESSION['rol'] !== 'admin' && $_SESSION['rol'] !== 'gestor') {
    header("Location: ../no_autorizado.php");
    exit;
}

require_once(BASE_PATH . '/php/crud/conexion.php');
require_once(BASE_PATH . '/php/crud/consultas_varias.php');

$anios = getAniosConDatos($conn);
$anioActual = intval($_GET['anio'] ?? date('Y'));
$datos = getRentabilidadMensual($conn, $anioActual);
$kpis = getKPIsMesActual($conn);

$meses_nombres = ['','Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
$mesActual = date('n');
$mesNombre = $meses_nombres[$mesActual];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rentabilidad — Tahito</title>
    <meta name="description" content="Dashboard de rentabilidad y análisis financiero del centro canino Tahito">
    <link rel="stylesheet" href="../../css/theme.css?v=<?= time() ?>">
    <link rel="stylesheet" href="../../css/reportes.css?v=<?= time() ?>">
    <link rel="apple-touch-icon" sizes="180x180" href="../../favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../../favicon_io/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../../favicon_io/favicon-16x16.png">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
</head>
<body>
    <?php include_once(__DIR__ . '/../includes/sidebar.php'); ?>

    <div class="page-reportes">
        <h1>📈 Rentabilidad</h1>
        <p class="page-subtitle">Análisis financiero del centro · <?= $mesNombre ?> <?= date('Y') ?></p>

        <!-- Toolbar -->
        <div class="toolbar">
            <div class="anio-selector">
                <label for="selectAnio">Año:</label>
                <select id="selectAnio" onchange="cambiarAnio(this.value)">
                    <?php foreach ($anios as $a): ?>
                        <option value="<?= $a ?>" <?= $a == $anioActual ? 'selected' : '' ?>><?= $a ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="toolbar-spacer"></div>
            <button class="btn" onclick="abrirModalCostos()">💼 Registrar Costos</button>
            <a href="reportes.php" class="btn-outline">📊 Ver Reportes</a>
        </div>

        <!-- KPI Cards -->
        <section class="kpi-grid">
            <div class="kpi-card kpi-ingresos">
                <div class="kpi-header">
                    <div class="kpi-icon">💰</div>
                    <span class="kpi-label">Ingresos del mes</span>
                </div>
                <p class="kpi-value value-positive">$ <?= number_format($kpis['ingresos'], 2, ',', '.') ?></p>
                <p class="kpi-detail">Servicios: $<?= number_format($kpis['ing_servicios'], 2, ',', '.') ?> · Ventas: $<?= number_format($kpis['ing_ventas'], 2, ',', '.') ?></p>
            </div>

            <div class="kpi-card kpi-costos">
                <div class="kpi-header">
                    <div class="kpi-icon">📉</div>
                    <span class="kpi-label">Costos del mes</span>
                </div>
                <p class="kpi-value value-negative">$ <?= number_format($kpis['costos'], 2, ',', '.') ?></p>
                <p class="kpi-detail">Compras: $<?= number_format($kpis['costo_compras'], 2, ',', '.') ?> · Sueldos: $<?= number_format($kpis['costo_sueldos'], 2, ',', '.') ?></p>
            </div>

            <div class="kpi-card kpi-ganancia">
                <div class="kpi-header">
                    <div class="kpi-icon">🏆</div>
                    <span class="kpi-label">Ganancia neta</span>
                </div>
                <p class="kpi-value <?= $kpis['ganancia'] >= 0 ? 'value-positive' : 'value-negative' ?>">
                    $ <?= number_format($kpis['ganancia'], 2, ',', '.') ?>
                </p>
                <p class="kpi-detail">
                    <span class="<?= $kpis['ganancia'] >= 0 ? 'badge-positive' : 'badge-negative' ?>">
                        <?= $kpis['ganancia'] >= 0 ? '▲' : '▼' ?> <?= $kpis['margen'] ?>%
                    </span>
                    margen de rentabilidad
                </p>
            </div>

            <div class="kpi-card kpi-margen">
                <div class="kpi-header">
                    <div class="kpi-icon">📊</div>
                    <span class="kpi-label">Margen neto</span>
                </div>
                <p class="kpi-value" style="color: <?= $kpis['margen'] >= 0 ? '#16A34A' : '#EF4444' ?>;">
                    <?= $kpis['margen'] ?> %
                </p>
                <p class="kpi-detail"><?= $mesNombre ?> <?= date('Y') ?></p>
            </div>
        </section>

        <!-- Charts -->
        <section class="charts-grid">
            <div class="chart-card">
                <h3>Ingresos vs Costos — <?= $anioActual ?></h3>
                <div class="chart-container">
                    <canvas id="chartBarras"></canvas>
                </div>
            </div>
            <div class="chart-card">
                <h3>Ganancia Neta Mensual — <?= $anioActual ?></h3>
                <div class="chart-container">
                    <canvas id="chartLinea"></canvas>
                </div>
            </div>
        </section>

        <!-- Detail Table -->
        <div class="report-table-wrapper">
            <h3>Desglose Mensual — <?= $anioActual ?></h3>
            <div style="overflow-x: auto;">
                <table class="report-table" id="tablaDesglose">
                    <thead>
                        <tr>
                            <th>Mes</th>
                            <th style="text-align:right">Ing. Servicios</th>
                            <th style="text-align:right">Ing. Ventas</th>
                            <th style="text-align:right">Total Ingresos</th>
                            <th style="text-align:right">Compras</th>
                            <th style="text-align:right">Sueldos</th>
                            <th style="text-align:right">Otros</th>
                            <th style="text-align:right">Total Costos</th>
                            <th style="text-align:right">Ganancia Neta</th>
                            <th style="text-align:center">Margen %</th>
                            <th style="text-align:center">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($datos)): ?>
                            <tr>
                                <td colspan="11">
                                    <div class="empty-state">
                                        <div class="empty-state-icon">📭</div>
                                        <p>No hay datos de rentabilidad para <?= $anioActual ?>. Registrá costos para comenzar.</p>
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php 
                            $sum_ing_srv = $sum_ing_vta = $sum_ing_total = 0;
                            $sum_cmp = $sum_sue = $sum_otr = $sum_cost = $sum_gan = 0;
                            foreach ($datos as $d): 
                                $sum_ing_srv += floatval($d['ingresos_servicios']);
                                $sum_ing_vta += floatval($d['ingresos_ventas']);
                                $sum_ing_total += floatval($d['ingresos_total']);
                                $sum_cmp += floatval($d['costo_compras']);
                                $sum_sue += floatval($d['costo_sueldos']);
                                $sum_otr += floatval($d['costo_otros']);
                                $sum_cost += floatval($d['costo_total']);
                                $sum_gan += floatval($d['ganancia_neta']);
                            ?>
                            <tr>
                                <td><strong><?= $meses_nombres[$d['periodo_mes']] ?></strong></td>
                                <td class="col-money">$ <?= number_format($d['ingresos_servicios'], 2, ',', '.') ?></td>
                                <td class="col-money">$ <?= number_format($d['ingresos_ventas'], 2, ',', '.') ?></td>
                                <td class="col-money"><strong>$ <?= number_format($d['ingresos_total'], 2, ',', '.') ?></strong></td>
                                <td class="col-money">$ <?= number_format($d['costo_compras'], 2, ',', '.') ?></td>
                                <td class="col-money">$ <?= number_format($d['costo_sueldos'], 2, ',', '.') ?></td>
                                <td class="col-money">$ <?= number_format($d['costo_otros'], 2, ',', '.') ?></td>
                                <td class="col-money"><strong>$ <?= number_format($d['costo_total'], 2, ',', '.') ?></strong></td>
                                <td class="col-money <?= floatval($d['ganancia_neta']) >= 0 ? 'value-positive' : 'value-negative' ?>">
                                    <strong>$ <?= number_format($d['ganancia_neta'], 2, ',', '.') ?></strong>
                                </td>
                                <td class="col-number">
                                    <span class="<?= floatval($d['margen_porcentaje']) >= 0 ? 'badge-positive' : 'badge-negative' ?>">
                                        <?= $d['margen_porcentaje'] ?>%
                                    </span>
                                </td>
                                <td style="text-align:center">
                                    <button class="btn-outline" style="padding:0.3rem 0.6rem;font-size:0.75rem" 
                                        onclick="editarCostos(<?= $d['periodo_anio'] ?>, <?= $d['periodo_mes'] ?>, <?= $d['costo_sueldos'] ?>, <?= $d['costo_otros'] ?>, '<?= htmlspecialchars($d['notas'] ?? '', ENT_QUOTES) ?>')">
                                        ✏️
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                    <?php if (!empty($datos)): ?>
                    <tfoot>
                        <tr>
                            <td><strong>TOTAL</strong></td>
                            <td class="col-money">$ <?= number_format($sum_ing_srv, 2, ',', '.') ?></td>
                            <td class="col-money">$ <?= number_format($sum_ing_vta, 2, ',', '.') ?></td>
                            <td class="col-money"><strong>$ <?= number_format($sum_ing_total, 2, ',', '.') ?></strong></td>
                            <td class="col-money">$ <?= number_format($sum_cmp, 2, ',', '.') ?></td>
                            <td class="col-money">$ <?= number_format($sum_sue, 2, ',', '.') ?></td>
                            <td class="col-money">$ <?= number_format($sum_otr, 2, ',', '.') ?></td>
                            <td class="col-money"><strong>$ <?= number_format($sum_cost, 2, ',', '.') ?></strong></td>
                            <td class="col-money <?= $sum_gan >= 0 ? 'value-positive' : 'value-negative' ?>">
                                <strong>$ <?= number_format($sum_gan, 2, ',', '.') ?></strong>
                            </td>
                            <td class="col-number">
                                <?php $margen_total = $sum_ing_total > 0 ? round(($sum_gan / $sum_ing_total) * 100, 1) : 0; ?>
                                <span class="<?= $margen_total >= 0 ? 'badge-positive' : 'badge-negative' ?>">
                                    <?= $margen_total ?>%
                                </span>
                            </td>
                            <td></td>
                        </tr>
                    </tfoot>
                    <?php endif; ?>
                </table>
            </div>
        </div>

        <div style="margin-top:1.5rem">
            <a href="main_admin.php" class="btn-volver-admin">← Volver al menú</a>
        </div>
    </div>

    <!-- Modal: Registrar Costos -->
    <div class="modal-overlay" id="modalCostos">
        <div class="modal-content">
            <h2>💼 Registrar Costos del Período</h2>
            <form id="formCostos" onsubmit="guardarCostos(event)">
                <div class="form-group">
                    <label for="costoAnio">Año</label>
                    <select id="costoAnio">
                        <?php foreach ($anios as $a): ?>
                            <option value="<?= $a ?>" <?= $a == $anioActual ? 'selected' : '' ?>><?= $a ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="costoMes">Mes</label>
                    <select id="costoMes">
                        <?php for ($m = 1; $m <= 12; $m++): ?>
                            <option value="<?= $m ?>" <?= $m == $mesActual ? 'selected' : '' ?>><?= $meses_nombres[$m] ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="costoSueldos">Costo Sueldos ($)</label>
                    <input type="number" id="costoSueldos" step="0.01" min="0" value="0" required>
                </div>
                <div class="form-group">
                    <label for="costoOtros">Otros Gastos ($)</label>
                    <input type="number" id="costoOtros" step="0.01" min="0" value="0" required>
                </div>
                <div class="form-group">
                    <label for="costoNotas">Notas</label>
                    <textarea id="costoNotas" placeholder="Detalle de gastos (opcional)"></textarea>
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn-cancel" onclick="cerrarModal()">Cancelar</button>
                    <button type="submit" class="btn">Guardar</button>
                </div>
            </form>
        </div>
    </div>

    <?php include('../footer.php'); ?>

    <script>
    // ── Datos para gráficos (inyectados desde PHP) ──
    const datosRentabilidad = <?= json_encode($datos) ?>;
    const mesesNombres = ['','Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'];

    // ── Chart.js: Barras Ingresos vs Costos ──
    const ctxBarras = document.getElementById('chartBarras');
    if (ctxBarras && datosRentabilidad.length > 0) {
        new Chart(ctxBarras, {
            type: 'bar',
            data: {
                labels: datosRentabilidad.map(d => mesesNombres[d.periodo_mes]),
                datasets: [
                    {
                        label: 'Ingresos',
                        data: datosRentabilidad.map(d => parseFloat(d.ingresos_total)),
                        backgroundColor: 'rgba(16, 185, 129, 0.7)',
                        borderColor: '#10B981',
                        borderWidth: 1,
                        borderRadius: 6,
                        borderSkipped: false
                    },
                    {
                        label: 'Costos',
                        data: datosRentabilidad.map(d => parseFloat(d.costo_total)),
                        backgroundColor: 'rgba(239, 68, 68, 0.6)',
                        borderColor: '#EF4444',
                        borderWidth: 1,
                        borderRadius: 6,
                        borderSkipped: false
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            pointStyle: 'circle',
                            padding: 16,
                            font: { family: "'Satoshi', sans-serif", size: 12, weight: 600 }
                        }
                    },
                    tooltip: {
                        backgroundColor: '#18181B',
                        titleFont: { family: "'Cabinet Grotesk', sans-serif", weight: 700 },
                        bodyFont: { family: "'Satoshi', sans-serif" },
                        padding: 12,
                        cornerRadius: 8,
                        callbacks: {
                            label: ctx => `${ctx.dataset.label}: $ ${ctx.parsed.y.toLocaleString('es-AR', {minimumFractionDigits: 2})}`
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: v => '$ ' + v.toLocaleString('es-AR'),
                            font: { family: "'JetBrains Mono', monospace", size: 11 }
                        },
                        grid: { color: 'rgba(0,0,0,0.04)' }
                    },
                    x: {
                        ticks: { font: { family: "'Satoshi', sans-serif", size: 12, weight: 600 } },
                        grid: { display: false }
                    }
                }
            }
        });
    }

    // ── Chart.js: Línea Ganancia Neta ──
    const ctxLinea = document.getElementById('chartLinea');
    if (ctxLinea && datosRentabilidad.length > 0) {
        const gananciaData = datosRentabilidad.map(d => parseFloat(d.ganancia_neta));
        
        new Chart(ctxLinea, {
            type: 'line',
            data: {
                labels: datosRentabilidad.map(d => mesesNombres[d.periodo_mes]),
                datasets: [{
                    label: 'Ganancia Neta',
                    data: gananciaData,
                    borderColor: '#14532D',
                    backgroundColor: (ctx) => {
                        const canvas = ctx.chart.ctx;
                        const gradient = canvas.createLinearGradient(0, 0, 0, 250);
                        gradient.addColorStop(0, 'rgba(34, 197, 94, 0.15)');
                        gradient.addColorStop(1, 'rgba(34, 197, 94, 0)');
                        return gradient;
                    },
                    fill: true,
                    tension: 0.4,
                    borderWidth: 2.5,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#14532D',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#18181B',
                        titleFont: { family: "'Cabinet Grotesk', sans-serif", weight: 700 },
                        bodyFont: { family: "'Satoshi', sans-serif" },
                        padding: 12,
                        cornerRadius: 8,
                        callbacks: {
                            label: ctx => `Ganancia: $ ${ctx.parsed.y.toLocaleString('es-AR', {minimumFractionDigits: 2})}`
                        }
                    }
                },
                scales: {
                    y: {
                        ticks: {
                            callback: v => '$ ' + v.toLocaleString('es-AR'),
                            font: { family: "'JetBrains Mono', monospace", size: 11 }
                        },
                        grid: { color: 'rgba(0,0,0,0.04)' }
                    },
                    x: {
                        ticks: { font: { family: "'Satoshi', sans-serif", size: 12, weight: 600 } },
                        grid: { display: false }
                    }
                }
            }
        });
    }

    // ── Functions ──
    function cambiarAnio(anio) {
        window.location.href = 'rentabilidad.php?anio=' + anio;
    }

    function abrirModalCostos() {
        document.getElementById('modalCostos').classList.add('active');
    }

    function cerrarModal() {
        document.getElementById('modalCostos').classList.remove('active');
    }

    function editarCostos(anio, mes, sueldos, otros, notas) {
        document.getElementById('costoAnio').value = anio;
        document.getElementById('costoMes').value = mes;
        document.getElementById('costoSueldos').value = sueldos;
        document.getElementById('costoOtros').value = otros;
        document.getElementById('costoNotas').value = notas;
        abrirModalCostos();
    }

    async function guardarCostos(e) {
        e.preventDefault();
        
        const payload = {
            anio: document.getElementById('costoAnio').value,
            mes: document.getElementById('costoMes').value,
            costo_sueldos: document.getElementById('costoSueldos').value,
            costo_otros: document.getElementById('costoOtros').value,
            notas: document.getElementById('costoNotas').value
        };

        try {
            const resp = await fetch('<?= BASE_URL ?>/php/crud/api_rentabilidad.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            });
            const data = await resp.json();
            
            if (data.success) {
                showToast('✅ ' + data.message);
                cerrarModal();
                setTimeout(() => location.reload(), 800);
            } else {
                showToast('❌ ' + (data.message || 'Error al guardar'));
            }
        } catch (err) {
            showToast('❌ Error de conexión');
        }
    }

    function showToast(msg) {
        const toast = document.createElement('div');
        toast.className = 'toast';
        toast.textContent = msg;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 3000);
    }

    // Close modal on overlay click
    document.getElementById('modalCostos').addEventListener('click', function(e) {
        if (e.target === this) cerrarModal();
    });
    </script>
</body>
</html>
