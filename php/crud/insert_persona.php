<?php
require('conexion.php');

$nombre_persona = $_POST['nombre_persona'];
$apellido_persona = $_POST['apellido_persona'];
$username = $_POST['username'];
$correo_persona = $_POST['correo_persona'];
$pass = $_POST['pass'];
$tel_persona = $_POST['tel_persona'];
$localidad = $_POST['localidad'];
$barrio = $_POST['barrio'];
$calle = $_POST['calle'];
$altura_calle = $_POST['altura_calle'];

include_once('consultas_varias.php');

#Verificaciones de unicidad
if(verificarNombreUsuario($conn, $username)) {
    echo "El nombre de usuario ya existe. Por favor, elija otro.";
    exit;
}

if(verificarCorreo($conn, $correo_persona)) {
    echo "El correo ya está registrado. Por favor, utilice otro.";
    exit;
}

#Insert en tabla persona

$sql_persona = "INSERT INTO persona (nombre, apellido, nombre_de_usuario, correo, password, rol, telefono) VALUES ('$nombre_persona', '$apellido_persona', '$username', '$correo_persona', '$pass', 'cliente', '$tel_persona')";

if ($conn->query($sql_persona) === TRUE) {
    echo "Nuevo registro creado exitosamente";

    
    #Insert en tabla direccion

    $sql_direccion = "INSERT INTO direccion (id_persona, provincia, localidad, calle, altura) VALUES (LAST_INSERT_ID(), '$localidad', '$barrio', '$calle', '$altura_calle')";

    if ($conn->query($sql_direccion) === TRUE) {
        echo "Nuevo registro creado exitosamente";
    } else {
        echo "Error: " . $sql_direccion . "<br>" . $conn->error;
    }
} else {
    echo "Error: " . $sql_persona . "<br>" . $conn->error;
}

$conn->close();

header("Location: ../login.php");

?>