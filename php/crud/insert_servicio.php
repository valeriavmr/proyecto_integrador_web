<?php
require('conexion.php');

$tipo_servicio = $_POST['tipo_servicio'];
$id_mascota = $_POST['mascota'];
$id_trabajador = $_POST['trabajador'];
$fecha = $_POST['fecha'];
$hora = $_POST['hora'];
$detalles = $_POST['detalles'];
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (empty($detalles)) {
    $detalles = "N/A";
}

$sql = "INSERT INTO servicio (tipo_de_servicio, id_mascota, id_trabajador, horario, comentarios) VALUES ('$tipo_servicio', '$id_mascota', '$id_trabajador','$fecha $hora', '$detalles')";

if ($conn->query($sql) === TRUE) {
    echo "Nuevo servicio solicitado exitosamente";
    header("Location: ../servicios_cliente.php");
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}
$conn->close();
?>