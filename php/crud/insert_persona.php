<?php
require('conexion.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$nombre_persona= $_POST['nombre_persona'] ?? '';
$apellido_persona= $_POST['apellido_persona'] ?? '';
$username= $_POST['username'] ?? '';
$correo_persona= $_POST['correo_persona'] ?? '';
$pass= $_POST['pass'] ?? '';
$rol = $_POST['rol'] ?? '';
$tel_persona= $_POST['tel_persona'] ?? '';
$localidad= $_POST['localidad'] ?? '';
$barrio= $_POST['barrio'] ?? '';
$calle= $_POST['calle'] ?? '';
$altura_calle= $_POST['altura_calle'] ?? '';

#Encripto la password
$hashed_pass = password_hash($pass, PASSWORD_DEFAULT);

#Insert en tabla persona, logia bifurcada dependiendo de si se usa desde el portal de admin o no

if($rol==''){
    $sql_persona = "INSERT INTO persona (nombre, apellido, nombre_de_usuario, correo, password, rol, telefono) VALUES (?, ?, ?, ?, ?, 'cliente', ?)";

    $stmt = $conn->prepare($sql_persona);
    $stmt->bind_param("ssssss", $nombre_persona, $apellido_persona, $username, 
    $correo_persona, $hashed_pass, $tel_persona);
}else{
    $sql_persona = "INSERT INTO persona (nombre, apellido, nombre_de_usuario, correo, password, rol, telefono) VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql_persona);
    $stmt->bind_param("sssssss", $nombre_persona, $apellido_persona, $username, 
    $correo_persona, $hashed_pass, $rol, $tel_persona);
}

if ($stmt->execute()) {

    $last_id = $stmt->insert_id;

    include_once('consultas_varias.php');
    include_once('../admin/crud/enviar_pass_inicial.php');

    $id_sesion = obtenerIdPersona($conn,$_SESSION['username']);
    $sesion_persona = getPersonaPorId($conn, $id_sesion);
    $rol_sesion = $sesion_persona['rol'];

    if($rol_sesion=='admin'){

        $admin_sesion = obtenerTrabajadorPorId($conn, $id_sesion);

        //Envio un correo al usuario con su pass inicial
        $cuerpo_correo='<h1>Credenciales iniciales</h1>
        <p><strong>Nombre de usuario:</strong>'. $username .'</p>
        <p><strong>Contraseña:</strong>' . $pass . '</p>
        <p><strong>Adiestramiento Tahito</strong></p>';
        enviarCorreo($admin_sesion['correo_host'], $admin_sesion['pass_app'], $correo_persona, 'Credenciales Iniciales', $cuerpo_correo);
    }

    # (si es trabajador o admin) Insert en tabla trabajadores
    if($rol=='trabajador' || $rol=='admin'){
        $sql_trabajador = "INSERT INTO trabajadores (id_persona,rol) VALUES (?,?)";
        $stmt_trabajador = $conn->prepare($sql_trabajador);
        $stmt_trabajador->bind_param("is", $last_id,$rol);
        $stmt_trabajador->execute();
        $stmt_trabajador->close();
    }
    
    #Insert en tabla direccion

    $sql_direccion = "INSERT INTO direccion (id_persona, provincia, localidad, calle, altura) VALUES (?, ?, ?, ?, ?)";
    $stmt2 = $conn->prepare($sql_direccion);
    $stmt2->bind_param("isssi", $last_id, $localidad, $barrio, $calle, $altura_calle);

    if ($stmt2->execute()) {

        if($rol_sesion!='admin') {
            header("Location: ../login.php");
        }else{
            header("Location: ../admin/tabla_personas.php?mensaje=Usuario creado correctamente");
        }
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