<?php

namespace App\Controllers;

use App\Core\View;

use function App\Core\TokenJwt\getSpecificDataFromToken;
require_once '/var/www/html/Core/TokenJwt.php';

class Main
{
    public function home(): void     
    {
        //utilisateur connecter:        
        $view = new View("Main/home", "front");
        if(isset($_SESSION['token'])){
            $view->assign("pseudo", getSpecificDataFromToken($_SESSION['token'], "pseudo"));
            $view->assign("roleId", getSpecificDataFromToken($_SESSION['token'], "roleId"));
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