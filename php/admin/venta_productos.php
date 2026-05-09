<?php 
include '../crud/conexion.php'; 
// Generamos un número de venta aleatorio
$numeroVenta = rand(1000, 9999);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Venta de Productos - Tahito</title>
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="../../css/clientes.css">
    <link rel="stylesheet" href="../../css/sidebar.css">
    <link rel="stylesheet" href="../../css/ticket.css">
    <link rel="stylesheet" href="../../css/print.css">
    
</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <div class="sidebar-top">
     
        <div class="logo-area">

            <img src="../../recursos/logo_sidebar_white.png"
            class="logo-sidebar">

            <div>

                <h2>Tahito</h2>

                <span>
                    Centro de Cuidado Canino
                </span>

             </div>

         </div>

        <ul class="menu">

            <li><a href="#">🏠 Inicio</a></li>
            <li><a href="#">📅 Turnos</a></li>
            <li><a href="#">🐶 Pacientes</a></li>
            <li><a href="#">📋 Historia Clínica</a></li>
            <li><a href="#">✂️ Servicios</a></li>
            <li><a href="#">📦 Insumos</a></li>
            <li><a href="#">🧾 Stock</a></li>

            <li class="active">
                <a href="#">🛒 Venta de Productos</a>
            </li>

            <li><a href="#">🚚 Proveedores</a></li>
            <li><a href="#">💰 Gestión Económica</a></li>
            <li><a href="#">📊 Reportes</a></li>
            <li><a href="#">👤 Usuarios</a></li>
            <li><a href="#">⚙️ Configuración</a></li>

        </ul>
    </div>


 <!-- FOOTER -->
<div class="sidebar-footer">

        <div class="user-box">

                <div class="user-avatar"></div>

                <div>
                    <strong>Administrador</strong>
                    <br>
                    <small>Rol: Administrador</small>
                </div>
        </div>

    </div>

</div>














<!-- CONTENIDO -->
<div class="main-content">
    
        <div class="container">
        
            <h1>Venta de Productos</h1>

            <div class="grid">

            <!-- PANEL IZQUIERDO: PRODUCTOS -->
            <div class="card">
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
                        $sql = "SELECT id_producto, nombre, tipo, stock_actual, precio_venta 
                                FROM productos WHERE activo = 1";
                        $result = mysqli_query($conn, $sql);

                        while($row = mysqli_fetch_assoc($result)){
                        ?>
                            <tr>
                                <td><?= htmlspecialchars($row['nombre']) ?></td>
                                <td><?= htmlspecialchars($row['tipo']) ?></td>
                                <td><?= $row['stock_actual'] ?></td>
                                <td>$<?= number_format($row['precio_venta'], 2) ?></td>
                                <td>
                                    <button class="btn" onclick="agregarProducto(<?= $row['id_producto'] ?>, '<?= addslashes($row['nombre']) ?>', <?= $row['precio_venta'] ?>)">
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
    </div>

    <!-- LIBRERIAS EXTERNAS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

    <!-- TU ARCHIVO DE LÓGICA -->
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