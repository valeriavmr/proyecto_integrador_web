<?php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Insumos</title>
    <link rel="stylesheet" href="../../css/theme.css?v=<?= time() ?>">
    <link rel="stylesheet" href="../../css/menu_gestor_inventario.css?v=<?= time() ?>">
    <link rel="stylesheet" href="../../css/menus_admin.css?v=<?= time() ?>">
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
        <h1>Gestión de Insumos</h1>
        <section id="menu_gestion">
            <article class="opc_menu_ap"><a href="<?php echo BASE_URL; ?>/php/gestor_inventario/inventario_insumos.php">
                <img src="../../recursos/img/gestion_insumos_icon.png" alt=""> Inventario de insumos</a></article>
            <article class="opc_menu_ap"><a href="<?php echo BASE_URL; ?>/php/gestor_inventario/add_insumo.php">
                <img src="../../recursos/img/agregar_insumo_icon.png" alt=""> Agregar insumo</a></article>
            <article class="opc_menu_ap"><a href="<?php echo BASE_URL; ?>/php/gestor_inventario/trazabilidad_insumos.php">
                <img src="../../recursos/img/trazabilidad_insumo_icon.png" alt=""> Trazabilidad de un insumo</a></article>
            <article class="opc_menu_ap"><a href="<?php echo BASE_URL; ?>/php/gestor_inventario/buscar_insumo.php">
                <img src="../../recursos/img/buscar_insumo_icon.png" alt=""> Buscar un insumo</a></article>
        </section>
        <section id="volver_s">
        <?php if($rol == 'admin'): ?><a href="../admin/main_admin.php" class="btn-volver-admin">Volver al menú principal</a>
        <?php elseif($rol == 'gestor'): ?><a href="../gestor_inventario/main_gestor.php" class="btn-volver-admin">Volver al menú principal</a>
        <?php endif; ?>
        </section>
    </main>
    <?php
    include('../footer.php');
    ?> 
</body>
</html>