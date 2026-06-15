<?php
    require_once(__DIR__ . '/../config.php');
    require('crud/conexion.php');
    include_once('crud/consultas_varias.php');

    $lista_servicios = obtenerTiposDeServicios($conn);

    // Mapeo de imágenes locales por palabras clave en el nombre del servicio
    $base = defined('BASE_URL') ? BASE_URL : '';
    $img_map = [
        'vet'       => $base . '/uploads/img-landing-cards/veterinaria.png',
        'consul'    => $base . '/uploads/img-landing-cards/veterinaria.png',
        'peluq'     => $base . '/uploads/img-landing-cards/peluqueria-canina.png',
        'baño'      => $base . '/uploads/img-landing-cards/peluqueria-canina.png',
        'baño'      => $base . '/uploads/img-landing-cards/peluqueria-canina.png',
        'spa'       => $base . '/uploads/img-landing-cards/peluqueria-canina.png',
        'adiest'    => $base . '/uploads/img-landing-cards/adiestrador.png',
        'obedien'   => $base . '/uploads/img-landing-cards/adiestrador.png',
        'básic'     => $base . '/uploads/img-landing-cards/adiestrador.png',
        'basic'     => $base . '/uploads/img-landing-cards/adiestrador.png',
        'tienda'    => $base . '/uploads/img-landing-cards/ecomerce.png',
        'product'   => $base . '/uploads/img-landing-cards/ecomerce.png',
        'ecomc'     => $base . '/uploads/img-landing-cards/ecomerce.png',
        'producto'  => $base . '/uploads/img-landing-cards/ecomerce.png',
        'guarder'   => $base . '/uploads/img-landing-cards/ecomerce.png',
    ];

    function resolverImagenServicio($nombre, $img_map, $fallback) {
        $nombre_lower = mb_strtolower($nombre, 'UTF-8');
        foreach ($img_map as $keyword => $ruta) {
            if (str_contains($nombre_lower, $keyword)) {
                return $ruta;
            }
        }
        return $fallback; // fallback a la imagen de la base de datos
    }

    for ($i = 0; $i < sizeof($lista_servicios); $i++) {
        $servicio = $lista_servicios[$i];
        $ruta_db  = obtenerRutaImagenTipoServicio($conn, $servicio['id_tipo_servicio']);
        $img_src  = resolverImagenServicio($servicio['tipo_de_servicio'], $img_map, $ruta_db);

        $img_html = $img_src
            ? "<div class='service-card-img'><img src='" . htmlspecialchars($img_src) . "' alt='" . htmlspecialchars($servicio['tipo_de_servicio']) . "'></div>"
            : "";

        // Renombrar títulos de cards para la landing
        $nombre_lower = mb_strtolower($servicio['tipo_de_servicio'], 'UTF-8');
        $titulo_card = $servicio['tipo_de_servicio'];
        $desc_card   = $servicio['descripcion'];
        if (str_contains($nombre_lower, 'guarder')) {
            $titulo_card = 'Productos de Mascotas';
            $desc_card   = 'Encontrá los mejores productos seleccionados para su bienestar.';
        }

        echo "
        <article class='service-card'>
            <a href='login.php' title='Ingresa para reservar: " . htmlspecialchars($titulo_card) . "'>
                " . $img_html . "
                <div class='service-card-body'>
                    <h3>" . htmlspecialchars($titulo_card) . "</h3>
                    <p>" . htmlspecialchars($desc_card) . "</p>
                    <span class='service-link'>Ver más →</span>
                </div>
            </a>
        </article>";
    }
?>