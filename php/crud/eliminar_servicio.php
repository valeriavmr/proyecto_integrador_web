<?php
require('conexion.php');

if (session_status() == PHP_SESSION_NONE) { 
                session_start(); 
            }

if (isset($_GET['id_servicio'])) {
    $id_servicio = $_GET['id_servicio'];

    $sql = "DELETE FROM servicio WHERE id_servicio = $id_servicio";
    if ($conn->query($sql) === TRUE) {
        if($_SESSION['rol'] == 'admin'){

            header("Location: ../admin/tabla_turnos.php");

        }elseif($_SESSION['rol'] == 'cliente'){
            
            header("Location: ../servicios_cliente.php");
        }
    } else {
        echo "Error al eliminar el servicio: " . $conn->error;
    }
} else {
    echo "ID de servicio no proporcionado.";
}
$conn->close();
?>