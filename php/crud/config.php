<?php

/**
 * Archivo de Configuración Principal
 * Define las rutas absolutas para que el proyecto sea portable.
 */

// 1. DEFINIR LA RUTA DE LA CARPETA DEL PROYECTO
// Esta es la parte de la ruta que viene DESPUÉS de 'htdocs'.
// Basado en tu ruta C:\xampp\htdocs\2C2025\proyecto_integrador_web
$project_folder_path = '2C2025/proyecto_integrador_web';


// 2. DEFINIR LA RUTA BASE DEL SERVIDOR (PARA INCLUDES DE PHP)
// Esto crea una ruta física en el disco duro. Es para uso interno de PHP.
// $_SERVER['DOCUMENT_ROOT'] es 'C:/xampp/htdocs'
define('BASE_PATH', $_SERVER['DOCUMENT_ROOT'] . '/' . $project_folder_path);
// En tu caso, BASE_PATH será: "C:/xampp/htdocs/2C2025/proyecto_integrador_web"


// 3. DEFINIR LA URL BASE (PARA ENLACES HTML, CSS, JS, IMÁGENES)
// Esto crea la URL que se usa en el navegador.
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
$server_name = $_SERVER['SERVER_NAME']; // Esto será 'localhost' en tu máquina
define('BASE_URL', $protocol . $server_name . '/' . $project_folder_path);
// En tu caso, BASE_URL será: "http://localhost/2C2025/proyecto_integrador_web"


?>