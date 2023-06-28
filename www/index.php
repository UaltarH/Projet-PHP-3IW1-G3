<?php

namespace App;

use App\Models\Role;

use function App\Core\TokenJwt\validateJWT;
use function App\Core\TokenJwt\getSpecificDataFromToken;
require_once '/var/www/html/Core/TokenJwt.php';
//require "Core/View.php";

spl_autoload_register(function ($class) {

    //$class = App\Core\View
    $class = str_replace("App\\","", $class);
    //$class = Core\View
    $class = str_replace("\\","/", $class);
    //$class = Core/View
    $classForm = $class.".form.php";
    $class = $class.".php";
    //$class = Core/View.php
    if(file_exists($class)){
        include $class;
    }else if(file_exists($classForm)){
        include $classForm;
    }
});

//Afficher le controller et l'action correspondant à l'URI

$uriStr = $_SERVER["REQUEST_URI"];
$uriExploded = explode("?", $uriStr);
$uriStr = strtolower(trim( $uriExploded[0], "/"));

$uri = [];
if(empty($uriStr))
    $uri[0] = "default";
else $uri = explode('/', $uriStr);
if(!file_exists("routes.yml")){
    die("Error 500 Internal Server Error : Le fichier routes.yml n'existe pas");
}

$routes = yaml_parse_file("routes.yml");
$controller = null;
$action = null;
$routeArray = $routes;
if(count($uri) > 1) {
    foreach($uri as $value) {
        if(isset($routeArray[$value])) {
            $routeArray = $routeArray[$value];
        }
        else {
            die('Error 404 : route not exist');
        }
    }
}
else {
    $routeArray = $routeArray[$uri[0]];
}
if(isset($routeArray["controller"]) && $routeArray["action"]) {
    $controller = $routeArray["controller"];
    $action = $routeArray["action"];
}
else {
    die('Error 500 Internal Server Error : Pas de controller ou action');
}



if(!file_exists("Controllers/".$controller.".php")){
    die("Error 500 Internal Server Error : Le fichier Controllers/".$controller.".php n'existe pas");
}
include "Controllers/".$controller.".php";

$controller = "\\App\\Controllers\\".$controller;

if(!class_exists($controller)){
    die("Error 500 Internal Server Error : La classe ".$controller." n'existe pas");
}
$objController = new $controller();

if(!method_exists($objController, $action)){
    die("Error 500 Internal Server Error : L'action ".$action." n'existe pas");
}

// TEST si il y a une clé token dans la session et si le token est valide et que si le role de l'utilisateur est autorisé à accéder à la page
if(isset($routeArray["access"])) {
    $accessArray = array_map('strtolower', $routeArray["access"]);
    if(in_array("all", $accessArray) ){
        $objController->$action();
    }
    else{
        session_start();
        if(isset($_SESSION["token"])) {
            $token = $_SESSION["token"];
            if(validateJWT($token)) {
                // tester le role de l'utilisateur en le comparent avec le role de la route
                $roleId = getSpecificDataFromToken($token, "roleId");
    
                $role = new Role(); 
                $whereSql = ["id" => $roleId];
                $role = $role->getOneWhere($whereSql);
                if(is_bool($role)){
                    die("Error 500 Internal Server Error : Le role n'existe pas");
                } else{
                    switch($role->getRoleName()){
                        case "admin":
                            if(in_array("admin", $accessArray)){
                                $objController->$action();
                            }
                            else{
                                die("Error Unauthorized : Vous n'avez pas les droits pour accéder à cette page");
                            }
                            break;
                        case "user":
                            if(in_array("user", $accessArray)){
                                $objController->$action();
                            }
                            else{
                                die("Error Unauthorized : Vous n'avez pas les droits pour accéder à cette page");
                            }
                            break;
                        case "moderator":
                            if(in_array("moderator", $accessArray)){
                                $objController->$action();
                            }
                            else{
                                die("Error Unauthorized : Vous n'avez pas les droits pour accéder à cette page");
                            }
                        default:
                            die("Error 500 Internal Server Error : Le role de l'utilisateur n'existe pas");
                    }
                }
            } else {
                die("Error Unauthorized : Token invalide ou expiré");
            }
       }
       else{
        die("pas de token c'est ciaooooo !");
       }
    }
} else {
    die("Error 500 Internal Server Error : the route doesn't have access key");
}