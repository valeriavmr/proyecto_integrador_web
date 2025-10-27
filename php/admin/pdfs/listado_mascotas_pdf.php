<?php
require_once __DIR__ . '/../../../config.php';
require_once __DIR__ . '/../../../tcpdf/tcpdf.php';
require_once(BASE_PATH . '/php/crud/conexion.php');

// --- Consulta todas las mascotas con dueño ---
$sql = "
    SELECT
        m.id_mascota,
        m.nombre AS nombre_mascota,
        m.fecha_de_nacimiento,
        m.edad,
        m.raza,
        m.tamanio,
        m.color,
        p.nombre_de_usuario AS duenio
    FROM mascota m
    INNER JOIN persona p ON m.id_persona = p.id_persona
    ORDER BY p.nombre_de_usuario ASC, m.nombre ASC
";

$result = $conn->query($sql);

if (!$result) {
    die("❌ Error en la consulta SQL: " . $conn->error);
}

if ($result->num_rows === 0) {
    die('No se encontraron mascotas en la base de datos.');
}

// --- Configuración de TCPDF ---
class MYPDF extends TCPDF {

    // Altura aproximada de tu logo y título
    const HEADER_HEIGHT = 30; // Ajusta este valor según el espacio que necesites para el logo y el título

    public function Header() {
        // Logo
        $image_file = __DIR__ . '/../../../recursos/logsinfondo.png';
        if (file_exists($image_file)) {
            // Ajuste de las coordenadas y el tamaño del logo para que no choque con el margen superior
            $this->Image($image_file, 10, 5, 20, 0, 'PNG', '', 'T', false, 300, '', false, false, 0, false, false); // Movido a Y=5 para separarlo del borde
        }


        $this->SetY(5); // Inicia un poco más abajo para el título, ajustando la posición
        $this->SetFont('helvetica', 'B', 15);
        $this->Cell(0, 10, 'LISTADO GENERAL DE MASCOTAS', 0, 1, 'C');


        // Línea de separación opcional
        $this->Line(10, self::HEADER_HEIGHT - 2, $this->getPageWidth() - 10, self::HEADER_HEIGHT - 2);

        // Espacio final después del header.
        // Importante: establece la posición Y para el inicio del contenido.
        $this->SetY(self::HEADER_HEIGHT);
    }

    public function Footer() {
        $this->SetY(-15);
        $this->SetFont('helvetica', 'I', 8);
        $this->Cell(0, 10, 'Página ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, 0, 'C');
    }
}

// --- Crear PDF ---
$pdf = new MYPDF('P', 'mm', 'A4', true, 'UTF-8', false);
$pdf->SetCreator('Sistema Adiestramiento Tahito');
$pdf->SetAuthor('Adiestramiento Tahito');
$pdf->SetTitle('Listado General de Mascotas');


$pdf->SetMargins(10, MYPDF::HEADER_HEIGHT + 2, 10); // El segundo parámetro es el margen superior. Debe ser mayor a la altura final de tu header.
$pdf->SetHeaderMargin(10); // Margen entre la parte superior de la página y el inicio del header
$pdf->SetFooterMargin(10);
$pdf->AddPage();
$pdf->SetFont('helvetica', '', 10);

// --- HTML de la tabla ---
$html = '

<table border="1" cellpadding="5" cellspacing="0">
    <thead>
        <tr style="background-color:#2E6009; text-align:center;">
            <th><b>ID</b></th>
            <th><b>Nombre</b></th>
            <th><b>Dueño</b></th>
            <th><b>Fecha Nacimiento</b></th>
            <th><b>Edad</b></th>
            <th><b>Raza</b></th>
            <th><b>Tamaño</b></th>
            <th><b>Color</b></th>
        </tr>
    </thead>
    <tbody>
';

while ($mascota = $result->fetch_assoc()) {
    $html .= '
        <tr>
            <td>' . htmlspecialchars($mascota['id_mascota']) . '</td>
            <td>' . htmlspecialchars($mascota['nombre_mascota']) . '</td>
            <td>' . htmlspecialchars($mascota['duenio']) . '</td>
            <td>' . htmlspecialchars($mascota['fecha_de_nacimiento']) . '</td>
            <td>' . htmlspecialchars($mascota['edad']) . '</td>
            <td>' . htmlspecialchars($mascota['raza']) . '</td>
            <td>' . htmlspecialchars($mascota['tamanio']) . '</td>
            <td>' . htmlspecialchars($mascota['color']) . '</td>
        </tr>
    ';
}

$html .= '</tbody></table>';

// --- Renderizamos PDF ---
$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Output('listado_general_mascotas.pdf', 'I');

