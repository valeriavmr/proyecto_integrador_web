<?php
    require('crud/conexion.php');
    include_once('crud/consultas_varias.php');

    $lista_servicios = obtenerTiposDeServicios($conn);


for($i=0;$i<sizeof($lista_servicios);$i++){
    $servicio = $lista_servicios[$i];
    $ruta_imagen = obtenerRutaImagenTipoServicio($conn, $servicio['id_tipo_servicio'],"proyecto_adiestramiento_tahito");
    echo "<article>
    <a href='login.php' title='Ingresa para más información sobre " . $servicio['tipo_de_servicio'] . "'>
    <h3>" . $servicio['tipo_de_servicio'] . "</h3><br>
    <p>" . $servicio['descripcion'] . "</p><br>
    <img src=" . $ruta_imagen . "></a>
    </article>";
}
?>