<?php
/**
 * Instalador de Datos Demo — Tahito Adiestramiento Canino
 * Ejecuta el script datos_demo.sql para poblar la BD con datos de prueba.
 *
 * ACCESO: Solo disponible en entorno local (localhost / 127.0.0.1)
 */

// ── Seguridad: solo localhost ──────────────────────────────────
$remoteIp = $_SERVER['REMOTE_ADDR'] ?? '';
$allowed  = ['127.0.0.1', '::1', 'localhost'];
if (!in_array($remoteIp, $allowed) && !str_starts_with($remoteIp, '192.168.')) {
    http_response_code(403);
    die('<h1>403 — Solo disponible en entorno local.</h1>');
}

require_once __DIR__ . '/../../config.php';
require_once BASE_PATH . '/php/crud/conexion.php';

$messages = [];
$success  = false;
$executed = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $executed = true;
    $sqlFile  = BASE_PATH . '/sql/datos_demo.sql';

    if (!file_exists($sqlFile)) {
        $messages[] = ['type' => 'error', 'text' => 'Archivo datos_demo.sql no encontrado en /sql/'];
    } else {
        // Garantizar UTF-8 en la conexión antes de todo
        $conn->set_charset('utf8mb4');
        $conn->query("SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci");

        $sql = file_get_contents($sqlFile);

        // Separar statements (respetando delimitadores)
        $statements = array_filter(
            array_map('trim', explode(';', $sql)),
            fn($s) => strlen($s) > 5
        );

        $total  = count($statements);
        $errors = 0;

        $conn->begin_transaction();
        try {
            foreach ($statements as $stmt) {
                // Saltar comentarios puros
                if (preg_match('/^\s*--/', $stmt) || preg_match('/^\s*\/\*/', $stmt)) {
                    continue;
                }
                if (!$conn->query($stmt)) {
                    $errors++;
                    $messages[] = ['type' => 'warning', 'text' => "SQL Error: " . $conn->error . "<br><small>" . htmlspecialchars(substr($stmt, 0, 120)) . "…</small>"];
                }
            }

            if ($errors === 0) {
                $conn->commit();
                $success = true;
                $messages[] = ['type' => 'success', 'text' => "✅ {$total} sentencias ejecutadas exitosamente. Base de datos poblada con datos demo."];
            } else {
                $conn->rollback();
                $messages[] = ['type' => 'error', 'text' => "❌ Se encontraron {$errors} errores. Se hizo rollback. Verificá que el schema esté aplicado antes de importar los datos demo."];
            }
        } catch (Exception $e) {
            $conn->rollback();
            $messages[] = ['type' => 'error', 'text' => "❌ Excepción: " . $e->getMessage()];
        }
    }
}

// ── Conteos actuales de la BD ──────────────────────────────────
function countTable($conn, $table) {
    $r = $conn->query("SELECT COUNT(*) AS c FROM `$table`");
    return $r ? (int)$r->fetch_assoc()['c'] : -1;
}

