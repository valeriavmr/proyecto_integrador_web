<?php
require('conexion.php');

$tipo_servicio = $_POST['tipo_servicio'];
$id_mascota = $_POST['mascota'];
$id_trabajador = $_POST['trabajador'];
$fecha = $_POST['fecha'];
$hora = $_POST['hora'];
$detalles = $_POST['detalles'];
$monto_servicio = $_POST['monto_servicio'];

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (empty($detalles)) {
    $detalles = "N/A";
}

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['solicitar_turno_btn'])){
    $sql = "INSERT INTO servicio_g3 (tipo_de_servicio, id_mascota, id_trabajador, horario, comentarios, monto,pagado) VALUES ('$tipo_servicio', '$id_mascota', '$id_trabajador','$fecha $hora', '$detalles', '$monto_servicio',0)";

if ($conn->query($sql) === TRUE) {
    header("Location: ../servicios_cliente.php");
    exit;
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}
}

$conn->close();
?>