<?php
require('conexion.php');

if (isset($_GET['id_servicio'])) {
    $id_servicio = $_GET['id_servicio'];

    $sql = "DELETE FROM servicio WHERE id_servicio = $id_servicio";
    if ($conn->query($sql) === TRUE) {
        echo "Servicio eliminado exitosamente";
        header("Location: ../servicios_cliente.php");
    } else {
        echo "Error al eliminar el servicio: " . $conn->error;
    }
} else {
    echo "ID de servicio no proporcionado.";
}
$conn->close();
?>