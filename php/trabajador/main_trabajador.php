<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adiestramiento Tahito</title>
    <link rel="apple-touch-icon" sizes="180x180" href="../../favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../../favicon_io/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../../favicon_io/favicon-16x16.png">
    <link rel="stylesheet" href="../../css/main_cliente_style.css?v=<?= time() ?>">
    <link rel="stylesheet" href="../../css/servicios_cliente.css?v=<?= time() ?>">
    <link rel="stylesheet" href="../../css/menus_admin.css">
</head>
<body>
<?php

//Conexion a base de datos y helper
require_once('../crud/conexion.php');
include_once('../crud/consultas_varias.php'); 

//Me traigo los datos del usuario
if (session_status() == PHP_SESSION_NONE) { session_start(); }

$username_trabajador = $_SESSION['username'];
$id_trabajador = obtenerIdPersona($conn, $username_trabajador);

//Coloco el header
include('header_trabajador.php');
?>
<main>
    <section id="servicios_contratados">
            <h2>Turnos pendientes</h2>
            <br>
            <div id="turnos_main">
            <?php 
            error_reporting(E_ALL);
            ini_set('display_errors', 1);
            $turnos_pendientes = turnosPendientesTrabajador($conn, $id_trabajador);

            if(count($turnos_pendientes) > 0){
            foreach($turnos_pendientes as $turno){?>
            <article class='servicio'>
                <h3><?php echo htmlspecialchars($turno['tipo_de_servicio']);?></h3>
                <?php //Busco el nombre de la mascota 
                $nombre_mascota = obtenerNombreMascota($conn, $turno['id_mascota']);?>
                <p><?php echo htmlspecialchars("Mascota: ". $nombre_mascota);?></p>
                <p><?php echo htmlspecialchars("Fecha y hora: ". $turno['horario']);?></p>
                <p><?php echo htmlspecialchars("Monto: ".$turno['monto']);?></p>
                <a href="../detalle_turno.php?id_servicio=<?= $turno['id_servicio'] ?>">Ver detalles del turno</a><br>
                <button class='cancelar_turno_btn'><a href="../crud/eliminar_servicio.php?id_servicio=<?= $turno['id_servicio'] ?>">Cancelar turno</a></button>
            </article>
            <?php
                }
            }else{
                echo "<div id='no_citas'><article><p>No hay citas pendientes en este momento.</p><br></article></div>";
            }?>
            </div>
    </section>
    <section id="menu_gestion">
        <article class="opc_menu_ap"><a href="balances_cuenta.php">
            <img src="../../recursos/balance_icono.png" alt=""> Balance de cuenta</a></article>
        <article class="opc_menu_ap"><a href="servicios_trabajador.php">
            <img src="../../recursos/servicio_icon.png" alt=""> Gestión de turnos y servicios</a></article>
        <?php
        if (isset($_GET['mensaje'])) {
            echo "<p style='margin: 1rem;'>" . htmlspecialchars($_GET['mensaje']) . "</p>";
            unset($_GET['mensaje']);
        }
        ?>
    </section>
</main>
<?php include('../footer.php');?>
</body>
</html>