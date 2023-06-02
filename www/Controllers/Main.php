<?php

namespace App\Controllers;

use App\Core\View;

class Main
{
    public function home(): void
    {
        if(!isset($_SESSION['id'])) {
            header('Location: /s-inscrire');
            exit();
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