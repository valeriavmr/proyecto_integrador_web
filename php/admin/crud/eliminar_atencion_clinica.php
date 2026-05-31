<?php
require_once('../auth.php');
require_once('../../crud/conexion.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../historia_clinica_admin.php');
    exit();
}

$id_atencion = isset($_POST['id_atencion']) ? (int) $_POST['id_atencion'] : 0;
$id_mascota = isset($_POST['id_mascota']) ? (int) $_POST['id_mascota'] : 0;

if ($id_atencion <= 0 || $id_mascota <= 0) {
    header('Location: ../historia_clinica_admin.php');
    exit();
}

/* Obtener la historia antes de borrar la atención */
$stmt = $conn->prepare("
    SELECT id_historia
    FROM atencion_clinica
    WHERE id_atencion = ?
");

$stmt->bind_param("i", $id_atencion);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header('Location: ../detalle_historia_clinica.php?id_mascota=' . $id_mascota);
    exit();
}

$row = $result->fetch_assoc();
$id_historia = (int) $row['id_historia'];
$stmt->close();

/* Borrar atención */
$stmt = $conn->prepare("
    DELETE FROM atencion_clinica
    WHERE id_atencion = ?
");

$stmt->bind_param("i", $id_atencion);
$stmt->execute();
$stmt->close();

/* Verificar si quedan atenciones en esa historia */
$stmt = $conn->prepare("
    SELECT COUNT(*) AS total
    FROM atencion_clinica
    WHERE id_historia = ?
");

$stmt->bind_param("i", $id_historia);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$total_atenciones = (int) $row['total'];
$stmt->close();

/* Si no quedan atenciones, borrar también la historia clínica */
if ($total_atenciones === 0) {
    $stmt = $conn->prepare("
        DELETE FROM historia_clinica
        WHERE id_historia = ?
    ");

    $stmt->bind_param("i", $id_historia);
    $stmt->execute();
    $stmt->close();
}

$origen = $_POST['origen'] ?? '';

if ($origen === 'historial') {
    header('Location: ../historial_atenciones_clinicas.php');
    exit();
}

header('Location: ../detalle_historia_clinica.php?id_mascota=' . $id_mascota);
exit();