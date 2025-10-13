<?php
// =================================================================
// 1. CONFIGURACIÓN DE LA BASE DE DATOS 
// =================================================================
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root'); 
define('DB_PASSWORD', ''); 
define('DB_NAME', 'proyecto_db'); 

// Directorio donde se guardarán los archivos CV en el servidor
$directorio_destino = '../uploads/cv/'; 

// Intentar conectar a MySQL
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión a la base de datos: " . $conn->connect_error);
}


// 2. PROCESAMIENTO DEL FORMULARIO

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
  
    if (!is_dir($directorio_destino)) {
        if (!mkdir($directorio_destino, 0755, true)) {
            die("Error: El directorio de destino '{$directorio_destino}' no pudo ser creado.");
        }
    }
    
    $nombre = $conn->real_escape_string(trim($_POST['nombre']));
    $apellido = $conn->real_escape_string(trim($_POST['apellido']));
    $correo = $conn->real_escape_string(trim($_POST['correo']));
    $puesto = $conn->real_escape_string(trim($_POST['puesto']));
    

    if (empty($nombre) || empty($apellido) || empty($correo) || empty($puesto)) {
        die("Error: Todos los campos de texto son obligatorios.");
    }
    
    if (isset($_FILES['cv_file']) && $_FILES['cv_file']['error'] == 0) {
        
        $cv_tmp_name = $_FILES['cv_file']['tmp_name'];
        $cv_nombre_original = $conn->real_escape_string($_FILES['cv_file']['name']);
        
 
        $extension = pathinfo($cv_nombre_original, PATHINFO_EXTENSION);
        $nombre_unico = 'cv_' . time() . '_' . uniqid() . '.' . $extension; // cv_TIMESTAMP_UNIQUEID.pdf
        
     
        $ruta_completa_servidor = $directorio_destino . $nombre_unico;

        if (move_uploaded_file($cv_tmp_name, $ruta_completa_servidor)) {
            
   
            $sql = "INSERT INTO postulaciones (nombre, apellido, correo, puesto_aplicado, cv_nombre, cv_ruta) 
                    VALUES (?, ?, ?, ?, ?, ?)";
            
    
            if ($stmt = $conn->prepare($sql)) {
                
   
                $stmt->bind_param("ssssss", 
                    $nombre, 
                    $apellido, 
                    $correo, 
                    $puesto, 
                    $cv_nombre_original, 
                    $ruta_completa_servidor
                );
                
            
                if ($stmt->execute()) {
                    echo "<h2>✅ Postulación enviada con éxito.</h2>";
                    echo "<p>Gracias por tu interés en la posición de {$puesto}.</p>";

                } else {
                    // Si falla la inserción en BD, intenta eliminar el archivo que ya se subió
                    unlink($ruta_completa_servidor); 
                    echo "<h2>❌ Error de Base de Datos.</h2>";
                    echo "<p>No se pudo guardar la información de la postulación: " . $stmt->error . "</p>";
                }
                
                $stmt->close();
                
            } else {
                echo "<h2>❌ Error interno.</h2>";
                echo "<p>Error al preparar la consulta SQL: " . $conn->error . "</p>";
            }

        } else {
            echo "<h2>❌ Error de Subida de Archivo.</h2>";
            echo "<p>Fallo al mover el CV al directorio de destino. Verifica los permisos de la carpeta: `{$directorio_destino}`.</p>";
        }

    } else {

        $error_mensaje = "Error al adjuntar el CV. ";
        switch ($_FILES['cv_file']['error']) {
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                $error_mensaje .= "El archivo excede el tamaño máximo permitido.";
                break;
            case UPLOAD_ERR_PARTIAL:
                $error_mensaje .= "El archivo fue subido solo parcialmente.";
                break;
            case UPLOAD_ERR_NO_FILE:
                $error_mensaje .= "No se seleccionó ningún archivo.";
                break;
            default:
                $error_mensaje .= "Ocurrió un error desconocido durante la subida.";
        }
        die("<h2>❌ Error de CV.</h2><p>{$error_mensaje}</p>");
    }
}

$conn->close();
?>