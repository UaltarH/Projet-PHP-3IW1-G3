<?php
namespace App\Services\SendEmail;

use App\Core\Config;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
require '/var/www/html/library/PHPMailer/src/Exception.php';
require '/var/www/html/library/PHPMailer/src/PHPMailer.php';
require '/var/www/html/library/PHPMailer/src/SMTP.php';

function SendMailFunction($to, $contentMail,$subject):array 
{
    $config = Config::getInstance()->getConfig();
    try {
        $mail = new PHPMailer (true);
        $mail->IsSMTP();
        $mail->Mailer = $config['mail']['mailer'];
        $mail->SMTPDebug  = 0;  
        $mail->Port       = $config['mail']['port'];
        $mail->Host       = $config['mail']['host'];
        $mail->IsHTML(true);
        $mail->AddAddress($to, "recipient-name");
        $mail->SetFrom($config['mail']['mailFrom'], "from-name");
        $mail->Subject = $subject;
        $content = $contentMail;

        $mail->MsgHTML($content);

        if(!$mail->Send()) {
            $messageInfoSendMail[] = "Erreur lors de l'envoi du mail.";   
        } else {
            $messageInfoSendMail[] = "L'email a bien été envoyé.";
        }
    } catch (Exception $e) {
            $messageInfoSendMail = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    } 
    finally {
        return $messageInfoSendMail;
    }
}