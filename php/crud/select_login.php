<?php
require('conexion.php');

// Recibir los datos del formulario
$username = $_POST['username'];
$pass = $_POST['pass'];

// Consulta para verificar las credenciales
$sql = "SELECT * FROM persona WHERE nombre_de_usuario = '$username' AND password = '$pass'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Credenciales válidas, iniciar sesión
    session_start();
    $_SESSION['username'] = $username;
    header("Location: ../main_cliente.php");
    exit;
} else {
    // Credenciales inválidas, redirigir al formulario de login con un mensaje de error
    echo "Nombre de usuario o contraseña incorrectos.<br>";
}
?>