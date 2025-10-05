<?php
require('conexion.php');

// Recibir los datos del formulario
$username = $_POST['username'];
$pass = $_POST['pass'];

#Primero busco al usuarios
<<<<<<< HEAD
$sql = "SELECT nombre_de_usuario, password FROM persona WHERE nombre_de_usuario = ?";
=======
$sql = "SELECT nombre_de_usuario, password, rol FROM persona WHERE nombre_de_usuario = ?";
>>>>>>> 83af6d2b3b41e3066e08b2b90fb992b5ed7a0a45
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();

$result = $stmt->get_result();


if ($result->num_rows > 0) {

    $row = $result->fetch_assoc();

    #Valido si la contraseña encriptada coincide
    if (password_verify($pass, $row['password'])){
    // Credenciales válidas, iniciar sesión
    session_start();
    $_SESSION['username'] = $username;
<<<<<<< HEAD
    header("Location: ../main_cliente.php");
=======

    //Recupero el rol del usuario
    $rol = $row['rol'];
    if($rol == 'cliente'){
        $_SESSION['rol'] = 'cliente';
        header("Location: ../main_cliente.php");
    }elseif($rol=='admin'){
        $_SESSION['rol'] = 'admin';
        header("Location: ../admin/main_admin.php");
    }
>>>>>>> 83af6d2b3b41e3066e08b2b90fb992b5ed7a0a45
    exit;
    }else {
    // Credenciales inválidas, redirigir al formulario de login con un mensaje de error
    header("Location: ../login.php?error=Nombre de usuario o contraseña incorrectos");
    }
}else{
    header("Location: ../login.php?error=Usuario inexistente");
    exit;
}
?>