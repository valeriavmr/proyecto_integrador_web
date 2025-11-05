<?php
// crud/update_balance.php
require_once __DIR__ . '/../../config.php';
require_once(BASE_PATH . '/php/crud/conexion.php');

header('Content-Type: application/json');

$mes_anio = $_POST['mes_anio'] ?? '';
$tipo_servicio = $_POST['tipo_servicio'] ?? '';
$pagado = $_POST['pagado'] ?? null;

if (empty($mes_anio) || empty($tipo_servicio) || $pagado === null) {
    echo json_encode(['success' => false, 'message' => 'Datos incompletos.']);
    exit;
}

$sql = "UPDATE servicio_g3 
        SET pagado = ? 
        WHERE tipo_de_servicio = ? 
        AND DATE_FORMAT(horario, '%Y-%m') = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("iss", $pagado, $tipo_servicio, $mes_anio);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => $stmt->error]);
}
?>