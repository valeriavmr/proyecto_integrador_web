<?php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trazabilidad de Productos</title>
    <link rel="stylesheet" href="../../css/menu_gestor_inventario.css">
    <link rel="stylesheet" href="../../css/menus_admin.css">
    <link rel="apple-touch-icon" sizes="180x180" href="../../favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../../favicon_io/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../../favicon_io/favicon-16x16.png">
</head>
<body>
    <?php
    include_once __DIR__ . '\..\..\config.php';
    require_once(BASE_PATH . '/php/admin/auth.php');
    include_once(__DIR__ . '/../includes/sidebar.php');
    ?>
    <main>
        <h1>Trazabilidad de Productos</h1>
        <section id="menu_gestion">
            <article class="opc_menu_ap"><a href="<?php echo BASE_URL; ?>/php/gestor_inventario/lista_trazabilidad_productos.php">
                <img src="../../recursos/img/inventario_productos_icon.png" alt=""> Lista de trazabilidad</a></article>
            <article class="opc_menu_ap"><a href="<?php echo BASE_URL; ?>/php/gestor_inventario/trazabilidad_un_producto.php">
                <img src="../../recursos/img/trazabilidad_producto_icon.png" alt=""> Trazabilidad de un producto</a></article>
        </section>
        <section id="volver_s">
            <a href="gestion_productos.php" class="btn-volver-admin">Volver al gestión de productos</a>
        </section>
    </main>
    <?php
    include('../footer.php');
    ?> 
</body>
</html>