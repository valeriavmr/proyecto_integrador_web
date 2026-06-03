<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagina de inicio</title>
    <link rel="stylesheet" href="../../css/menu_gestor_inventario.css">
    <link rel="apple-touch-icon" sizes="180x180" href="../../favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../../favicon_io/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../../favicon_io/favicon-16x16.png">
</head>
<body>
    <?php
    include_once __DIR__ . '\..\..\config.php';
    require_once(BASE_PATH . '/php/admin/auth.php');
    $rol = $_SESSION['rol'];
    if ($rol == 'admin') {
        include_once(BASE_PATH . '/php/admin/header_admin.php');
    } elseif ($rol == 'gestor') {
        include_once(BASE_PATH . '/php/gestor_inventario/header_gi.php');
    } else {
        header('Location: ' . BASE_URL . '/php/login.php');
        exit();
    }
    include_once(BASE_PATH . '/php/crud/consultas_varias.php');
    ?>
<main class="dashboard">

<?php
$insumos_bajo_stock = count(obtenerInsumosConBajoStock($conn));
$productos_bajo_stock = count(obtenerProductosConBajoStock($conn));
?>

    <h1>Menú de gestión</h1>

<section class="dashboard_cards">

    <article class="card">
        <div class="icono">💉</div>
        <div class="info">
            <p class="titulo">Insumos en stock</p>
            <p class="valor"><?php echo getCantidadTotalInsumos($conn); ?></p>
        </div>
    </article>

    <article class="card">
        <div class="icono">🛍️</div>
        <div class="info">
            <p class="titulo">Productos en stock</p>
            <p class="valor"><?php echo getCantidadTotalProductos($conn);?></p>
        </div>
    </article>

    <article class="card alerta <?= $insumos_bajo_stock > 0 ? 'con-alerta' : '' ?>">
        <div class="icono">⚠</div>
        <div class="info">
            <p class="titulo">Insumos con bajo stock</p>
            <a href="<?php echo BASE_URL; ?>/php/gestor_inventario/inventario_insumos.php?filtro=bajo_stock"><p class="valor"><?php echo $insumos_bajo_stock; ?></p></a>
        </div>
    </article>

    <article class="card alerta <?= $productos_bajo_stock > 0 ? 'con-alerta' : '' ?>">
        <div class="icono">⚠</div>
        <div class="info">
            <p class="titulo">Productos con bajo stock</p>
            <a href="<?php echo BASE_URL; ?>/php/gestor_inventario/inventario_productos.php?filtro=bajo_stock"><p class="valor"><?php echo $productos_bajo_stock; ?></p></a>
        </div>
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
            <a href="#">
                <img src="../../recursos/pdf_icon.png" alt="">
                Reportes
            </a>
        </article>

        <article class="opc_menu_ap">
            <a href="gestion_economica.php">
                <img src="../../recursos/img/gestion_economica_icon.png" alt="">
                Gestión económica
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