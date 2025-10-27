<?php

require_once('../../crud/conexion.php');

//Recupero los valores del form
$id_persona = $_POST['id_persona'] ?? null;
$pass_app = $_POST['pass_app'] ?? null;
$tipo_de_servicio = $_POST['tipo_de_servicio'] ?? null;

if (!$id_persona) die("Error: no se especificó la persona.");

//Creo el update
$sql = 'UPDATE trabajadores SET pass_app = ?, tipo_de_servicio = ? WHERE id_persona = ?';

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssi", $pass_app,$tipo_de_servicio, $id_persona);

if ($stmt->execute()) {
    // Redirigir de vuelta al formulario de edición o donde quieras
    header("Location: ../detalle_usuario.php?id_persona=$id_persona#info_trabajador");
    exit;
} else {
    die("Error al actualizar la información de trabajador: " . $stmt->error);
}

?>