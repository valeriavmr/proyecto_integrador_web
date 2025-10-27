<?php
require_once('../auth.php');
require_once('../../../config.php');

//Conecto a la base y borro la cuenta
require('../../crud/conexion.php');
include_once('../../crud/consultas_varias.php');

//Borro la cuenta con el id
if (isset($_POST['id_persona'])) {
    $id_persona = $_POST['id_persona'];

    # Borro los turnos asociados a la persona
    deleteTurnosPorPersonaId($conn, $id_persona);

    //Borro las mascotas asociadas a la persona
    deleteMascotasPorPersonaId($conn, $id_persona);

    //Borro la direccion
    deleteDireccionPorId($conn, $id_persona);

    //Borro en la tabla de trabajadores
    deleteTrabajadorPorId($conn,$id_persona);

    //Borro la persona
    deletePersonaPorId($conn, $id_persona);

    exit();
} else {
    header('Location: ' . BASE_URL . '/php/admin/tabla_personas.php?mensaje=Error: no se recibió ID');
    exit();
}

?>