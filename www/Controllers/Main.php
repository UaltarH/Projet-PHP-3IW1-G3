<?php

namespace App\Controllers;

use App\Core\View;

use function App\Services\TestUserIsConnected\UserExistForAccess;

require_once '/var/www/html/Services/TestUserIsConnected.php';

class Main
{
    public function home(): void     
    {
        $error = false;
        $messageInfo = [];
        session_start();
        
        if (isset($_SESSION['pseudo'])) {
            //tester si le pseudo et si le compte est bien confirmé         
            $response = UserExistForAccess($_SESSION['pseudo']);
            if($response->success == false) {
                $messageInfo['noConnection'] = $response->message;
                $error = true;
            }
        } else {
            $messageInfo['noConnection'] = 'Vous etes pas connecter, vous allez etre rediriger vers la page de connexion.';
            $error = true;
        }
        
        if($error){
            //utilisateur non connecter:
            $view = new View("Common/NotAccess", "unauthorised");
            $view->assign("messageInfo", $messageInfo);
            $view->assign("typeError", 'noConnection');
        }
        else {
            //utilisateur connecter:
            $view = new View("Main/home", "front");
            $view->assign("pseudo", $_SESSION['pseudo']);
            $view->assign("roleId", $response->userResult->getRoleId());
        }
    }

    public function contact(): void
    {
        echo "Page de contact";
    }

    public function aboutUs(): void
    {
        echo "Page à propos";
    }

}