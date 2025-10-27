<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagina de inicio</title>
    <link rel="stylesheet" href="../../css/menus_admin.css">
    <link rel="apple-touch-icon" sizes="180x180" href="../../favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../../favicon_io/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../../favicon_io/favicon-16x16.png">
</head>
<body>
    <?php
    require_once('auth.php');
    include('header_admin.php');
    ?>
    <main>
        <h1>Menú de gestión</h1>
    <section id="menu_gestion">
        <article class="opc_menu_ap"><a href="personas_admin.php">
            <img src="../../recursos/perfil_icon.png" alt="">Gestión de personas</a></article>
        <article class="opc_menu_ap"><a href="servicios_admin.php">
            <img src="../../recursos/servicio_icon.png" alt="">Gestión de turnos y servicios</a></article>
                    <?php
        if (isset($_GET['mensaje'])) {
            echo "<p style='margin: 1rem;'>" . htmlspecialchars($_GET['mensaje']) . "</p>";
            unset($_GET['mensaje']);
        }
        ?>
        <article class="opc_menu_ap"><a href="mascotas_admin.php">
            <img src="../../recursos/mascotas_icon.png" alt="">Gestión de mascotas</a></article>

        <article class="opc_menu_ap"><p><img src="../../recursos/trabajador_icon.png" alt="">Gestión de trabajadores</p></article>
    </section>
    </main>
    <?php
    include('../footer.php');
    ?>
</body>
</html>