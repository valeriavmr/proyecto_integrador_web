<?php

if(session_status() == PHP_SESSION_NONE) {
    session_start();
}

//Recuperar el nombre de usuario de la sesión

require('conexion.php');

$usuario = $_SESSION['username'];

//Select del histórico de servicios
$sql = "SELECT * FROM servicio WHERE id_mascota IN (SELECT id_mascota FROM mascota WHERE id_persona = (SELECT id_persona FROM persona WHERE nombre_de_usuario = '$usuario'))
and horario < NOW()";
$result = $conn->query($sql);

if ($result->num_rows > 0) {

    // Mostrar los servicios pasados
    echo "<table class='tabla_servicios'>";
    echo "<tr><th>Tipo de Servicio</th><th>Fecha y Hora</th><th>Mascota</th><th>Monto</th></tr>";
    while ($turno = $result->fetch_assoc()) {

        //Buscar el nombre de la mascota
        include_once('consultas_varias.php');
        $nombre_mascota = obtenerNombreMascota($conn, $turno['id_mascota']);

        echo "<tr><td><a href='detalle_turno.php?id_servicio=".$turno['id_servicio'] . "'>" . htmlspecialchars($turno['tipo_de_servicio']) . "</a></td><td>" . htmlspecialchars($turno['horario']) . "</td><td>" 
                . htmlspecialchars($nombre_mascota) . "</td><td>". htmlspecialchars($turno['monto']) . "</td></tr>";
    }
    echo "</table>";
} else {
    echo "<br>No hay servicios pasados registrados.<br>";
}

?>