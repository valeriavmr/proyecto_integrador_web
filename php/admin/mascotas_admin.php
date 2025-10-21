<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administración de Mascotas</title>
    <link rel="stylesheet" href="../../css/menus_admin.css">
</head>
<body>
    <?php
    require_once('auth.php');
    include('header_admin.php');
    ?>
    <main>
    <h2>Gestión de Mascotas</h2>
    <section id="menu_gestion">
        <article class="opc_menu_ap"><a href="tabla_mascotas.php" id="lista_mascotas_op"><img src="../../recursos/lista_mascotas_icon.png" alt=""> Lista de mascotas registradas</a></article>
        <article class="opc_menu_ap" id="listado_mascotas_pdf"><a href="pdfs/listado_mascotas_pdf.php" target="_blank"><img src="../../recursos/pdf_icon.png" alt=""> Generar Reporte de Mascotas</a></article>
        <article class="opc_menu_ap"><p><img src="../../recursos/person_add_icon.png" alt=""> Agregar Mascota</p></article>
        <article class="opc_menu_ap"><p><img src="../../recursos/person_search_icon.png" alt=""> Buscar Mascota</p></article>
    </section>
    </main>
    <?php
    include('../footer.php');
    ?>
</body>
</html>