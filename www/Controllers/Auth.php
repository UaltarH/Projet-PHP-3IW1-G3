<?php

namespace App\Controllers;

use App\Core\View;
use App\Forms\Register;
use App\Forms\Connection;
use App\Models\User;
use App\Models\Role;

use function App\Core\TokenJwt\generateJWT;
use function App\Services\SendEmail\SendMailFunction;

require_once '/var/www/html/Services/SendEmail.php';
require_once '/var/www/html/Core/TokenJwt.php';

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
            $user = $user->getOneWhere($whereSql);

            if(!is_bool($user)){ //si le resultat de getOneWhere est un bool ca veut dire qu'il na pas trouver l'utilisateur 
                if (password_verify($_POST['password'], $user->getPassword())) {
                    // Le mot de passe est correct   
                    if($user->getEmailConfirmation() == false) { //tester si l'email est confirmé   
                        $formConnection->errors[] = "l'email n'est pas confirmer";
                    } else {
                        //get the name of the role of the user
                        $role = new Role();
                        $whereSql = ["id" => $user->getRoleId()];
                        $role = $role->getOneWhere($whereSql);
                        //l'utilisateur est bien connecter du coup on le redirige vers la home page 
                        
                        //creer le token jwt et le set en variable session 
                        $payload = array(
                            'id' => $user->getId(), // Identifiant de l'utilisateur 
                            'pseudo' => $user->getPseudo(), // Pseudo de l'utilisateur
                            'firstName' => $user->getFirstname(), // Prénom de l'utilisateur
                            'lastName' => $user->getLastname(), // Nom de l'utilisateur
                            'roleId' => $user->getRoleId(), //  id role de l'utilisateur
                            'roleName' => $role->getRoleName(), // nom du role de l'utilisateur
                            'iat' => time(), // Horodatage de création du JWT (émetteur)
                            'exp' => time() + 7200 // Horodatage d'expiration du JWT (2 heure)
                        );

                        $token = generateJWT($payload);

                        $_SESSION['token'] = $token;
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
        
            if($form->isPhoneNumberValid($_POST['phone_number']) && $form->isPasswordValid($_POST['password'], $_POST['passwordConfirm']) && $form->isFieldsInfoValid($user, ["email"=>$_POST['email'], "pseudo"=>$_POST['pseudo'], "phone_number"=>$_POST['phone_number']])){
                $user->setPseudo($_POST['pseudo']);
                $user->setFirstname($_POST['first_name']);
                $user->setLastname($_POST['last_name']);
                $user->setEmail($_POST['email']);
                $user->setPhoneNumber($_POST['phone_number']);
                $user->setPassword($_POST['password']);
                $user->setEmailConfirmation(false);
                $user->setDateInscription(date("Y-m-d H:i:s"));
                $user->setRoleId(""); //1 = user

                //create confirm token for email confirmation 
                $lengthKey = 20;
                $key = "";
                for($i = 0 ; $i < $lengthKey ; $i++){
                    $key .= mt_rand(0,20);
                }
                $user->setConfirmAndResetToken($key);

                $responseQuery = $user->save();
                if($responseQuery->success){ //return true | false
                    //envoi du mail pour la confirmation du compte:
                    $to = $user->getEmail();
                    $contentMail = "<b>Hello ".$user->getPseudo().", <a href='http://localhost/email-confirmation?pseudo=".urlencode($user->getPseudo())."&key=".$user->getConfirmAndResetToken()."'> Confirmez votre compte </a></b>";
                    $subject = "Confirmation de compte pour notre site Carte chance.";
                    $resultSendMail = SendMailFunction($to, $contentMail, $subject);

                    $view->assign("messageInfoSendMail", $resultSendMail);
                }
            }
        }
        $view->assign("formErrors", $form->errors);
    }

    public function logout(): void
    {
        session_destroy();
        // Supprimer une variable de session spécifique
        //unset($_SESSION['token']);
        header("Location: /");
    }

    public function emailConfirmation(): void {
        $view = new View("Auth/emailConfirmation", "front");
        $messageInfo = [];
        //$view->assign("messageInfo", '');

        if(isset($_GET['pseudo'], $_GET['key']) AND !empty($_GET['pseudo']) AND !empty($_GET['key'])) {
            $pseudo = htmlspecialchars(urldecode($_GET['pseudo']));
            $key = htmlspecialchars($_GET['key']);

            $user = new User();
            $whereSql = ["pseudo" => $pseudo, "confirmAndResetToken" => $key];
            $result = $user->getOneWhere($whereSql);
            if(is_bool($result)){
                $messageInfo[] = "utilisateur introuvable belec au hack !";
            }
            else{
                if($result->getEmailConfirmation() == true){
                    $messageInfo[] = "votre compte a deja été confirmé";
                }
                else{
                    $result->setEmailConfirmation(true);
                    $responseQuery = $result->save();
                    if($responseQuery->success){
                        $messageInfo[] = "votre compte a bien été confirmez, vous pouvez des maintenant vous connecter";
                    }
                }
            }        
        }
        else{
            $messageInfo[] = "les parametres de l'url sont incorrect";
        }
        $view->assign("messageInfo", $messageInfo);
    }
}