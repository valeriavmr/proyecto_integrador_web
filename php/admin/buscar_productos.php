<?php

include("../crud/conexion.php");

header('Content-Type: application/json');


$busqueda = $_GET['q'] ?? '';


$sql = "
    SELECT *
    FROM productos
    WHERE activo = 1
    AND (

        nombre LIKE ?
        OR tipo LIKE ?

    )
    ORDER BY nombre ASC
";


$like = "%$busqueda%";


$stmt =
    mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param(
    $stmt,
    "ss",
    $like,
    $like
);

mysqli_stmt_execute($stmt);

$result =
    mysqli_stmt_get_result($stmt);


$productos = [];

while($row = mysqli_fetch_assoc($result)){

    $productos[] = $row;
}


echo json_encode($productos);