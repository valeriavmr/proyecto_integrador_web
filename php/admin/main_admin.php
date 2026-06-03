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
    ?>
    <main>
    <br><br>
    <h1>Menú de gestión</h1>
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
            <a href="../gestor_inventario/main_gestor.php">
                <img src="../../recursos/img/gestion_productos_icon.png" alt="">
                 Gestión de inventario
            </a>
        </article>

        <article class="opc_menu_ap"><a href="reportes.php">
            <img src="../../recursos/pdf_icon.png" alt=""> Reportes</a></article>

        <article class="opc_menu_ap"><a href="rentabilidad.php">
            <img src="../../recursos/balance_icono.png" alt=""> Rentabilidad</a></article>

    </section>
    </main>
    <?php
    include('../footer.php');
    ?>
</body>
</html>