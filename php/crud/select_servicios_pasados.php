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
    echo "<tr><th>Tipo de Servicio</th><th>Fecha y Hora</th><th>Mascota</th></tr>";
    while ($row = $result->fetch_assoc()) {

        //Buscar el nombre de la mascota
        include_once('consultas_varias.php');
        $nombre_mascota = obtenerNombreMascota($conn, $row['id_mascota']);

        echo "<tr><td>" . htmlspecialchars($row['tipo_de_servicio']) . "</td><td>" . htmlspecialchars($row['horario']) . "</td><td>" . htmlspecialchars($nombre_mascota) . "</td></tr>";
    }
    echo "</table>";
} else {
    echo "<br>No hay servicios pasados registrados.<br>";
}

?>