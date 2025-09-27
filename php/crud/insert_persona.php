<?php
require('conexion.php');

$nombre_persona= $_POST['nombre_persona'] ?? '';
$apellido_persona= $_POST['apellido_persona'] ?? '';
$username= $_POST['username'] ?? '';
$correo_persona= $_POST['correo_persona'] ?? '';
$pass= $_POST['pass'] ?? '';
$tel_persona= $_POST['tel_persona'] ?? '';
$localidad= $_POST['localidad'] ?? '';
$barrio= $_POST['barrio'] ?? '';
$calle= $_POST['calle'] ?? '';
$altura_calle= $_POST['altura_calle'] ?? '';

#Encripto la password
$hashed_pass = password_hash($pass, PASSWORD_DEFAULT);

#Insert en tabla persona

$sql_persona = "INSERT INTO persona (nombre, apellido, nombre_de_usuario, correo, password, rol, telefono) VALUES (?, ?, ?, ?, ?, 'cliente', ?)";

$stmt = $conn->prepare($sql_persona);
$stmt->bind_param("ssssss", $nombre_persona, $apellido_persona, $username, 
$correo_persona, $hashed_pass, $tel_persona);

if ($stmt->execute()) {

    $last_id = $stmt->insert_id;
    
    #Insert en tabla direccion

    $sql_direccion = "INSERT INTO direccion (id_persona, provincia, localidad, calle, altura) VALUES (?, ?, ?, ?, ?)";
    $stmt2 = $conn->prepare($sql_direccion);
    $stmt2->bind_param("isssi", $last_id, $localidad, $barrio, $calle, $altura_calle);

    if ($stmt2->execute()) {
        header("Location: ../login.php");
    } else {
        echo "Error: " . $sql_direccion . "<br>" . $conn->error;
    }
    $stmt2->close();
} else {
    echo "Error: " . $sql_persona . "<br>" . $conn->error;
}

$stmt->close();
$conn->close();
exit;

?>