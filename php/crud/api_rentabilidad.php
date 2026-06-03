<?php
// API de Rentabilidad — Devuelve JSON para gráficos y tabla
require_once __DIR__ . '/../../config.php';
require_once(BASE_PATH . '/php/crud/conexion.php');
require_once(BASE_PATH . '/php/crud/consultas_varias.php');

if (session_status() == PHP_SESSION_NONE) session_start();

// Solo admin
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['error' => 'No autorizado']);
    exit;
}

header('Content-Type: application/json; charset=utf-8');

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $action = $_GET['action'] ?? 'datos';
    
    if ($action === 'datos') {
        $anio = isset($_GET['anio']) ? intval($_GET['anio']) : intval(date('Y'));
        $datos = getRentabilidadMensual($conn, $anio);
        $anios = getAniosConDatos($conn);
        $kpis = getKPIsMesActual($conn);
        
        echo json_encode([
            'success' => true,
            'anio' => $anio,
            'anios_disponibles' => $anios,
            'kpis' => $kpis,
            'datos' => $datos
        ]);
    } elseif ($action === 'anios') {
        echo json_encode([
            'success' => true,
            'anios' => getAniosConDatos($conn)
        ]);
    }

} elseif ($method === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input) {
        $input = $_POST;
    }
    
    $anio = intval($input['anio'] ?? 0);
    $mes = intval($input['mes'] ?? 0);
    $sueldos = floatval($input['costo_sueldos'] ?? 0);
    $otros = floatval($input['costo_otros'] ?? 0);
    $notas = $input['notas'] ?? '';
    
    if ($anio < 2020 || $anio > 2099 || $mes < 1 || $mes > 12) {
        echo json_encode(['success' => false, 'message' => 'Período inválido.']);
        exit;
    }
    
    $ok = guardarCostosManuales($conn, $anio, $mes, $sueldos, $otros, $notas);
    
    echo json_encode([
        'success' => $ok,
        'message' => $ok ? 'Costos guardados correctamente.' : 'Error al guardar los costos.'
    ]);
}
?>
