<?php
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
<h2 style="color:#003366;">Datos de la Mascota</h2>
<table border="1" cellpadding="6">
    <tr>
        <td><strong>Nombre</strong></td>
        <td>' . htmlspecialchars($mascota['nombre']) . '</td>
    </tr>
    <tr>
        <td><strong>Fecha de Nacimiento</strong></td>
        <td>' . htmlspecialchars($mascota['fecha_de_nacimiento']) . '</td>
    </tr>
    <tr>
        <td><strong>Edad</strong></td>
        <td>' . htmlspecialchars($mascota['edad']) . ' años</td>
    </tr>
    <tr>
        <td><strong>Raza</strong></td>
        <td>' . htmlspecialchars($mascota['raza']) . '</td>
    </tr>
    <tr>
        <td><strong>Tamaño</strong></td>
        <td>' . htmlspecialchars($mascota['tamanio']) . '</td>
    </tr>
    <tr>
        <td><strong>Color</strong></td>
        <td>' . htmlspecialchars($mascota['color']) . '</td>
    </tr>
</table>
';

$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Output('ficha_' . $mascota['nombre'] . '.pdf', 'I');
