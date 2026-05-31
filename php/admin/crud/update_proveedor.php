<?php

require_once('../../crud/conexion.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../tabla_proveedores.php');
    exit();
}

$id_proveedor = $_POST['id_proveedor'] ?? null;
$nombre = trim($_POST['nombre'] ?? '');
$cuit = trim($_POST['cuit'] ?? '');
$telefono = trim($_POST['telefono'] ?? '');
$correo = trim($_POST['correo'] ?? '');
$direccion = trim($_POST['direccion'] ?? '');
$activo = $_POST['activo'] ?? 1;

if (!$id_proveedor || empty($nombre)) {
    header('Location: ../tabla_proveedores.php?error=Datos inválidos');
    exit();
}

$sql = "
UPDATE proveedores
SET
    nombre = ?,
    cuit = ?,
    telefono = ?,
    correo = ?,
    direccion = ?,
    activo = ?
WHERE id_proveedor = ?
";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "sssssii",
    $nombre,
    $cuit,
    $telefono,
    $correo,
    $direccion,
    $activo,
    $id_proveedor
);

if ($stmt->execute()) {

    session_start();
    $_SESSION['mensaje'] = "Proveedor actualizado correctamente";

    header('Location: ../tabla_proveedores.php');
    exit();
}

session_start();
$_SESSION['mensaje'] = "Error al actualizar proveedor";

header('Location: ../tabla_proveedores.php');
exit();