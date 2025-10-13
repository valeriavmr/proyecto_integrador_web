<?php
require('../../crud/conexion.php');
include_once('../../crud/consultas_varias.php');

if (session_status() == PHP_SESSION_NONE) { 
                session_start(); 
            }

// Traigo los datos enviados
$id_persona = $_POST['id_persona'] ?? $_GET['id_persona'] ?? null;
$nombre = trim($_POST['nombre'] ?? '');
$apellido = trim($_POST['apellido'] ?? '');
$username = trim($_POST['username'] ?? '');
$correo = trim($_POST['correo'] ?? '');
$rol = trim($_POST['rol'] ?? '');
$tel = trim($_POST['tel'] ?? '');

// Verifico que haya ID
if (!$id_persona) {
    die("Error: Falta el ID de la persona.");
}

//hago el UPDATE
$sql = "UPDATE persona 
        SET nombre = ?, apellido = ?, nombre_de_usuario = ?, correo = ?, rol = ?, telefono = ?
        WHERE id_persona = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssssi", $nombre, $apellido, $username, $correo, $rol, $tel, $id_persona);

if ($stmt->execute()) {
    $_SESSION['mensaje_exito'] = "Cambios guardados correctamente.";
} else {
    $_SESSION['mensaje_error'] = "Error al guardar los cambios.";
}

// Redirijo nuevamente al formulario para mostrar mensajes
header("Location: ../editar_usuario.php?id_persona=$id_persona");
exit;

?>