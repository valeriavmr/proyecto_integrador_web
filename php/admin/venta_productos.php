<?php 
include '../crud/conexion.php';
include_once('auth.php');
include_once __DIR__ . '\..\..\config.php';

//Busco el rol del usuario
$rol = $_SESSION['rol'];
// Generamos un número de venta aleatorio
$numeroVenta = rand(1000, 9999);


include '../crud/conexion.php'; 

$numeroVenta = rand(1000, 9999);

/* =========================
   CARDS DASHBOARD
========================= */

$totalProductos = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total
         FROM productos
         WHERE activo = 1"
    )
);

$totalMedicamentos = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total
         FROM productos
         WHERE activo = 1
         AND tipo = 'Medicamento'"
    )
);

$totalVacunas = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total
         FROM productos
         WHERE activo = 1
         AND tipo = 'Vacuna'"
    )
);

$totalOtros = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total
         FROM productos
         WHERE activo = 1
         AND tipo = 'Otro'"
    )
);

$stockBajo = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total_stock_bajo
         FROM inventario i
         INNER JOIN productos p
         ON p.id_producto = i.id_producto
         WHERE p.activo = 1
         AND i.cantidad_actual_producto <= i.param_bajo_stock"
    )
);

/* =========================
   VENTAS RECIENTES
========================= */

$sqlVentasRecientes = "
    SELECT
        v.id_venta,
        v.fecha,
        v.total,

        COALESCE(
            CONCAT(p.nombre, ' ', p.apellido),
            'Consumidor Final'
        ) AS cliente,

        GROUP_CONCAT(
            pr.nombre_producto
            SEPARATOR ', '
        ) AS productos

    FROM ventas v

    LEFT JOIN persona p
        ON v.id_persona = p.id_persona

    INNER JOIN detalle_venta dv
        ON v.id_venta = dv.id_venta

    INNER JOIN productos pr
        ON dv.id_producto = pr.id_producto

    GROUP BY
        v.id_venta,
        v.fecha,
        v.total,
        cliente

    ORDER BY v.fecha DESC

    LIMIT 5
";

$ventasRecientes = mysqli_query($conn, $sqlVentasRecientes);
?>



<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Venta de Productos - Tahito</title>
    <link rel="stylesheet" href="../../css/theme.css?v=<?= time() ?>">
    <link rel="stylesheet" href="../../css/style.css?v=<?= time() ?>">
    <link rel="stylesheet" href="../../css/clientes.css?v=<?= time() ?>">
    <link rel="stylesheet" href="../../css/sidebar.css?v=<?= time() ?>">
    <link rel="stylesheet" href="../../css/ticket.css?v=<?= time() ?>">
    <link rel="stylesheet" href="../../css/print.css?v=<?= time() ?>">
    
</head>

<body>

<!-- SIDEBAR -->
<?php include_once(__DIR__ . '/../includes/sidebar.php'); ?>

