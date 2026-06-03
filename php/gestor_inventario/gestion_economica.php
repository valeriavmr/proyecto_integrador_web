<?php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Insumos</title>
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
    include_once(BASE_PATH . '/php/gestor_inventario/header_gi.php');
    ?>
    <main>
        <h1>Gestión Económica</h1>
        <section id="menu_gestion">
        </section>
        <section id="volver_s">
            <a href="main_gestor.php" class="btn-volver-admin">Volver al menú principal</a>
        </section>
    </main>
    <?php
    include('../footer.php');
    ?> 
</body>
</html>