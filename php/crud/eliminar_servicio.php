<?php
require('conexion.php');

<<<<<<< HEAD
=======
if (session_status() == PHP_SESSION_NONE) { 
                session_start(); 
            }

>>>>>>> 83af6d2b3b41e3066e08b2b90fb992b5ed7a0a45
if (isset($_GET['id_servicio'])) {
    $id_servicio = $_GET['id_servicio'];

    $sql = "DELETE FROM servicio WHERE id_servicio = $id_servicio";
    if ($conn->query($sql) === TRUE) {
<<<<<<< HEAD
        echo "Servicio eliminado exitosamente";
        header("Location: ../servicios_cliente.php");
=======
        if($_SESSION['rol'] == 'admin'){

            header("Location: ../admin/tabla_turnos.php");

        }elseif($_SESSION['rol'] == 'cliente'){
            
            header("Location: ../servicios_cliente.php");
        }
>>>>>>> 83af6d2b3b41e3066e08b2b90fb992b5ed7a0a45
    } else {
        echo "Error al eliminar el servicio: " . $conn->error;
    }
} else {
    echo "ID de servicio no proporcionado.";
}
$conn->close();
?>