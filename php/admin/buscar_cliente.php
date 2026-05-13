<?php
include("../crud/conexion.php");

// 1. Validar que exista el parámetro 'q'
$q = isset($_GET['q']) ? $_GET['q'] : '';

$clientes = [];

if ($q !== '') {
    // 2. Usar Prepared Statements para evitar Inyección SQL
    $termino = "%$q%";
    
    $sql = "SELECT id_persona, nombre, apellido 
            FROM persona 
            WHERE rol = 'cliente' 
            AND activo = 1 
            AND (nombre LIKE ? OR apellido LIKE ?) 
            LIMIT 10";

    $stmt = mysqli_prepare($conn, $sql);
    
    if ($stmt) {
        // "ss" significa que pasamos 2 strings
        mysqli_stmt_bind_param($stmt, "ss", $termino, $termino);
        mysqli_stmt_execute($stmt);
        
        $result = mysqli_stmt_get_result($stmt);
        
        while ($row = mysqli_fetch_assoc($result)) {
            $clientes[] = $row;
        }
        
        mysqli_stmt_close($stmt);
    }
}

// 3. Siempre devolver un JSON, incluso si está vacío
header('Content-Type: application/json');
echo json_encode($clientes);