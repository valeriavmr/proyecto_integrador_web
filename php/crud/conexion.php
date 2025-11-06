<?php
//Locales
$host = "localhost";
$dbname = "proyecto_db";
$user = "root";
$password ="";

//Hosting
/*$host = "localhost";
$dbname = "c2720913_bolsa";
$user = "c2720913_bolsa";
$password ="ra92vopaLI";*/

#conectar usando mysqli
$conn = new mysqli($host,$user,$password,$dbname);

$conn->set_charset("utf8");

#valido si se conecto
if($conn->connect_errno){
    echo "Fallo la conexion. Error: " . $conn->connect_errno . $conn->connect_error . "<br>";
}else{
    #echo "Conexión exitosa" . "<br>";
}

?>