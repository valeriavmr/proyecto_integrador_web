<?php
require_once('../auth.php');
require_once('../../crud/conexion.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../historia_clinica_admin.php');
    exit();
}

$id_mascota = isset($_POST['id_mascota']) ? (int) $_POST['id_mascota'] : 0;
$id_historia = 0;

$id_profesional = !empty($_POST['id_profesional']) ? (int) $_POST['id_profesional'] : null;

$fecha_atencion = !empty($_POST['fecha_atencion'])
    ? $_POST['fecha_atencion'] . ' 00:00:00'
    : date('Y-m-d H:i:s');

$motivo_consulta = trim($_POST['motivo_consulta'] ?? '');
$diagnostico = trim($_POST['diagnostico'] ?? '');
$tratamiento = trim($_POST['tratamiento'] ?? '');
$observaciones = trim($_POST['observaciones'] ?? '');

if ($id_mascota <= 0) {
    header('Location: ../historia_clinica_admin.php');
    exit();
}
$stmt_hist = $conn->prepare("
    SELECT id_historia
    FROM historia_clinica
    WHERE id_mascota = ?
");

$stmt_hist->bind_param("i", $id_mascota);
$stmt_hist->execute();
$result_hist = $stmt_hist->get_result();

if ($row_hist = $result_hist->fetch_assoc()) {
    $id_historia = (int) $row_hist['id_historia'];
} else {
    $stmt_insert_hist = $conn->prepare("
        INSERT INTO historia_clinica (id_mascota, observaciones_generales)
        VALUES (?, NULL)
    ");

    $stmt_insert_hist->bind_param("i", $id_mascota);
    $stmt_insert_hist->execute();

    $id_historia = $conn->insert_id;

    $stmt_insert_hist->close();
}

$stmt_hist->close();
$sql = "
    INSERT INTO atencion_clinica (
        id_historia,
        id_profesional,
        fecha_atencion,
        motivo_consulta,
        diagnostico,
        tratamiento,
        observaciones
    )
    VALUES (?, ?, ?, ?, ?, ?, ?)
";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Error al preparar la consulta: " . $conn->error);
}

$stmt->bind_param(
    "iisssss",
    $id_historia,
    $id_profesional,
    $fecha_atencion,
    $motivo_consulta,
    $diagnostico,
    $tratamiento,
    $observaciones
);

if ($stmt->execute()) {
    header('Location: ../detalle_historia_clinica.php?id_mascota=' . $id_mascota);
    exit();
}

echo "Error al registrar la atención clínica: " . $stmt->error;

$stmt->close();
$conn->close();
