<?php
require_once('auth.php');

//Conecto a la base y borro la cuenta
require('../crud/conexion.php');
include_once('../crud/consultas_varias.php');

# Borro los turnos asociados a la persona
deleteTurnosPorPersonaId($conn, $_POST['id_persona']);

//Borro las mascotas asociadas a la persona
deleteMascotasPorPersonaId($conn, $_POST['id_persona']);

//Borro la cuenta con el id
if (isset($_POST['id_persona'])) {
    $id_persona = $_POST['id_persona'];
    deletePersonaPorId($conn, $id_persona);
} else {
    header('Location: tabla_personas.php?mensaje=Error: no se recibió ID');
    exit();
}

?>