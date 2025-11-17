<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once '../../libs/PHPMailer/src/Exception.php';
require_once '../../libs/PHPMailer/src/PHPMailer.php';
require_once '../../libs/PHPMailer/src/SMTP.php';
require_once '../../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (session_status() == PHP_SESSION_NONE) {session_start();}

    $email = CORREO_HOST;
    $email_contacto = $_POST['correo_contacto'] ?? '';
    $nombre_contacto = $_POST['nombre_contacto'];
    $asunto = $_POST['asunto_contacto'] ?? '';
    $mensaje = $_POST['mensaje_contacto'] ?? '';

    $mail = new PHPMailer(true);

    try {
        // Configuración SMTP del hosting
        $mail->isSMTP();
        $mail->Host = 'mail.serviciosya.com.ar';
        $mail->SMTPAuth = true;
        $mail->Username = $email;
        $mail->Password = PASS_HOST;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;
        $mail->addCC('valeriavable2000@gmail.com');
        $mail->addCC("yuske2108@gmail.com");

        $mail->setFrom($email, $nombre_contacto);

        $mail->addAddress($email, 'Adiestramiento Tahito');

        // Opcional: para evitar errores SSL
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );

        // Contenido del mensaje
        $mail->isHTML(true);
        $mail->Subject = $asunto;
        $mail->Body = "
            <h2>" . $asunto ."</h2>
            <p>". $mensaje . "</p>
        ";
        $mail->AltBody = strip_tags($mail->Body);

        //Para que se le pueda responder
        $mail->addReplyTo($email_contacto, $nombre_contacto);

        $mail->send();

        header('Location: ../contacto.php?mensaje=Mensaje enviado correctamente');
        exit;   

    } catch (Exception $e) {
        header("Location: ../contacto.php?error=Error al enviar el mensaje: {$mail->ErrorInfo}");
        exit;
    }
}

?>