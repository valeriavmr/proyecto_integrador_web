<?php
require_once('../../crud/conexion.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../add_proveedor_admin.php?error=Acceso no permitido');
    exit();
}

$nombre = trim($_POST['nombre'] ?? '');
$cuit = trim($_POST['cuit'] ?? '');
$telefono = trim($_POST['telefono'] ?? '');
$correo = trim($_POST['correo'] ?? '');
$direccion = trim($_POST['direccion'] ?? '');

if ($nombre === '') {
    header('Location: ../add_proveedor_admin.php?error=El nombre del proveedor es obligatorio');
    exit();
}

$sql = "INSERT INTO proveedores 
        (nombre, cuit, telefono, correo, direccion) 
        VALUES (?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    header('Location: ../add_proveedor_admin.php?error=Error al preparar la consulta');
    exit();
}

$stmt->bind_param(
    "sssss",
    $nombre,
    $cuit,
    $telefono,
    $correo,
    $direccion
);

if ($stmt->execute()) {
    header('Location: ../tabla_proveedores.php?mensaje=Proveedor agregado correctamente');
    exit();
} else {
    header('Location: ../add_proveedor_admin.php?error=Error al guardar el proveedor');
    exit();
}