<?php
session_start();
require 'conexion.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

// Eliminar el usuario de la tabla persona
$sql = "DELETE FROM persona WHERE nombre_de_usuario = ?";
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
