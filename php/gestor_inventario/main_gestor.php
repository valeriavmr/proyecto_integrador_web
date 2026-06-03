<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagina de inicio</title>
    <link rel="stylesheet" href="../../css/theme.css?v=<?= time() ?>">
    <link rel="stylesheet" href="../../css/menu_gestor_inventario.css?v=<?= time() ?>">
    <link rel="apple-touch-icon" sizes="180x180" href="../../favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../../favicon_io/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../../favicon_io/favicon-16x16.png">
</head>
<body>
    <?php
    include_once __DIR__ . '\..\..\config.php';
    require_once(BASE_PATH . '/php/admin/auth.php');
    include_once(__DIR__ . '/../includes/sidebar.php');
    include_once(BASE_PATH . '/php/crud/consultas_varias.php');
    ?>
<main class="dashboard">

<?php
$insumos_bajo_stock = count(obtenerInsumosConBajoStock($conn));
$productos_bajo_stock = count(obtenerProductosConBajoStock($conn));
?>
<br><br>
    <h1>Menú de gestión</h1>
<br>
<section class="dashboard_cards">

    <article class="dashboard-card">
        <div class="card-top">
            <div class="card-icon">💉</div>
            <p class="card-title">Insumos en stock</p>
        </div>
        <p class="card-number"><?php echo getCantidadTotalInsumos($conn); ?></p>
    </article>

    <article class="dashboard-card">
        <div class="card-top">
            <div class="card-icon">🛍️</div>
            <p class="card-title">Productos en stock</p>
        </div>
        <p class="card-number"><?php echo getCantidadTotalProductos($conn);?></p>
    </article>

    <article class="dashboard-card alerta">
        <div class="card-top">
            <div class="card-icon">⚠</div>
            <p class="card-title">Insumos bajo stock</p>
        </div>
        <a href="<?php echo BASE_URL; ?>/php/gestor_inventario/inventario_insumos.php?filtro=bajo_stock" class="card-link">
            <p class="card-number"><?php echo count(obtenerInsumosConBajoStock($conn)); ?></p>
        </a>
    </article>

    <article class="dashboard-card alerta">
        <div class="card-top">
            <div class="card-icon">⚠</div>
            <p class="card-title">Productos bajo stock</p>
        </div>
        <a href="<?php echo BASE_URL; ?>/php/gestor_inventario/inventario_productos.php?filtro=bajo_stock" class="card-link">
            <p class="card-number"><?php echo count(obtenerProductosConBajoStock($conn)); ?></p>
        </a>
    </article>

</section>
    <section id="menu_gestion_gestor_inventario">

        <article class="opc_menu_ap">
            <a href="<?php echo BASE_URL; ?>/php/gestor_inventario/gestion_insumos.php">
                <img src="../../recursos/img/gestion_insumos_icon.png" alt="">
                Gestión de Insumos
            </a>
        </article>

        <article class="opc_menu_ap">
            <a href="<?php echo BASE_URL; ?>/php/gestor_inventario/gestion_productos.php">
                <img src="../../recursos/img/gestion_productos_icon.png" alt="">
                Gestión de Productos
            </a>
        </article>

        <article class="opc_menu_ap">
            <a href="../admin/venta_productos.php">
                <img src="../../recursos/img/ventas_icon.png" alt="">
                Ventas
            </a>
        </article>

        <article class="opc_menu_ap">
            <a href="../admin/proveedores_admin.php">
                <img src="../../recursos/personas_icon.png" alt="">
                Gestión de proveedores
            </a>
        </article>

        <article class="opc_menu_ap">
            <a href="<?php echo BASE_URL; ?>/php/admin/reportes.php">
                <img src="../../recursos/pdf_icon.png" alt="">
                Reportes
            </a>
        </article>

    </section>

    <?php if($rol == 'admin'): ?>
    <section id="volver_s">
            <a href="../admin/main_admin.php" class="btn-volver-admin">Volver al menú principal</a>
    </section>
    <?php endif; ?>
</main>
    <?php
    include('../footer.php');
    ?>
</body>
</html>