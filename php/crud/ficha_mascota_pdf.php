<?php

ini_set('display_errors', 0);
error_reporting(E_ALL & ~E_NOTICE); 

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../tcpdf/tcpdf.php';
require_once(BASE_PATH . '/php/crud/conexion.php');

session_start();


if (!isset($_SESSION['username'])) {
    header('Location: ' . BASE_URL . '/php/login.php');
    exit();
}


if (!isset($_GET['id'])) {
    die('ID de mascota no proporcionado.');
}

$id_mascota = intval($_GET['id']);
$username = $_SESSION['username'];


$sql_user = "SELECT id_persona FROM persona WHERE nombre_de_usuario = ?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param("s", $username);
$stmt_user->execute();
$result_user = $stmt_user->get_result();
$user_data = $result_user->fetch_assoc();
$id_usuario = $user_data['id_persona'];



$sql_mascota = "SELECT * FROM mascota WHERE id_mascota = ? AND id_persona = ?";
$stmt = $conn->prepare($sql_mascota);
$stmt->bind_param("ii", $id_mascota, $id_usuario);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die('No se encontró la mascota o no pertenece al usuario.');
}

$mascota = $result->fetch_assoc();


$imagen_url_web = $mascota['imagen_url'] ?? ''; 
$ruta_imagen_display = ''; 

if (!empty($imagen_url_web)) {
    $ruta_fisica = str_replace(BASE_URL, BASE_PATH, $imagen_url_web);
    
    if (file_exists($ruta_fisica)) {
        $ruta_imagen_display = $ruta_fisica; 
    }
} 

if (empty($ruta_imagen_display)) {
    $ruta_imagen_display = BASE_PATH . '/img/default_pet.png';
    
    if (!file_exists($ruta_imagen_display)) {
        $ruta_imagen_display = ''; 
    }
}

// SOLUCIÓN B: Inicializar la variable ANTES de la condición
$imagen_tag = ''; 

if (!empty($ruta_imagen_display)) {
    $imagen_tag = '<img src="' . $ruta_imagen_display . '" width="40mm" height="40mm" style="border: 1px solid #ccc; max-width:100%; height:auto;" />';
}


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
$pdf->Output('ficha_' . $mascota['nombre'] . '.pdf', 'I');