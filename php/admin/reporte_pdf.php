<?php
/**
 * Generador de PDF para Reportes — Utiliza TCPDF
 * Recibe tipo de reporte y fechas por GET
 */
require_once __DIR__ . '/../../config.php';
require_once(BASE_PATH . '/php/admin/auth.php');
if ($_SESSION['rol'] !== 'admin') { header("Location: ../no_autorizado.php"); exit; }

require_once(BASE_PATH . '/php/crud/conexion.php');
require_once(BASE_PATH . '/php/crud/consultas_varias.php');
require_once(BASE_PATH . '/tcpdf/tcpdf.php');

$tipo = $_GET['tipo'] ?? '';
$desde = $_GET['desde'] ?? date('Y-m-01');
$hasta = $_GET['hasta'] ?? date('Y-m-d');

// Crear PDF
$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
$pdf->SetCreator('Tahito — Centro de Cuidado Canino');
$pdf->SetAuthor('Sistema Tahito');
$pdf->SetMargins(15, 20, 15);
$pdf->SetAutoPageBreak(true, 20);
$pdf->SetFont('helvetica', '', 10);

// Estilos comunes
$headerStyle = 'style="background-color:#14532D; color:#ffffff; font-weight:bold; padding:6px 8px; text-align:left; font-size:9px;"';
$cellStyle = 'style="padding:5px 8px; border-bottom:1px solid #E2E8F0; font-size:9px;"';
$cellMoneyStyle = 'style="padding:5px 8px; border-bottom:1px solid #E2E8F0; font-size:9px; text-align:right; font-family:courier;"';
$footerStyle = 'style="padding:6px 8px; font-weight:bold; background-color:#F4F6F8; font-size:9px;"';

