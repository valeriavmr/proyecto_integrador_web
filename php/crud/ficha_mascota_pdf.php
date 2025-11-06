<?php
// 1. CONFIGURACIÓN INICIAL Y SEGURIDAD
ini_set('display_errors', 0);
error_reporting(E_ALL & ~E_NOTICE); 

// Uso de dirname(__FILE__) para mayor compatibilidad
require_once dirname(__FILE__). '/../../config.php';
require_once dirname(__FILE__). '/../../tcpdf/tcpdf.php';
require_once(BASE_PATH . '/php/crud/conexion.php');

session_start();

// 2. VERIFICACIÓN DE SESIÓN
if (!isset($_SESSION['username'])) {
    header('Location: ' . BASE_URL . '/php/login.php');
    exit();
}

// 3. OBTENER PARÁMETROS Y DATOS DE USUARIO
// Se actualiza el índice de la URL a 'id_mascota'
if (!isset($_GET['id_mascota'])) {
    die('ID de mascota no proporcionado.');
}

$id_mascota = intval($_GET['id_mascota']);
$username = $_SESSION['username'];

// Obtener ID y ROL del usuario logueado
$sql_user = "SELECT id_persona, rol FROM persona_g3 WHERE nombre_de_usuario = ?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param("s", $username);
$stmt_user->execute();
$result_user = $stmt_user->get_result();

if ($result_user->num_rows === 0) {
    die('Usuario no encontrado.');
}

$user_data = $result_user->fetch_assoc();
$id_usuario = $user_data['id_persona'];
$es_administrador = ($user_data['rol'] === 'admin'); // Suponiendo que 'admin' es el rol

// 4. LÓGICA DE AUTORIZACIÓN PARA CARGAR LA FICHA
if ($es_administrador) {
    // Si es administrador: Cargar CUALQUIER mascota por ID.
    $sql_mascota = "SELECT * FROM mascota_g3 WHERE id_mascota = ?";
    $stmt = $conn->prepare($sql_mascota);
    $stmt->bind_param("i", $id_mascota);
} else {
    // Si es cliente: Cargar solo sus propias mascotas.
    $sql_mascota = "SELECT * FROM mascota_g3 WHERE id_mascota = ? AND id_persona = ?";
    $stmt = $conn->prepare($sql_mascota);
    $stmt->bind_param("ii", $id_mascota, $id_usuario);
}

$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die('No se encontró la mascota o no tiene permiso para verla.');
}

$mascota = $result->fetch_assoc();


// 5. MANEJO DE IMAGEN (No requiere cambios de lógica)

$imagen_url_web = $mascota['imagen_url'] ?? ''; 
$ruta_imagen_display = ''; 

if (!empty($imagen_url_web)) {
    // Reemplazamos la URL web por la ruta física en el servidor
    $ruta_fisica = str_replace(BASE_URL, BASE_PATH, $imagen_url_web);
    
    if (file_exists($ruta_fisica)) {
        $ruta_imagen_display = $ruta_fisica; 
    }
} 

if (empty($ruta_imagen_display)) {
    // Usamos el método compatible para el default
    $ruta_imagen_display = BASE_PATH . '/img/default_pet.png';
    
    if (!file_exists($ruta_imagen_display)) {
        $ruta_imagen_display = ''; 
    }
}

$imagen_tag = ''; 

if (!empty($ruta_imagen_display)) {
    $imagen_tag = '<img src="' . $ruta_imagen_display . '" width="40mm" height="40mm" style="border: 1px solid #ccc; max-width:100%; height:auto;" />';
}


// 6. GENERACIÓN DEL PDF (TCPDF)

class MYPDF extends TCPDF {
    public function Header() {
        $this->SetFont('helvetica', 'B', 12);
        $this->Cell(0, 10, 'FICHA DE MASCOTA', 0, 1, 'C');
        $this->Ln(3);
    }
    public function Footer() {
        $this->SetY(-15);
        $this->SetFont('helvetica', 'I', 8);
        $this->Cell(0, 10, 'Página ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, 0, 'C');
    }
}

$pdf = new MYPDF();
$pdf->SetCreator('Sistema Adiestramiento Tahito');
$pdf->SetAuthor('Adiestramiento Tahito');
$pdf->SetTitle('Ficha de Mascota');
$pdf->AddPage();


$pdf->SetFont('helvetica', '', 12);

$html = '
<h2 style="color:#2E6009;">Datos de la Mascota</h2>
<style>
.celda_foto {
    text-align: center; 
    height: 45mm; 
    vertical-align: middle;
    background-color: #f9f9f9;
}
</style>

<table border="1" cellpadding="6" style="width: 100%;">
    
    <tr>
        <td style="width: 35%; font-weight: bold; background-color: #f0f0f0;">Foto de la Mascota</td>
        <td style="width: 65%;" class="celda_foto">' . $imagen_tag . '</td>
    </tr>
    
    <tr>
        <td style="width: 35%;"><strong>Nombre</strong></td>
        <td style="width: 65%;">' . htmlspecialchars($mascota['nombre']) . '</td>
    </tr>
    <tr>
        <td style="width: 35%;"><strong>Fecha de Nacimiento</strong></td>
        <td style="width: 65%;">' . htmlspecialchars($mascota['fecha_de_nacimiento']) . '</td>
    </tr>
    <tr>
        <td style="width: 35%;"><strong>Edad</strong></td>
        <td style="width: 65%;">' . htmlspecialchars($mascota['edad']) . ' años</td>
    </tr>
    <tr>
        <td style="width: 35%;"><strong>Raza</strong></td>
        <td style="width: 65%;">' . htmlspecialchars($mascota['raza']) . '</td>
    </tr>
    <tr>
        <td style="width: 35%;"><strong>Tamaño</strong></td>
        <td style="width: 65%;">' . htmlspecialchars($mascota['tamanio']) . '</td>
    </tr>
    <tr>
        <td style="width: 35%;"><strong>Color</strong></td>
        <td style="width: 65%;">' . htmlspecialchars($mascota['color']) . '</td>
    </tr>
</table>
';

$pdf->writeHTML($html, true, false, true, false, '');

// 7. SALIDA DEL PDF: 'I' (Inline/Browser) para mostrar, 'D' (Download) para forzar descarga.
// Dejamos 'I' como estabas, que permite ver y luego guardar desde el navegador.
$pdf->Output('ficha_' . $mascota['nombre'] . '.pdf', 'I');