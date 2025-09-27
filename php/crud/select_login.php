<?php
require('conexion.php');

// Recibir los datos del formulario
$username = $_POST['username'];
$pass = $_POST['pass'];

#Primero busco al usuarios
$sql = "SELECT nombre_de_usuario, password FROM persona WHERE nombre_de_usuario = ?";
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
    header("Location: ../main_cliente.php");
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