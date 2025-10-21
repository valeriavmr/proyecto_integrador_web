<?php
require_once('../../crud/conexion.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mascota = $_POST['mascota'] ?? '';
    $trabajador = $_POST['trabajador'] ?? '';
    $tipo_servicio = $_POST['tipo_servicio'] ?? '';
    $fecha = $_POST['fecha'] ?? '';
    $hora = $_POST['hora'] ?? '';
    $detalles = $_POST['detalles'] ?? '';

    $horario = "$fecha $hora";
    
    // Aquí puedes agregar la lógica para insertar los datos en la base de datos
    $query = "INSERT INTO servicio (tipo_de_servicio, id_mascota, id_trabajador, horario, comentarios) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("siiss", $tipo_servicio, $mascota, $trabajador, $horario, $detalles);
    $stmt->execute();

    // Redirigir o mostrar un mensaje de éxito
    header("Location: ../tabla_historico_servicios.php");
    exit();
}
?>