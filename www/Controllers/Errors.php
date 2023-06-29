<?php

namespace App\Controllers;

use App\Core\View;

class Errors
{
    public static function define(Int $error, String $message): void
    {
        if ($error == 404) {
            self::error404($message);
        } else if ($error == 500) {
            self::error500($message);
        }
    }

    public static function error404($message): void
    {
        http_response_code(404);
        $view = new View("Errors/error400", "front");
        $view->assign("message", $message);
    }

    public static function error500($message): void
    {
        http_response_code(500);
        $view = new View("Errors/error500", "front");
        $view->assign("message", $message);
    }
}
