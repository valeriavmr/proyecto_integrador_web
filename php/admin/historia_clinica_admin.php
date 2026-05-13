<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historia clínica de mascotas</title>
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
        <h2>Historia Clínica de Mascotas</h2>

        <section id="menu_gestion">
            <article class="opc_menu_ap">
                <a href="buscar_mascota_historia.php">
                    <img src="../../recursos/search_negro_icon.png" alt="">
                    Buscar mascota
                </a>
            </article>

            <article class="opc_menu_ap">
                <a href="crear_historia_clinica.php">
                    <img src="../../recursos/crear_turno_icon.png" alt="">
                    Nueva historia clínica
                </a>
            </article>

            <article class="opc_menu_ap">
                <a href="ver_historia_clinica.php">
                    <img src="../../recursos/lista_servicios_icon.png" alt="">
                    Ver historia clínica
                </a>
            </article>

            <article class="opc_menu_ap">
                <a href="registrar_atencion_clinica.php">
                    <img src="../../recursos/crear_tipo_servicio_icon.png" alt="">
                    Registrar atención
                </a>
            </article>
        </section>
    </main>

    <?php
    include('../footer.php');
    ?>
</body>
</html>