$counts = [
    'persona'       => countTable($conn, 'persona'),
    'mascota'       => countTable($conn, 'mascota'),
    'servicio'      => countTable($conn, 'servicio'),
    'ventas'        => countTable($conn, 'ventas'),
    'compras'       => countTable($conn, 'compras'),
    'rentabilidad'  => countTable($conn, 'rentabilidad'),
    'productos'     => countTable($conn, 'productos'),
    'proveedores'   => countTable($conn, 'proveedores'),
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instalador de Datos Demo — Tahito</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg:        #0f0f13;
            --surface:   #18181f;
            --surface2:  #22222c;
            --border:    #2e2e3e;
            --accent:    #7c6af7;
            --accent2:   #a78bfa;
            --green:     #22c55e;
            --red:       #ef4444;
            --yellow:    #f59e0b;
            --text:      #e2e2f0;
            --muted:     #8888aa;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            align-items: flex-start;
            justify-content: center;
            padding: 2rem 1rem;
        }

        .container {
            width: 100%;
            max-width: 760px;
        }

        /* ── Header ── */
        .header {
            text-align: center;
            margin-bottom: 2.5rem;
            padding-bottom: 2rem;
            border-bottom: 1px solid var(--border);
        }
        .header .logo {
            font-size: 3rem;
            margin-bottom: 0.75rem;
        }
        .header h1 {
            font-size: 1.75rem;
            font-weight: 800;
            background: linear-gradient(135deg, var(--accent2), var(--accent));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .header p {
            color: var(--muted);
            margin-top: 0.4rem;
            font-size: 0.95rem;
        }
        .badge-local {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            background: rgba(34, 197, 94, 0.12);
            color: var(--green);
            border: 1px solid rgba(34, 197, 94, 0.3);
            border-radius: 100px;
            padding: 0.25rem 0.85rem;
            font-size: 0.78rem;
            font-weight: 600;
            margin-top: 0.75rem;
        }

        /* ── Cards ── */
        .card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 1.75rem;
            margin-bottom: 1.25rem;
        }
        .card h2 {
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* ── Stats grid ── */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
            gap: 0.75rem;
        }
        .stat-item {
            background: var(--surface2);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 1rem;
            text-align: center;
        }
        .stat-num {
            font-size: 1.75rem;
            font-weight: 800;
            font-family: 'JetBrains Mono', monospace;
            color: var(--accent2);
        }
        .stat-num.zero { color: var(--muted); }
        .stat-label {
            font-size: 0.75rem;
            color: var(--muted);
            margin-top: 0.25rem;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }

        /* ── Demo contents ── */
        .demo-list {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
        .demo-list li {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            font-size: 0.88rem;
            line-height: 1.5;
            color: var(--muted);
        }
        .demo-list li .icon {
            font-size: 1rem;
            flex-shrink: 0;
            margin-top: 1px;
        }
        .demo-list li strong { color: var(--text); }

        /* ── Warning ── */
        .warning-box {
            background: rgba(245, 158, 11, 0.08);
            border: 1px solid rgba(245, 158, 11, 0.3);
            border-radius: 12px;
            padding: 1rem 1.25rem;
            color: #fcd34d;
            font-size: 0.875rem;
            line-height: 1.6;
            margin-bottom: 1.25rem;
        }
        .warning-box strong { color: #fde68a; }

        /* ── Form ── */
        .form-actions {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }
        .btn-install {
            flex: 1;
            min-width: 220px;
            padding: 1rem 2rem;
            font-size: 1rem;
            font-weight: 700;
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, var(--accent), var(--accent2));
            color: #fff;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.6rem;
        }
        .btn-install:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(124, 106, 247, 0.4);
        }
        .btn-install:active { transform: translateY(0); }

        .btn-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 1rem 1.5rem;
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--muted);
            background: var(--surface2);
            border: 1px solid var(--border);
            border-radius: 12px;
            text-decoration: none;
            transition: all 0.2s;
        }
        .btn-link:hover {
            color: var(--text);
            border-color: var(--accent);
        }

        /* ── Messages ── */
        .messages { display: flex; flex-direction: column; gap: 0.6rem; margin-bottom: 1.25rem; }
        .msg {
            padding: 0.85rem 1.1rem;
            border-radius: 10px;
            font-size: 0.875rem;
            line-height: 1.6;
        }
        .msg.success {
            background: rgba(34, 197, 94, 0.1);
            border: 1px solid rgba(34, 197, 94, 0.3);
            color: #86efac;
        }
        .msg.error {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #fca5a5;
        }
        .msg.warning {
            background: rgba(245, 158, 11, 0.1);
            border: 1px solid rgba(245, 158, 11, 0.3);
            color: #fcd34d;
        }

        /* ── Post-success links ── */
        .quick-links {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 0.75rem;
            margin-top: 1.25rem;
        }
        .quick-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.85rem 1rem;
            background: var(--surface2);
            border: 1px solid var(--border);
            border-radius: 12px;
            text-decoration: none;
            color: var(--text);
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.2s;
        }
        .quick-link:hover {
            border-color: var(--accent);
            background: rgba(124, 106, 247, 0.08);
            transform: translateY(-1px);
        }
        .quick-link .ql-icon { font-size: 1.2rem; }

        /* ── Credentials box ── */
        .creds-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.6rem;
        }
        .cred-item {
            background: var(--surface2);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 0.75rem 1rem;
        }
        .cred-role {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--muted);
            margin-bottom: 0.3rem;
        }
        .cred-user {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.85rem;
            color: var(--accent2);
        }
        .cred-pass {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.78rem;
            color: var(--muted);
            margin-top: 0.1rem;
        }

        @media (max-width: 500px) {
            .creds-grid { grid-template-columns: 1fr; }
            .form-actions { flex-direction: column; }
        }
    </style>
