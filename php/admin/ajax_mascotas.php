<?php

include '../crud/conexion.php';

header('Content-Type: application/json');

$id_persona =
    $_GET['id_persona'] ?? 0;

$sql = "

    SELECT
        id_mascota,
        nombre

    FROM mascota

    WHERE id_persona = ?

    ORDER BY nombre

";

$stmt =
    mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $id_persona
);

mysqli_stmt_execute($stmt);

$result =
    mysqli_stmt_get_result($stmt);

$mascotas = [];

while($row = mysqli_fetch_assoc($result)){

    $mascotas[] = $row;
}

echo json_encode($mascotas);