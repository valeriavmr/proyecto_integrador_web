<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administración de servicios</title>
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
    <br>    
    <h2>Gestión de Turnos y Servicios</h2>
    <section id="menu_gestion">
        <article class="opc_menu_ap"><a href="tabla_turnos.php" id="lista_servicios_op"><img src="../../recursos/lista_turnos_icon.png" alt="">Lista de turnos activos</a></article>
        <article class="opc_menu_ap"><a href="tabla_historico_servicios.php"><img src="../../recursos/lista_servicios_icon.png" alt="">Histórico de turnos</a></article>
        <article class="opc_menu_ap" id="pdf_datos_servicios"><a href="pdfs/pdf_turnos.php" target="_blank"><img src="../../recursos/pdf_icon.png" alt="">Generar reporte de datos de turnos</a></article>
        <article class="opc_menu_ap"><p><img src="../../recursos/crear_turno_icon.png" alt="">Crear turno</p></article>
        <article class="opc_menu_ap"><p><img src="../../recursos/search_icon.png" alt="">Buscar turno</p></article>
        <article class="opc_menu_ap"><p><img src="../../recursos/lista_tipos_servicios_icon.png" alt="">Lista de tipos de servicios</p></article>
        <article class="opc_menu_ap"><p><img src="../../recursos/pdf_icon.png" alt="">Generar reporte de datos de tipos de servicios</p></article>
        <article class="opc_menu_ap"><p><img src="../../recursos/crear_tipo_servicio_icon.png" alt="">Agregar tipo de servicio</p></article>
    </section>
    </main>
    <?php
    include('../footer.php');
    ?>
</body>
</html>