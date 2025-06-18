<?php
$root = $_SERVER['DOCUMENT_ROOT'];
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once  $root.'/vendor/autoload.php';

class EmailHelper {
    public static function enviar($para, $assunto, $mensagemHtml) {
        $mail = new PHPMailer(true);

        try {
            // $mail->SMTPDebug = SMTP::DEBUG_OFF;
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'obsidiangames1503@gmail.com';         // Altere aqui
            $mail->Password = 'khgf tlzf bhxf nloo';           // App Password 
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('obsidiangames1503@gmail.com', 'Suporte Gamehub');
            $mail->addAddress($para);
            $mail->isHTML(true);
            $mail->Subject = $assunto;
            $mail->Body    = $mensagemHtml;

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Erro: {$mail->ErrorInfo}");
            return false;
        }
    }
}
