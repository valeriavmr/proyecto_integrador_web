<?php
if(session_status() == PHP_SESSION_NONE) {
    session_start();
}

require('conexion.php');

$usuario = $_SESSION['username'];

$sql = "SELECT * FROM servicio WHERE id_mascota IN (SELECT id_mascota FROM mascota WHERE id_persona = (SELECT id_persona FROM persona WHERE nombre_de_usuario = '$usuario'))
and horario >= NOW()";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Mostrar los servicios contratados
    while ($row = $result->fetch_assoc()) {

        //Buscar el nombre de la mascota
        include_once('consultas_varias.php');
        $nombre_mascota = obtenerNombreMascota($conn, $row['id_mascota']);
        echo "<article class='servicio'>
        <h3>" . $row['tipo_de_servicio'] . "</h3>
        <p>Mascota: " . $nombre_mascota . "</p>
        <p>Fecha y Hora: " . $row['horario'] . "</p>
        <button class='cancelar_turno_btn'><a href='crud/eliminar_servicio.php?id_servicio=" . $row['id_servicio'] . "'>Cancelar turno</a></button>
        </article>";
    }
} else {
    echo "<br>No hay citas pendientes en este momento.<br>";
    echo "<br><a href='solicitar_turno.php' title='Pedir cita'><img src='../recursos/add_img.png' alt='Pedir cita'></a><br>";
}

$conn->close();

?>