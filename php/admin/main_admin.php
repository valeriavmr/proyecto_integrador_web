<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagina de inicio</title>
    <link rel="stylesheet" href="../../css/theme.css?v=<?= time() ?>">
    <link rel="stylesheet" href="../../css/menus_admin.css?v=<?= time() ?>">
    <link rel="apple-touch-icon" sizes="180x180" href="../../favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../../favicon_io/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../../favicon_io/favicon-16x16.png">
</head>
<body>
    <?php
    require_once('auth.php');
    include_once(__DIR__ . '/../includes/sidebar.php');
    include_once(BASE_PATH . '/php/crud/consultas_varias.php');
    ?>
    <main>
    <br><br>
    <h1>Menú de gestión</h1>

    <?php if($rol == 'gestor' || $rol == 'admin'): ?>
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
    <?php endif; ?>
    <section id="menu_gestion">
        <article class="opc_menu_ap"><a href="personas_admin.php">
            <img src="../../recursos/perfil_icon.png" alt=""> Gestión de personas</a></article>
        <article class="opc_menu_ap"><a href="servicios_admin.php">
            <img src="../../recursos/servicio_icon.png" alt=""> Gestión de turnos y servicios</a></article>
                    <?php
        if (isset($_GET['mensaje'])) {
            echo "<p style='margin: 1rem;'>" . htmlspecialchars($_GET['mensaje']) . "</p>";
            unset($_GET['mensaje']);
        }
        ?>
        <article class="opc_menu_ap"><a href="mascotas_admin.php">
            <img src="../../recursos/mascotas_icon.png" alt=""> Gestión de mascotas</a></article>

        <article class="opc_menu_ap"><a href="trabajadores_admin.php"><img src="../../recursos/trabajador_icon.png" alt=""> Gestión de trabajadores</a></article>
        
        <article class="opc_menu_ap"><a href="venta_productos.php"><img src="../../recursos/shopping-cart.png" alt="">      Venta de Productos</a></article>
        <article class="opc_menu_ap">
            <a href="proveedores_admin.php">
                <img src="../../recursos/lista_personas_icon.png" alt="">
                Gestión de proveedores
            </a>
        </article>

        <article class="opc_menu_ap">
            <a href="historia_clinica_admin.php">
                <img src="../../recursos/mascotas_icon.png" alt="">
                Historia clínica
            </a>
        </article>

        <article class="opc_menu_ap">
            <a href="../gestor_inventario/gestion_insumos.php">
                <img src="../../recursos/img/gestion_insumos_icon.png" alt="">
                 Gestión de insumos
            </a>
        </article>

        <article class="opc_menu_ap">
            <a href="../gestor_inventario/gestion_productos.php">
                <img src="../../recursos/img/gestion_productos_icon.png" alt="">
                 Gestión de productos
            </a>
        </article>

        <article class="opc_menu_ap"><a href="reportes.php">
            <img src="../../recursos/pdf_icon.png" alt=""> Reportes</a></article>

    </section>
    </main>
    <?php
    include('../footer.php');
    ?>
</body>
</html>