<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../../vendor/autoload.php'; // adapter le chemin si besoin

class MailController
{
  
    public function envoyerMail()
    {
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'airbarry94@gmail.com';
            $mail->Password = 'jzdmiazwxwjqhikg'; // Gmail App Password
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('airbarry94@gmail.com', 'Ton Nom');
            $mail->addAddress('amitacompt90@gmail.com');

            $mail->isHTML(true);
            $mail->Subject = 'Sujet test';
            $mail->Body = '<b>Bonjour, ceci est un test</b>';

            $mail->send();
            echo "Email envoyé avec succès";
        } catch (Exception $e) {
            echo "Erreur lors de l'envoi : {$mail->ErrorInfo}";
        }
    }
}
