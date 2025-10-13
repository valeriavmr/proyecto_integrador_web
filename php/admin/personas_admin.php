<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administración de personas</title>
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
    <h2>Gestión de Personas</h2>
    <section id="menu_gestion">
        <article class="opc_menu_ap"><a href="tabla_personas.php" id="lista_personas_op"><img src="../../recursos/lista_personas_icon.png" alt="">Lista de personas registradas</a></article>
        <article class="opc_menu_ap" id="pdf_datos_personas"><a href="pdfs/pdf_personas.php" target="_blank"><img src="../../recursos/pdf_icon.png" alt="">Generar reporte de datos de usuarios</a></article>
        <article class="opc_menu_ap"><a href="crear_usuario.php"><img src="../../recursos/person_add_icon.png" alt="">Agregar persona</a></article>
        <article class="opc_menu_ap"><a href="buscar_persona.php"><img src="../../recursos/person_search_icon.png" alt="">Buscar persona</a></article>
    </section>
    </main>
    <?php
    include('../footer.php');
    ?>
</body>
</html>