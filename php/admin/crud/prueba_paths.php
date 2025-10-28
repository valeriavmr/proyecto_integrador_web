<?php
echo '<pre>';
echo "Estoy en: " . __FILE__ . "\n";
echo "Directorio actual: " . getcwd() . "\n";
echo "Subir 1 nivel: " . realpath('../') . "\n";
echo "Subir 2 niveles: " . realpath('../../') . "\n";
echo "Subir 3 niveles: " . realpath('../../../') . "\n";
echo "Subir 4 niveles: " . realpath('../../../../') . "\n";
echo '</pre>';
?>