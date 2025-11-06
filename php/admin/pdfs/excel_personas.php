<?php
require_once('../../crud/conexion.php');
include_once('../../crud/consultas_varias.php');
require_once('../../../config.php');

[$datos_personas, $columnas] = selectAllPersonas($conn);

$columnas = array_filter($columnas, function($col) {
    return $col !== 'acciones';
});

$nombreArchivo = "usuarios_" . date("Y-m-d") . ".csv";
header("Content-Type: text/csv; charset=UTF-8");
header("Content-Disposition: attachment; filename=\"$nombreArchivo\"");
echo "\xEF\xBB\xBF";

$separador = ";";

echo implode($separador, $columnas) . "\r\n";

foreach ($datos_personas as $fila) {
    $valores = [];
    foreach ($columnas as $col) {
        if ($col === 'password') {
            $valor = '••••••••'; // oculta la contraseña
        } else {
            $valor = isset($fila[$col]) ? str_replace(["\r", "\n"], ' ', $fila[$col]) : '';
        }
        $valor = '"' . str_replace('"', '""', $valor) . '"';
        $valores[] = $valor;
    }
    echo implode($separador, $valores) . "\r\n";
}
exit;

?>