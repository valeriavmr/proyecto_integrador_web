<?php
    require_once(__DIR__ . '/../config.php');
    require('crud/conexion.php');
    include_once('crud/consultas_varias.php');

    $lista_servicios = obtenerTiposDeServicios($conn);

    for($i = 0; $i < sizeof($lista_servicios); $i++){
        $servicio = $lista_servicios[$i];
        $ruta_imagen = obtenerRutaImagenTipoServicio($conn, $servicio['id_tipo_servicio']);
        $img_html = $ruta_imagen
            ? "<div class='service-card-img'><img src='" . htmlspecialchars($ruta_imagen) . "' alt='" . htmlspecialchars($servicio['tipo_de_servicio']) . "'></div>"
            : "";
        echo "
        <article class='service-card'>
            <a href='login.php' title='Ingresa para reservar: " . htmlspecialchars($servicio['tipo_de_servicio']) . "'>
                " . $img_html . "
                <div class='service-card-body'>
                    <h3>" . htmlspecialchars($servicio['tipo_de_servicio']) . "</h3>
                    <p>" . htmlspecialchars($servicio['descripcion']) . "</p>
                    <span class='service-link'>Ver más →</span>
                </div>
            </a>
        </article>";
    }
?>