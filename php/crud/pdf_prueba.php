<?php

require_once __DIR__ . '/tcpdf/tcpdf.php';

$pdf = new TCPDF();         // crea instancia
$pdf->SetCreator('TCPDF');
$pdf->SetAuthor('Yuske');
$pdf->SetTitle('Prueba TCPDF');
$pdf->SetMargins(15, 20, 15);
$pdf->AddPage();

$pdf->SetFont('helvetica', '', 12);
$html = '<h1>Hola, Yuske</h1><p>TCPDF funcionando sin Composer — rápido y sin dramas.</p>';
$pdf->writeHTML($html, true, false, true, false, '');

$pdf->Output('ejemplo_tcpdf.pdf', 'I'); // 'I' = mostrar en navegador
