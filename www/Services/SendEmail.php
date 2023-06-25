<?php
namespace App\Services\SendEmail;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
require '/var/www/html/library/PHPMailer/src/Exception.php';
require '/var/www/html/library/PHPMailer/src/PHPMailer.php';
require '/var/www/html/library/PHPMailer/src/SMTP.php';

function SendMailFunction($to, $contentMail,$subject):array 
{
    try {
        $mail = new PHPMailer (true);
        $mail->IsSMTP();
        $mail->Mailer = "smtp";
        $mail->SMTPDebug  = 0;  
        $mail->Port       = 1025;
        $mail->Host       = "mailcatcher";
        $mail->IsHTML(true);
        $mail->AddAddress($to, "recipient-name");
        $mail->SetFrom("carte_chance_admin@myges.fr", "from-name");
        $mail->Subject = $subject;
        $content = $contentMail;

        $mail->MsgHTML($content);

        if(!$mail->Send()) {
            $messageInfoSendMail[] = "Error while sending Email.";   
        } else {
            $messageInfoSendMail[] = "Email sent successfully";
        }
    } catch (Exception $e) {
            $messageInfoSendMail = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    } 
    finally {
        return $messageInfoSendMail;
    }
}