<!-- CONTENIDO -->
<div class="main-content">
    <div class="container">
        <!-- CARDS DASHBOARD -->
        <div class="cards-dashboard">

            <div class="dashboard-card">

                <div class="card-top">
                    <span class="card-icon">📦</span>
                    <span class="card-title">Productos</span>
                </div>

                <div class="card-number">
                    <?= $totalProductos['total'] ?? 0 ?>
                </div>

                <a href="../gestor_inventario/inventario_productos.php" class="card-link">
                    Ver stock →
                </a>

            </div>

            <div class="dashboard-card">

                <div class="card-top">
                    <span class="card-icon">💊</span>
                    <span class="card-title">Medicamentos</span>
                </div>

                <div class="card-number">
                    <?= $totalMedicamentos['total'] ?? 0 ?>
                </div>

                <a href="../gestor_inventario/inventario_productos.php?tipo=Medicamento" class="card-link">
                    Ver medicamentos →
                </a>

            </div>

            <div class="dashboard-card">

                <div class="card-top">
                    <span class="card-icon">💉</span>
                    <span class="card-title">Vacunas</span>
                </div>

                <div class="card-number">
                    <?= $totalVacunas['total'] ?? 0 ?>
                </div>

                <a href="../gestor_inventario/inventario_productos.php?tipo=Vacuna" class="card-link">
                    Ver vacunas →
                </a>

            </div>

            <div class="dashboard-card">

                <div class="card-top">
                    <span class="card-icon">🧴</span>
                    <span class="card-title">Otros</span>
                </div>

                <div class="card-number">
                    <?= $totalOtros['total'] ?? 0 ?>
                </div>

                <a href="../gestor_inventario/inventario_productos.php?tipo=Otro" class="card-link">
                    Ver otros →
                </a>

            </div>

            <div class="dashboard-card alerta">

                <div class="card-top">
                    <span class="card-icon">⚠️</span>
                    <span class="card-title">Stock Bajo</span>
                </div>

                <div class="card-number">
                    <?= $stockBajo['total_stock_bajo'] ?? 0 ?>
                </div>

                <a href="../gestor_inventario/inventario_productos.php?stock=bajo" class="card-link">
                    Ver alertas →
                </a>

            </div>

        </div>
    
        <h1>Venta de Productos</h1>
        
        <div class="grid">

        <!-- PANEL IZQUIERDO: PRODUCTOS -->
        <div class="card">
            <div class="filtros-categoria">

                <button class="filtro-btn active" data-categoria="todos">
                    Todos
                </button>

                <button class="filtro-btn" data-categoria="Medicamento">
                    Medicamentos
                </button>

                <button class="filtro-btn" data-categoria="Vacuna">
                    Vacunas
                </button>

                <button class="filtro-btn" data-categoria="Otro">
                    Otros
                </button>

            </div>

            <br>
                
            <input type="text" id="buscar" placeholder="🔍 Buscar producto por nombre o categoría...">
            <br><br>

            <table>
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Categoría</th>
                        <th>Stock</th>
                        <th>Precio</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody id="tbodyProductos">
                    <?php
                    $sql = "
                    SELECT
                        p.id_producto,
                        p.nombre_producto AS nombre,
                        p.tipo,
                        p.precio_unitario AS precio_venta,
                        COALESCE(i.cantidad_actual_producto, 0) AS stock_actual
                    FROM productos p
                    LEFT JOIN inventario i
                    ON p.id_producto = i.id_producto
                    WHERE p.activo = 1
                    ";

                    $result = mysqli_query($conn, $sql);

                    while($row = mysqli_fetch_assoc($result)){ ?>
                        <tr data-categoria="<?= $row['tipo'] ?>">
                            <td><?= htmlspecialchars($row['nombre']) ?></td>
                            <td><?= htmlspecialchars($row['tipo']) ?></td>
                            <td><?= $row['stock_actual'] ?></td>
                            <td>$<?= number_format($row['precio_venta'], 2) ?></td>
                            <td>
                                <button class="btn" 
                                onclick="agregarProducto(
                                    <?= $row['id_producto'] ?>,
                                    '<?= addslashes($row['nombre']) ?>', 
                                    <?= $row['precio_venta'] ?>
                                )">
                                    ➡️
                                </button>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <!-- PANEL DERECHO: TICKET -->
        <div class="card" id="ticket">
            <h2 style="margin-top:0;">
            🛒 Venta Actual
            </h2>
            
            <!-- BUSCADOR DE CLIENTE -->
            <div class="cliente-box">
                <div class="autocomplete-box">
                    <input type="text" id="buscarCliente" placeholder="👤 Buscar cliente...">
                    <div id="listaClientes"></div>
                </div>
                <input type="hidden" id="idPersona">
                <button class="btn" onclick="nuevoCliente()" title="Nuevo Cliente">+</button>
            </div>

            <br>

            <!-- SELECCIÓN DE MASCOTA -->
            <select id="mascotaSelect">
                <option value="">🐾 Seleccionar mascota</option>
            </select>

            <hr>

            <!-- ENCABEZADO TICKET -->
            <div class="ticket-header">
                <img src="../../recursos/logsinfondo.png" class="ticket-logo" alt="Logo">
                <h2>Tahito</h2>
                <div>Centro Veterinario</div>
                <br>
                <div>Fecha: <?= date("d/m/Y H:i") ?></div>
                <div>Ticket N°: <strong><?= $numeroVenta ?></strong></div>
            </div>

            <br>
            <!-- CÓDIGO DE BARRAS -->
            <svg id="barcode"></svg>

            <br>
            <div>Cliente: <span id="ticketCliente">Consumidor Final</span></div>
            <div>Mascota: <span id="ticketMascota">-</span></div>

            <div class="ticket-divider">--------------------------------</div>

            <!-- TABLA DE ITEMS SELECCIONADOS -->
            <table id="tablaVenta">
                <thead>
                    <tr>
                        <th>Prod.</th>
                        <th>Cant</th>
                        <th>P.Unit</th>
                        <th>Sub</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Se llena con JS -->
                </tbody>
            </table>

            <div class="ticket-divider">--------------------------------</div>

            <!-- RESUMEN DE TOTALES -->
            <div class="ticket-linea">
                <span>Subtotal:</span>
                <span>$ <span id="subtotal">0.00</span></span>
            </div>

            <div class="ticket-linea">
                <span>IVA (21%):</span>
                <span>$ <span id="iva">0.00</span></span>
            </div>

            <h3 class="ticket-total">
                TOTAL: $ <span id="total">0.00</span>
            </h3>

            <!-- QR GENERADO -->
            <div id="qrcode"></div>

            <br>
            <button class="btn" style="width:100%; font-weight:bold;" onclick="guardarVenta()">
                ✅ CONFIRMAR VENTA
            </button>
        </div>

    </div>
    <!-- VENTAS RECIENTES -->
    <div class="card ventas-recientes">

        <h3>
            Ventas recientes
        </h3>

        <table class="tabla-ventas-recientes">

            <thead>

                <tr>
                    <th>Fecha</th>
                    <th>Cliente</th>
                    <th>Productos</th>
                    <th>Total</th>
                    <th>Acciones</th>
                </tr>

            </thead>

            <tbody>

                <?php if ($ventasRecientes && mysqli_num_rows($ventasRecientes) > 0): ?>

                    <?php while($venta = mysqli_fetch_assoc($ventasRecientes)): ?>

                        <tr>

                            <td>
                                <?= date("d/m/Y H:i", strtotime($venta['fecha'])) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($venta['cliente']) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($venta['productos']) ?>
                            </td>

                            <td>
                                $ <?= number_format($venta['total'], 2) ?>
                            </td>

                            <td>

                                <a
                                    href="ver_venta.php?id=<?= $venta['id_venta'] ?>"
                                    class="btn-accion"
                                    title="Ver detalle"
                                >
                                    👁️
                                </a>

                                <a
                                    href="ticket_venta.php?id=<?= $venta['id_venta'] ?>"
                                    class="btn-accion"
                                    title="Ver ticket"
                                >
                                    🧾
                                </a>

                            </td>

                        </tr>

                    <?php endwhile; ?>

                <?php else: ?>

                    <tr>
                        <td colspan="5" style="text-align:center;">
                            No hay ventas registradas
                        </td>
                    </tr>

                <?php endif; ?>

            </tbody>

        </table>

        <div class="ver-todas-ventas">

            <a href="historial_ventas.php">
                Ver todas las ventas
            </a>

        </div>

    </div>
</div>

<!-- LIBRERIAS EXTERNAS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>


<script src="ventas.js"></script>

<script>
    // Inicializar código de barras al cargar
    JsBarcode("#barcode", "<?= $numeroVenta ?>", {
        format: "CODE128",
        width: 1.5,
        height: 40,
        displayValue: true,
        fontSize: 12
    });
</script>

</body>
</html>
    