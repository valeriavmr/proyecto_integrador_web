<?php
session_start();
require 'conexion.php';

include_once 'consultas_varias.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

$id_persona = obtenerIdPersona($conn,$username);

# Borro los turnos asociados a la persona
deleteTurnosPorPersonaId($conn, $id_persona);

//Borro las mascotas asociadas a la persona
deleteMascotasPorPersonaId($conn, $id_persona);

//Borro la direccion
deleteDireccionPorId($conn, $id_persona);

//Borro en la tabla de trabajadores
deleteTrabajadorPorId($conn,$id_persona);

// Eliminar el usuario de la tabla persona
$sql = "DELETE FROM persona_g3 WHERE nombre_de_usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);

if ($stmt->execute()) {
    session_unset();
    session_destroy();
    header("Location: ../main_guest.php"); // Redirige al visitante
    exit();
} else {
    echo "Error al eliminar la cuenta.";
}
?>
