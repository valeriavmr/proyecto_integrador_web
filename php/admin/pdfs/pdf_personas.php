<?php
require_once('../auth.php');

require_once('../../../libs/TCPDF/tcpdf.php');

#Importo la familia de fuentes
$montserrat = TCPDF_FONTS::addTTFfont('../../../recursos/fuentes/Montserrat-Regular.ttf', 'TrueTypeUnicode', '', 32);
$montserrat_bold = TCPDF_FONTS::addTTFfont('../../../recursos/fuentes/Montserrat-Bold.ttf', 'TrueTypeUnicode', '', 32);
$montserrat_italic = TCPDF_FONTS::addTTFfont('../../../recursos/fuentes/Montserrat-Italic.ttf', 'TrueTypeUnicode', '', 32);
$montserrat_bi = TCPDF_FONTS::addTTFfont('../../../recursos/fuentes/Montserrat-BoldItalic.ttf', 'TrueTypeUnicode', '', 32);

//header
    class MyPDF extends TCPDF{

        //Header
        public function Header() {
		// Logo
        
		$image_file = '../../../recursos/logsinfondo.png';
		

		// Tipo de letra
		$this->SetFont('montserrat', 'B', 15);
        
        //Color del texto
        $this->SetColor('text',46, 96, 9);

		// Title
		$this->Cell(0, 15, 'Datos de usuarios', 0, false, 'C', 0, '', 0, false, 'T', 'C');
	    }

         //footer
	    public function Footer() {
		// Posicionar a 15 mm del fin de la pagina
		$this->SetY(-15);
		
		$this->SetFont('montserrat', 'I', 12);

        //Color del texto
        $this->SetColor('text',0,64,0);

		// Numero de pagina
		$this->Cell(0, 10, $this->getAliasNumPage(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
	    }
    }


    //Creo el pdf con la clase que sobreescribí

    $pdf = new MyPDF();

        $pdf->SetHeaderData('',
    30,
    'Mi primer pdf',
    'Ejemplo con encabezado',
    array(97,36,249),
    array(228,156,252)
);

    # Footer estándar (con número de página)
    $pdf->setFooterData(array(0,64,0), array(0,64,128));
    $pdf->setFooterFont(Array('Helvetica', '', 14));

    # Márgenes (en mm, configurados a mano)
    $pdf->SetMargins(15, 27, 15);
    $pdf->SetHeaderMargin(5);
    $pdf->SetFooterMargin(10);

    $pdf->SetAutoPageBreak(TRUE, 25);  // 25mm desde abajo

    $pdf->SetFont($montserrat,'',8);

    $pdf->AddPage();

    //Recupero los datos de la tabla
    require_once('../../crud/conexion.php');
    include_once('../../crud/consultas_varias.php');

    [$datos_personas, $columnas] = selectAllPersonas($conn);

    //Con eso empezamos a crear el html
    $html = '
        <table cellspacing="0" cellpadding="4" border="1">
        <thead><tr>';

    foreach($columnas as $nombre_columna){
        $html .= '<th>' . $nombre_columna . '</th>';
    }

    $html = $html . '</tr></thead><tbody>';

    //Ahora creamos el cuerpo de la tabla
    foreach($datos_personas as $fila){
        $html = $html . '<tr>';
        foreach ($columnas as $columna) {
            if($columna == 'password'){
            $html .= "<td>••••••••</td>";
            }else{
            $html .= '<td>' . htmlspecialchars($fila[$columna]) . '</td>';
            }
    }
    $html = $html . '</tr>';
    }
    $html = $html . '</tbody></table>';

    $pdf->writeHTML($html, true, false, true, false, '');

    #Genero el pdf
    $pdf->Output('datos_personas.pdf','I');
?>