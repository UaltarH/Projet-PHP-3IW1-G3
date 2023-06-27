<?php

namespace App\Controllers;

use App\Core\View;
use App\Forms\Register;
use App\Forms\Connection;
use App\Models\User;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
require '/var/www/html/library/PHPMailer/src/Exception.php';
require '/var/www/html/library/PHPMailer/src/PHPMailer.php';
require '/var/www/html/library/PHPMailer/src/SMTP.php';

class Auth
{
    public function login(): void
    {
        $formConnection = new Connection();
        $view = new View("Auth/connection", "front");
        $view->assign("form", $formConnection->getConfig());
        $formConfig = $formConnection->getConfig();
        if($formConnection->isSubmited() && $formConnection->isValid()){
            $user = new User();
            $whereSql = ["pseudo" => $_POST['pseudo']];
            $result = $user->getOneWhere($whereSql);

            if(!is_bool($result)){ //si le resultat de getOneWhere est un bool ca veut dire qu'il na pas trouver l'utilisateur 
                if (password_verify($_POST['password'], $result->getPassword())) {
                    // Le mot de passe est correct   
                    if($result->getEmailConfirmation() == false) { //tester si l'email est confirmé   
                        $formConnection->errors[] = "l'email n'est pas confirmer";
                    } else {
                        header("Location: default");
                    }    
                    
                } else {
                    // Le mot de passe est incorrect
                    $formConnection->errors[] = $formConfig["inputs"]["password"]["error"];
                }
            }
            else{
                //pseudo nexiste pas 
                $formConnection->errors[] = $formConfig["inputs"]["pseudo"]["error"];
            }
        }
        $view->assign("formErrors", $formConnection->errors);
    }

    public function register(): void
    {
        $form = new Register();
        $view = new View("Auth/register", "front");
        $view->assign("form", $form->getConfig());
        
        //Form validé ? et correct ?
        if($form->isSubmited() && $form->isValid()){
            $user = new User();
            //$user->setTableFromChild();
        
            if($form->isPhoneNumberValid($_POST['phone_number']) && $form->isPasswordValid($_POST['password'], $_POST['passwordConfirm']) && $form->isUserInfoValid($user, $_POST['email'], $_POST['pseudo'], $_POST['phone_number'])){
                $user->setPseudo($_POST['pseudo']);
                $user->setFirstname($_POST['first_name']);
                $user->setLastname($_POST['last_name']);
                $user->setEmail($_POST['email']);
                $user->setPhoneNumber($_POST['phone_number']);
                $user->setPassword($_POST['password']);
                $user->setEmailConfirmation(false);
                $user->setDateInscription(date("Y-m-d H:i:s"));

                //create confirm token for email confirmation 
                $lengthKey = 20;
                $key = "";
                for($i = 0 ; $i < $lengthKey ; $i++){
                    $key .= mt_rand(0,20);
                }
                $user->setConfirmToken($key);

 
                if($user->save()){ //return true | false

                    //envoi du mail pour la confirmation du compte:
                    try {
                        $mail = new PHPMailer (true);
                        $mail->IsSMTP();
                        $mail->Mailer = "smtp";
                        $mail->SMTPDebug  = 0;  
                        $mail->Port       = 1025;
                        $mail->Host       = "mailcatcher";
                        $mail->IsHTML(true);
                        $mail->AddAddress($user->getEmail(), "recipient-name");
                        $mail->SetFrom("carte_chance_admin@myges.fr", "from-name");
                        $mail->Subject = "Confirmation de compte pour notre site Carte chance.";
                        $content = "<b>Hello ".$user->getPseudo().", <a href='http://localhost/email-confirmation?pseudo=".urlencode($user->getPseudo())."&key=".$user->getConfirmToken()."'> Confirmez votre compte </a></b>";

                        $mail->MsgHTML($content);

                        if(!$mail->Send()) {
                            echo "Error while sending Email.";
                        } else {
                            echo "Email sent successfully";
                        }
                    } catch (Exception $e) {
                            echo "Mailer Error: ".$mail->ErrorInfo;
                    }


                    header("Location: email-confirmation");
                }
            }
        }
        $view->assign("formErrors", $form->errors);
    }

    public function logout(): void
    {
        echo "Page de déconnexion";
    }

    public function emailConfirmation(): void {
        if(isset($_GET['pseudo'], $_GET['key']) AND !empty($_GET['pseudo']) AND !empty($_GET['key'])) {
            $pseudo = htmlspecialchars(urldecode($_GET['pseudo']));
            $key = htmlspecialchars($_GET['key']);

            $user = new User();
            $whereSql = ["pseudo" => $pseudo, "confirmToken" => $key];
            $result = $user->getOneWhere($whereSql);
            if(is_bool($result)){
                echo("utilisateur introuvable belec au hack !");
            }
            else{
                if($result->getEmailConfirmation() == true){
                    echo('votre compte a deja été confirmé');
                }
                else{
                    $result->setEmailConfirmation(true);
                    if($result->save()){
                        echo('votre compte a été confirmez, vous pouvez des maintenant vous connecter');
                    }
                }
            }        
        }
        else{
            echo "Un email de confirmation vous a été envoyé a votre adresse mail afin de confirmez votre compte.<br>";
        }
        echo "<button onclick='window.location.href='/login''>
                Connection
            </button>";
    }
}