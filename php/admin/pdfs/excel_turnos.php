<?php
require_once('../../crud/conexion.php');
include_once('../../crud/consultas_varias.php');
require_once('../../../config.php');

[$datos_turnos, $columnas] = selectAllServicios($conn,false);

$columnas = array_filter($columnas, function($col) {
    return $col !== 'acciones';
});

$nombreArchivo = "turnos_" . date("Y-m-d") . ".csv";
header("Content-Type: text/csv; charset=UTF-8");
header("Content-Disposition: attachment; filename=\"$nombreArchivo\"");
echo "\xEF\xBB\xBF";

$separador = ";";

echo implode($separador, $columnas) . "\r\n";

foreach ($datos_turnos as $fila) {
    $valores = [];
    foreach ($columnas as $col) {
        if ($col === 'horario' && !empty($fila[$col])) {
            // Convertimos el formato MySQL (YYYY-MM-DD HH:MM:SS) a uno legible para Excel
            $valor = date('d/m/Y H:i:s', strtotime($fila[$col]));
        }else{
            $valor = isset($fila[$col]) ? str_replace(["\r", "\n"], ' ', $fila[$col]) : '';
        }
        $valor = '"' . str_replace('"', '""', $valor) . '"';
        $valores[] = $valor;
    }
    echo implode($separador, $valores) . "\r\n";
}
exit;

?>