<?php

/**
 * Archivo de Configuración Principal
 * Define las rutas absolutas para que el proyecto sea portable.
 * 
 * Funciona tanto con XAMPP/Apache como con el servidor de desarrollo `php -S`.
 */

// 1. BASE_PATH — Ruta física en disco a la raíz del proyecto
//    __DIR__ en config.php siempre apunta al directorio donde está este archivo,
//    sin importar desde dónde se llame al script. Esto lo hace 100% portable.
define('BASE_PATH', str_replace('\\', '/', __DIR__));


// 2. BASE_URL — URL accesible desde el navegador
$protocol   = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
$server_name = $_SERVER['SERVER_NAME'] ?? 'localhost'; 
$port        = $_SERVER['SERVER_PORT'] ?? 80;

// Incluye el puerto sólo cuando no es el estándar (80/443)
$port_str = (!in_array((int)$port, [80, 443])) ? ':' . $port : '';

// Al servir con `php -S` desde la raíz del proyecto, la URL base es simplemente el host.
// Al servir con XAMPP, se detecta el subfolder del proyecto automáticamente.
$doc_root    = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT'] ?? '');
$project_dir = str_replace('\\', '/', __DIR__);

if ($doc_root && strpos($project_dir, $doc_root) === 0) {
    // Caso XAMPP: el proyecto está dentro de htdocs
    $subfolder = ltrim(str_replace($doc_root, '', $project_dir), '/');
    $base_url_path = $subfolder ? '/' . $subfolder : '';
} else {
    // Caso `php -S localhost:8000`: el doc_root ES el proyecto
    $base_url_path = '';
}

define('BASE_URL', $protocol . $server_name . $port_str . $base_url_path);


// 3. Credenciales de correo
define('CORREO_HOST', 'grupos@serviciosya.com.ar');
define('PASS_HOST',   'B6UVDn@3pX');
?>