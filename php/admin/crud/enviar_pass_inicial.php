<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Subimos tres niveles desde /php/admin/crud/
require_once '../../config.php';

// PHPMailer
require_once '../../libs/PHPMailer/src/PHPMailer.php';
require_once '../../libs/PHPMailer/src/SMTP.php';
require_once '../../libs/PHPMailer/src/Exception.php';

function enviarCorreo($admin,$pass_admin,$destinatario, $asunto, $cuerpo) {

    $phpmailer = new PHPMailer();
    $phpmailer->isSMTP();
    $phpmailer->isHTML(true);
    $phpmailer->Host = 'mail.serviciosya.com.ar';
    $phpmailer->Port = 465;
    $phpmailer->SMTPAuth = true;
    $phpmailer->Username = $admin;
    $phpmailer->Password = $pass_admin;
    $phpmailer->SMTPSecure ='ssl';

    $phpmailer->SMTPOptions = array(
    'ssl' => array(
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true
    )
);

    try {
        // Configuración de remitente y destinatario
        $phpmailer->setFrom($admin, 'Adiestramiento Tahito');
        $phpmailer->addAddress($destinatario, 'Nombre Destinatario');

        // Enviando email html
        $phpmailer->Subject = $asunto;
        $phpmailer->Body    = $cuerpo;
        $phpmailer->AltBody = strip_tags($cuerpo);

        // Enviar el email
        $phpmailer->send();

        return true;
    } catch (Exception $e) {
        error_log("Error al enviar correo: " . $phpmailer->ErrorInfo);
        return false;
    }
}

?>