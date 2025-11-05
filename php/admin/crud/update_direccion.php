<?php
require_once('../../crud/conexion.php');

// Recuperamos id_persona y campos de la dirección
$id_persona = $_POST['id_persona'] ?? null;
$localidad = $_POST['localidad'] ?? '';
$barrio = $_POST['barrio'] ?? '';
$calle = $_POST['calle'] ?? '';
$altura = $_POST['altura'] ?? '';

if (!$id_persona) die("Error: no se especificó la persona.");

// Preparar la query de update
$sql = "UPDATE direccion_g3 SET provincia = ?, localidad = ?, calle = ?, altura = ? WHERE id_persona = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sssii", $localidad, $barrio, $calle, $altura, $id_persona);

if ($stmt->execute()) {
    // Redirigir de vuelta al formulario de edición o donde quieras
    header("Location: ../detalle_usuario.php?id_persona=$id_persona#info_direccion");
    exit;
} else {
    die("Error al actualizar la dirección: " . $stmt->error);
}
?>