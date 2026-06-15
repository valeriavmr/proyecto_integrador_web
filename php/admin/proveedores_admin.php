<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administración de proveedores</title>
    <link rel="stylesheet" href="../../css/menus_admin.css">
    <link rel="apple-touch-icon" sizes="180x180" href="../../favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../../favicon_io/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../../favicon_io/favicon-16x16.png">
</head>
<body>
    <?php
    include_once __DIR__ . '\..\..\config.php';
    include_once(__DIR__ . '/../includes/sidebar.php');
    ?>

    <main>
    <br><br>
        <h2>Gestión de Proveedores</h2>

        <section id="menu_gestion">
            <article class="opc_menu_ap">
                <a href="tabla_proveedores.php">
                    <img src="../../recursos/lista_personas_icon.png" alt="">
                    Lista de proveedores
                </a>
            </article>

            <article class="opc_menu_ap">
                <a href="add_proveedor_admin.php">
                    <img src="../../recursos/person_add_icon.png" alt="">
                    Agregar proveedor
                </a>
            </article>

            <article class="opc_menu_ap">
                <a href="buscar_proveedor.php">
                    <img src="../../recursos/person_search_icon.png" alt="">
                    Buscar proveedor
                </a>
            </article>

            <article class="opc_menu_ap">
                <a href="historial_compras_proveedor.php">
                    <img src="../../recursos/lista_servicios_icon.png" alt="">
                    Historial de compras
                </a>
            </article>
            
            <article class="opc_menu_ap">
                <a href="registrar_compra_proveedor.php">
                    <img src="../../recursos/lista_servicios_icon.png" alt="">
                    Registrar compra
                </a>
            </article>
        </section>
        <section id="volver_s">
        <?php if($rol == 'admin'): ?><a href="../admin/main_admin.php" class="btn-volver-admin">Volver al menú principal</a>
        <?php elseif($rol == 'gestor'): ?><a href="../admin/main_admin.php" class="btn-volver-admin">Volver al menú principal</a>
        <?php endif; ?>
        </section>
    </main>

    <?php
    include('../footer.php');
    ?>
</body>
</html>