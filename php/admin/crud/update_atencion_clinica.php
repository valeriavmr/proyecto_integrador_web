<?php
require_once('../auth.php');
require_once('../../crud/conexion.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../historia_clinica_admin.php');
    exit();
}

$id_atencion = isset($_POST['id_atencion']) ? (int) $_POST['id_atencion'] : 0;
$id_mascota = isset($_POST['id_mascota']) ? (int) $_POST['id_mascota'] : 0;

$id_profesional = !empty($_POST['id_profesional']) ? (int) $_POST['id_profesional'] : null;

$fecha_atencion = !empty($_POST['fecha_atencion'])
    ? $_POST['fecha_atencion'] . ' 00:00:00'
    : date('Y-m-d H:i:s');

$motivo_consulta = trim($_POST['motivo_consulta'] ?? '');
$diagnostico = trim($_POST['diagnostico'] ?? '');
$tratamiento = trim($_POST['tratamiento'] ?? '');
$observaciones = trim($_POST['observaciones'] ?? '');

if ($id_atencion <= 0 || $id_mascota <= 0) {
    header('Location: ../historia_clinica_admin.php');
    exit();
}

$sql = "
    UPDATE atencion_clinica
    SET
        id_profesional = ?,
        fecha_atencion = ?,
        motivo_consulta = ?,
        diagnostico = ?,
        tratamiento = ?,
        observaciones = ?
    WHERE id_atencion = ?
";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Error al preparar la consulta: " . $conn->error);
}

$stmt->bind_param(
    "isssssi",
    $id_profesional,
    $fecha_atencion,
    $motivo_consulta,
    $diagnostico,
    $tratamiento,
    $observaciones,
    $id_atencion
);

if ($stmt->execute()) {
    header('Location: ../detalle_historia_clinica.php?id_mascota=' . $id_mascota);
    exit();
}

echo "Error al actualizar la atención clínica: " . $stmt->error;

$stmt->close();
$conn->close();