</head>
<body>
<div class="container">

    <!-- Header -->
    <div class="header">
        <div class="logo">🐾</div>
        <h1>Instalador de Datos Demo</h1>
        <p>Tahito Adiestramiento Canino — Módulo de Rentabilidad</p>
        <div class="badge-local">🔒 Solo entorno local</div>
    </div>

    <!-- Estado actual de la BD -->
    <div class="card">
        <h2>📊 Estado actual de la base de datos</h2>
        <div class="stats-grid">
            <?php
            $labels = [
                'persona'      => 'Personas',
                'mascota'      => 'Mascotas',
                'servicio'     => 'Turnos',
                'ventas'       => 'Ventas',
                'compras'      => 'Compras',
                'rentabilidad' => 'Períodos',
                'productos'    => 'Productos',
                'proveedores'  => 'Proveedores',
            ];
            foreach ($counts as $table => $count): ?>
                <div class="stat-item">
                    <div class="stat-num <?= $count === 0 ? 'zero' : '' ?>"><?= $count >= 0 ? $count : '?' ?></div>
                    <div class="stat-label"><?= $labels[$table] ?></div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Qué se va a cargar -->
    <div class="card">
        <h2>📋 Datos que se cargarán</h2>
        <ul class="demo-list">
            <li><span class="icon">👤</span><span><strong>13 personas:</strong> 1 admin, 1 gestor, 3 trabajadores y 8 clientes con datos completos</span></li>
            <li><span class="icon">🐕</span><span><strong>10 mascotas</strong> de distintas razas y tamaños con historias clínicas</span></li>
            <li><span class="icon">📅</span><span><strong>88 turnos</strong> de Enero a Junio 2026 con servicios variados (pagados y pendientes)</span></li>
            <li><span class="icon">🛒</span><span><strong>25 ventas</strong> de productos con detalle completo por mes</span></li>
            <li><span class="icon">📦</span><span><strong>11 órdenes de compra</strong> a 4 proveedores para generar costos reales</span></li>
            <li><span class="icon">💰</span><span><strong>6 registros de rentabilidad</strong> con sueldos y otros costos para Ene–Jun 2026</span></li>
            <li><span class="icon">📈</span><span>El módulo de rentabilidad mostrará <strong>gráficos completos</strong> con datos reales de los 6 meses</span></li>
            <li><span class="icon">🏪</span><span><strong>10 productos</strong>, <strong>8 insumos</strong> con inventario inicial y alertas de bajo stock</span></li>
        </ul>
    </div>

    <!-- Credenciales de prueba -->
    <div class="card">
        <h2>🔑 Credenciales de acceso (todos usan contraseña: <code style="color:var(--accent2)">password</code>)</h2>
        <div class="creds-grid">
            <div class="cred-item">
                <div class="cred-role">🛡️ Administrador</div>
                <div class="cred-user">admin_rodrigo</div>
                <div class="cred-pass">password</div>
            </div>
            <div class="cred-item">
                <div class="cred-role">📦 Gestor Inventario</div>
                <div class="cred-user">valeria_gestor</div>
                <div class="cred-pass">password</div>
            </div>
            <div class="cred-item">
                <div class="cred-role">🎓 Adiestrador</div>
                <div class="cred-user">carlos_trainer</div>
                <div class="cred-pass">password</div>
            </div>
            <div class="cred-item">
                <div class="cred-role">🐾 Cliente</div>
                <div class="cred-user">ana_perez</div>
                <div class="cred-pass">password</div>
            </div>
        </div>
    </div>

    <!-- Advertencia -->
    <div class="warning-box">
        ⚠️ <strong>Atención:</strong> Este script <strong>BORRARÁ todos los datos actuales</strong> de la base de datos y los reemplazará con los datos de demo. Asegurate de tener el schema aplicado (<code>schema_v2_completo.sql</code>) antes de continuar.
    </div>

    <!-- Mensajes de resultado -->
    <?php if (!empty($messages)): ?>
    <div class="messages">
        <?php foreach ($messages as $msg): ?>
            <div class="msg <?= $msg['type'] ?>"><?= $msg['text'] ?></div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- Formulario -->
    <?php if (!($executed && $success)): ?>
    <div class="card">
        <h2>🚀 Instalación</h2>
        <form method="POST">
            <div class="form-actions">
                <button type="submit" name="action" value="install" class="btn-install"
                        onclick="return confirm('¿Confirmar instalación de datos demo? Esto borrará los datos actuales.')">
                    ⚡ Instalar datos de demo
                </button>
                <a href="<?= BASE_URL ?>/php/login.php" class="btn-link">
                    ← Ir al login
                </a>
            </div>
        </form>
    </div>
    <?php endif; ?>

    <!-- Links rápidos post-instalación -->
    <?php if ($executed && $success): ?>
    <div class="card">
        <h2>🎉 ¡Listo! Explorá el sistema</h2>
        <div class="quick-links">
            <a href="<?= BASE_URL ?>/php/admin/rentabilidad.php" class="quick-link">
                <span class="ql-icon">📈</span>
                <span>Rentabilidad</span>
            </a>
            <a href="<?= BASE_URL ?>/php/admin/reportes.php" class="quick-link">
                <span class="ql-icon">📊</span>
                <span>Reportes</span>
            </a>
            <a href="<?= BASE_URL ?>/php/admin/venta_productos.php" class="quick-link">
                <span class="ql-icon">🛒</span>
                <span>Venta Productos</span>
            </a>
            <a href="<?= BASE_URL ?>/php/admin/tabla_personas.php" class="quick-link">
                <span class="ql-icon">👥</span>
                <span>Personas</span>
            </a>
            <a href="<?= BASE_URL ?>/php/admin/buscar_turno.php" class="quick-link">
                <span class="ql-icon">📅</span>
                <span>Turnos</span>
            </a>
            <a href="<?= BASE_URL ?>/php/admin/reporte_compras.php" class="quick-link">
                <span class="ql-icon">📦</span>
                <span>Compras</span>
            </a>
            <a href="<?= BASE_URL ?>/php/gestor_inventario/gestion_insumos.php" class="quick-link">
                <span class="ql-icon">🏪</span>
                <span>Inventario</span>
            </a>
            <a href="<?= BASE_URL ?>/php/login.php" class="quick-link">
                <span class="ql-icon">🔐</span>
                <span>Ir al Login</span>
            </a>
        </div>
    </div>
    <?php endif; ?>

</div>
</body>
</html>
