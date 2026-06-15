<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historia Clínica</title>

    <link rel="stylesheet" href="../../css/menus_admin.css">

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
        <h1>Historia Clínica</h1>

        <section id="menu_gestion">

            <article class="opc_menu_ap">
                <a href="tabla_historias_clinicas.php">
                    <img src="../../recursos/lista_mascotas_icon.png" alt="">
                    Gestionar historias clínicas
                </a>
            </article>

            <article class="opc_menu_ap">
                <a href="mascotas_sin_historia.php">
                    <img src="../../recursos/add_mascota.png" alt="">
                    Registrar primera atención
                </a>
            </article>

        </section>

        <section id="volver_s">
            <a href="main_admin.php" class="btn-volver-admin">Volver al menú principal</a>
        </section>

    </main>

    <?php include('../footer.php'); ?>

</body>

</html>