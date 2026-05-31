<?php

require_once('../../crud/conexion.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../tabla_proveedores.php');
    exit();
}

$id_proveedor = $_POST['id_proveedor'] ?? null;

if (!$id_proveedor) {
    session_start();
    $_SESSION['mensaje'] = "Proveedor no válido";
    header('Location: ../tabla_proveedores.php');
    exit();
}

session_start();

/* Verifico compras asociadas */
$sql_check = "SELECT COUNT(*) AS total FROM compras WHERE id_proveedor = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("i", $id_proveedor);
$stmt_check->execute();

$resultado = $stmt_check->get_result();
$fila = $resultado->fetch_assoc();

if ($fila['total'] > 0) {

    $sql = "
    UPDATE proveedores
    SET activo = 0
    WHERE id_proveedor = ?
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_proveedor);
    $stmt->execute();

    $_SESSION['mensaje'] =
        "El proveedor posee compras asociadas y fue marcado como INACTIVO.";

} else {

    $sql = "
    DELETE FROM proveedores
    WHERE id_proveedor = ?
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_proveedor);
    $stmt->execute();

    $_SESSION['mensaje'] =
        "Proveedor eliminado correctamente.";
}

header('Location: ../tabla_proveedores.php');
exit();