switch ($tipo) {
    case 'ventas':
        $datos = getReporteVentas($conn, $desde, $hasta);
        $total = array_sum(array_column($datos, 'total'));
        
        $pdf->SetTitle('Reporte de Ventas - Tahito');
        $pdf->AddPage();
        
        // Header
        $pdf->SetFont('helvetica', 'B', 16);
        $pdf->SetTextColor(24, 24, 27);
        $pdf->Cell(0, 10, 'Reporte de Ventas', 0, 1, 'L');
        $pdf->SetFont('helvetica', '', 10);
        $pdf->SetTextColor(113, 113, 122);
        $pdf->Cell(0, 6, "Período: " . date('d/m/Y', strtotime($desde)) . " al " . date('d/m/Y', strtotime($hasta)), 0, 1);
        $pdf->Cell(0, 6, "Total: $ " . number_format($total, 2, ',', '.') . " | Cantidad: " . count($datos) . " ventas", 0, 1);
        $pdf->Ln(5);
        
        // Table
        $html = '<table cellpadding="4" cellspacing="0" border="0" width="100%">';
        $html .= "<tr><th {$headerStyle}>#</th><th {$headerStyle}>Fecha</th><th {$headerStyle}>Cliente</th><th {$headerStyle}>Productos</th><th {$headerStyle} style='text-align:right;background-color:#14532D;color:#fff;font-weight:bold;padding:6px 8px;font-size:9px;'>Total</th></tr>";
        
        foreach ($datos as $v) {
            $fecha = date('d/m/Y', strtotime($v['fecha']));
            $html .= "<tr>";
            $html .= "<td {$cellStyle}>{$v['id_venta']}</td>";
            $html .= "<td {$cellStyle}>{$fecha}</td>";
            $html .= "<td {$cellStyle}>" . htmlspecialchars($v['cliente']) . "</td>";
            $html .= "<td {$cellStyle}>" . htmlspecialchars($v['productos']) . "</td>";
            $html .= "<td {$cellMoneyStyle}>$ " . number_format($v['total'], 2, ',', '.') . "</td>";
            $html .= "</tr>";
        }
        
        $html .= "<tr><td colspan='4' {$footerStyle}>TOTAL</td><td {$footerStyle} style='text-align:right;padding:6px 8px;font-weight:bold;background-color:#F4F6F8;font-size:9px;'>$ " . number_format($total, 2, ',', '.') . "</td></tr>";
        $html .= '</table>';
        
        $pdf->writeHTML($html, true, false, true, false, '');
        break;

    case 'servicios':
        $datos = getReporteServicios($conn, $desde, $hasta);
        
        $pdf->SetTitle('Reporte de Servicios - Tahito');
        $pdf->AddPage();
        
        $pdf->SetFont('helvetica', 'B', 16);
        $pdf->SetTextColor(24, 24, 27);
        $pdf->Cell(0, 10, 'Reporte de Servicios', 0, 1, 'L');
        $pdf->SetFont('helvetica', '', 10);
        $pdf->SetTextColor(113, 113, 122);
        $pdf->Cell(0, 6, "Período: " . date('d/m/Y', strtotime($desde)) . " al " . date('d/m/Y', strtotime($hasta)), 0, 1);
        $pdf->Ln(5);
        
        $html = '<table cellpadding="4" cellspacing="0" border="0" width="100%">';
        $html .= "<tr><th {$headerStyle}>Tipo de Servicio</th><th {$headerStyle} style='text-align:center;background-color:#14532D;color:#fff;font-weight:bold;padding:6px 8px;font-size:9px;'>Turnos</th><th {$headerStyle} style='text-align:center;background-color:#14532D;color:#fff;font-weight:bold;padding:6px 8px;font-size:9px;'>Pagados</th><th {$headerStyle} style='text-align:center;background-color:#14532D;color:#fff;font-weight:bold;padding:6px 8px;font-size:9px;'>Pendientes</th><th {$headerStyle} style='text-align:right;background-color:#14532D;color:#fff;font-weight:bold;padding:6px 8px;font-size:9px;'>Cobrado</th><th {$headerStyle} style='text-align:right;background-color:#14532D;color:#fff;font-weight:bold;padding:6px 8px;font-size:9px;'>Total</th></tr>";
        
        $sum_turnos = $sum_cobrado = $sum_total = 0;
        foreach ($datos as $s) {
            $sum_turnos += $s['total_turnos'];
            $sum_cobrado += floatval($s['ingresos_cobrados']);
            $sum_total += floatval($s['ingresos_total']);
            
            $html .= "<tr>";
            $html .= "<td {$cellStyle}><strong>" . htmlspecialchars($s['tipo_de_servicio']) . "</strong></td>";
            $html .= "<td {$cellStyle} style='text-align:center;padding:5px 8px;border-bottom:1px solid #E2E8F0;font-size:9px;'>{$s['total_turnos']}</td>";
            $html .= "<td {$cellStyle} style='text-align:center;padding:5px 8px;border-bottom:1px solid #E2E8F0;font-size:9px;'>{$s['turnos_pagados']}</td>";
            $html .= "<td {$cellStyle} style='text-align:center;padding:5px 8px;border-bottom:1px solid #E2E8F0;font-size:9px;'>{$s['turnos_no_pagados']}</td>";
            $html .= "<td {$cellMoneyStyle}>$ " . number_format($s['ingresos_cobrados'], 2, ',', '.') . "</td>";
            $html .= "<td {$cellMoneyStyle}>$ " . number_format($s['ingresos_total'], 2, ',', '.') . "</td>";
            $html .= "</tr>";
        }
        
        $html .= "<tr><td {$footerStyle}>TOTAL</td><td {$footerStyle} style='text-align:center;padding:6px 8px;font-weight:bold;background-color:#F4F6F8;font-size:9px;'>{$sum_turnos}</td><td {$footerStyle}></td><td {$footerStyle}></td><td {$footerStyle} style='text-align:right;padding:6px 8px;font-weight:bold;background-color:#F4F6F8;font-size:9px;'>$ " . number_format($sum_cobrado, 2, ',', '.') . "</td><td {$footerStyle} style='text-align:right;padding:6px 8px;font-weight:bold;background-color:#F4F6F8;font-size:9px;'>$ " . number_format($sum_total, 2, ',', '.') . "</td></tr>";
        $html .= '</table>';
        
        $pdf->writeHTML($html, true, false, true, false, '');
        break;

    case 'compras':
        $datos = getReporteCompras($conn, $desde, $hasta);
        $total = array_sum(array_column($datos, 'total'));
        
        $pdf->SetTitle('Reporte de Compras - Tahito');
        $pdf->AddPage();
        
        $pdf->SetFont('helvetica', 'B', 16);
        $pdf->SetTextColor(24, 24, 27);
        $pdf->Cell(0, 10, 'Reporte de Compras a Proveedores', 0, 1, 'L');
        $pdf->SetFont('helvetica', '', 10);
        $pdf->SetTextColor(113, 113, 122);
        $pdf->Cell(0, 6, "Período: " . date('d/m/Y', strtotime($desde)) . " al " . date('d/m/Y', strtotime($hasta)), 0, 1);
        $pdf->Cell(0, 6, "Total gastado: $ " . number_format($total, 2, ',', '.'), 0, 1);
        $pdf->Ln(5);
        
        $html = '<table cellpadding="4" cellspacing="0" border="0" width="100%">';
        $html .= "<tr><th {$headerStyle}>Fecha</th><th {$headerStyle}>Proveedor</th><th {$headerStyle}>Detalle</th><th {$headerStyle} style='text-align:right;background-color:#14532D;color:#fff;font-weight:bold;padding:6px 8px;font-size:9px;'>Total</th></tr>";
        
        foreach ($datos as $c) {
            $fecha = date('d/m/Y', strtotime($c['fecha_compra']));
            $html .= "<tr>";
            $html .= "<td {$cellStyle}>{$fecha}</td>";
            $html .= "<td {$cellStyle}><strong>" . htmlspecialchars($c['proveedor']) . "</strong></td>";
            $html .= "<td {$cellStyle} style='font-size:8px;padding:5px 8px;border-bottom:1px solid #E2E8F0;'>" . htmlspecialchars($c['detalle'] ?? '-') . "</td>";
            $html .= "<td {$cellMoneyStyle}>$ " . number_format($c['total'], 2, ',', '.') . "</td>";
            $html .= "</tr>";
        }
        
        $html .= "<tr><td colspan='3' {$footerStyle}>TOTAL</td><td {$footerStyle} style='text-align:right;padding:6px 8px;font-weight:bold;background-color:#F4F6F8;font-size:9px;'>$ " . number_format($total, 2, ',', '.') . "</td></tr>";
        $html .= '</table>';
        
        $pdf->writeHTML($html, true, false, true, false, '');
        break;

    case 'productos_top':
        $datos = getProductosTopVendidos($conn, $desde, $hasta, 15);
        $total_monto = array_sum(array_column($datos, 'monto_total'));
        $total_unidades = array_sum(array_column($datos, 'cantidad_vendida'));
        
        $pdf->SetTitle('Productos Más Vendidos - Tahito');
        $pdf->AddPage();
        
        $pdf->SetFont('helvetica', 'B', 16);
        $pdf->SetTextColor(24, 24, 27);
        $pdf->Cell(0, 10, 'Productos Más Vendidos', 0, 1, 'L');
        $pdf->SetFont('helvetica', '', 10);
        $pdf->SetTextColor(113, 113, 122);
        $pdf->Cell(0, 6, "Período: " . date('d/m/Y', strtotime($desde)) . " al " . date('d/m/Y', strtotime($hasta)), 0, 1);
        $pdf->Cell(0, 6, "Total: $ " . number_format($total_monto, 2, ',', '.') . " | " . $total_unidades . " unidades", 0, 1);
        $pdf->Ln(5);
        
        $html = '<table cellpadding="4" cellspacing="0" border="0" width="100%">';
        $html .= "<tr><th {$headerStyle}>#</th><th {$headerStyle}>Producto</th><th {$headerStyle} style='text-align:center;background-color:#14532D;color:#fff;font-weight:bold;padding:6px 8px;font-size:9px;'>Unidades</th><th {$headerStyle} style='text-align:center;background-color:#14532D;color:#fff;font-weight:bold;padding:6px 8px;font-size:9px;'>Ventas</th><th {$headerStyle} style='text-align:right;background-color:#14532D;color:#fff;font-weight:bold;padding:6px 8px;font-size:9px;'>Monto Total</th></tr>";
        
        foreach ($datos as $i => $p) {
            $pos = $i + 1;
            $html .= "<tr>";
            $html .= "<td {$cellStyle} style='text-align:center;padding:5px 8px;border-bottom:1px solid #E2E8F0;font-size:9px;'>{$pos}</td>";
            $html .= "<td {$cellStyle}><strong>" . htmlspecialchars($p['nombre_producto']) . "</strong></td>";
            $html .= "<td {$cellStyle} style='text-align:center;padding:5px 8px;border-bottom:1px solid #E2E8F0;font-size:9px;'>{$p['cantidad_vendida']}</td>";
            $html .= "<td {$cellStyle} style='text-align:center;padding:5px 8px;border-bottom:1px solid #E2E8F0;font-size:9px;'>{$p['num_ventas']}</td>";
            $html .= "<td {$cellMoneyStyle}>$ " . number_format($p['monto_total'], 2, ',', '.') . "</td>";
            $html .= "</tr>";
        }
        
        $html .= "<tr><td colspan='2' {$footerStyle}>TOTAL</td><td {$footerStyle} style='text-align:center;padding:6px 8px;font-weight:bold;background-color:#F4F6F8;font-size:9px;'>{$total_unidades}</td><td {$footerStyle}></td><td {$footerStyle} style='text-align:right;padding:6px 8px;font-weight:bold;background-color:#F4F6F8;font-size:9px;'>$ " . number_format($total_monto, 2, ',', '.') . "</td></tr>";
        $html .= '</table>';
        
        $pdf->writeHTML($html, true, false, true, false, '');
        break;

    default:
        $pdf->AddPage();
        $pdf->SetFont('helvetica', 'B', 14);
        $pdf->Cell(0, 10, 'Tipo de reporte no válido', 0, 1, 'C');
}

// Footer con marca de agua
$pdf->SetY(-15);
$pdf->SetFont('helvetica', 'I', 8);
$pdf->SetTextColor(180, 180, 180);
$pdf->Cell(0, 10, 'Generado por Sistema Tahito — ' . date('d/m/Y H:i'), 0, 0, 'C');

// Output
$nombre = 'reporte_' . $tipo . '_' . $desde . '_' . $hasta . '.pdf';
$pdf->Output($nombre, 'I');
?>
