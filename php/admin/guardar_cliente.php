<?php
include("../crud/conexion.php");

header('Content-Type: application/json');

// Leer los datos que vienen del fetch en JS
$data = json_decode(file_get_contents("php://input"), true);

// Validar que los datos no estén vacíos
if (empty($data['nombre'])) {
    echo json_encode([
        "success" => false,
        "message" => "El nombre es obligatorio"
    ]);
    exit;
}

$nombre = $data['nombre'];
$telefono = $data['telefono'] ?? ''; 

$sql = "INSERT INTO persona (nombre, telefono, rol, activo) VALUES (?, ?, 'cliente', 1)";

$stmt = mysqli_prepare($conn, $sql);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "ss", $nombre, $telefono);
    
    if (mysqli_stmt_execute($stmt)) {
        echo json_encode([
            "success" => true,
            "id_cliente" => mysqli_insert_id($conn),
            "message" => "Cliente guardado correctamente"
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Error al ejecutar el guardado: " . mysqli_error($conn)
        ]);
    }
    mysqli_stmt_close($stmt);
} else {
    echo json_encode([
        "success" => false,
        "message" => "Error en la preparación de la consulta"
    ]);
}