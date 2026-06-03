<?php
// API de Reportes — Devuelve JSON para los sub-reportes
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

$tipo = $_GET['tipo'] ?? '';
$desde = $_GET['desde'] ?? date('Y-m-01');
$hasta = $_GET['hasta'] ?? date('Y-m-d');

switch ($tipo) {
    case 'ventas':
        $datos = getReporteVentas($conn, $desde, $hasta);
        $total = array_sum(array_column($datos, 'total'));
        echo json_encode([
            'success' => true,
            'tipo' => 'ventas',
            'desde' => $desde,
            'hasta' => $hasta,
            'total' => $total,
            'count' => count($datos),
            'datos' => $datos
        ]);
        break;
        
    case 'servicios':
        $datos = getReporteServicios($conn, $desde, $hasta);
        echo json_encode([
            'success' => true,
            'tipo' => 'servicios',
            'desde' => $desde,
            'hasta' => $hasta,
            'datos' => $datos
        ]);
        break;
        
    case 'compras':
        $datos = getReporteCompras($conn, $desde, $hasta);
        $total = array_sum(array_column($datos, 'total'));
        echo json_encode([
            'success' => true,
            'tipo' => 'compras',
            'desde' => $desde,
            'hasta' => $hasta,
            'total' => $total,
            'count' => count($datos),
            'datos' => $datos
        ]);
        break;
        
    case 'productos_top':
        $limit = intval($_GET['limit'] ?? 10);
        $datos = getProductosTopVendidos($conn, $desde, $hasta, $limit);
        echo json_encode([
            'success' => true,
            'tipo' => 'productos_top',
            'desde' => $desde,
            'hasta' => $hasta,
            'datos' => $datos
        ]);
        break;
        
    default:
        echo json_encode([
            'success' => false,
            'message' => 'Tipo de reporte no válido. Use: ventas, servicios, compras, productos_top'
        ]);
}
?>
