<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administración de personas</title>
    <link rel="stylesheet" href="../../css/menus_admin.css">
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
        <article class="opc_menu_ap"><p><img src="../../recursos/person_add_icon.png" alt="">Agregar persona</p></article>
        <article class="opc_menu_ap"><p><img src="../../recursos/person_search_icon.png" alt="">Buscar persona</p></article>
    </section>
    </main>
    <?php
    include('../footer.php');
    ?>
</body>
</html>