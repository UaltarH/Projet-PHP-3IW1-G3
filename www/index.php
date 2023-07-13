<?php


namespace App;
session_start();

use App\Core\Config;
use App\Core\Errors;
use App\Models\Role;
use App\Repository\RoleRepository;
use function App\Core\TokenJwt\getSpecificDataFromToken;
use function App\Core\TokenJwt\validateJWT;

require_once '/var/www/html/Core/TokenJwt.php';

spl_autoload_register(function ($class) {

    //$class = App\Core\View
    $class = str_replace("App\\", "", $class);
    //$class = Core\View
    $class = str_replace("\\", "/", $class);
    //$class = Core/View
    $classForm = $class . ".form.php";
    $class = $class . ".php";
    //$class = Core/View.php
    if (file_exists($class)) {
        include $class;
    } else if (file_exists($classForm)) {
        include $classForm;
    }
});
/**
 * Affiche l'environnement du projet
 */
//echo Config::getInstance()->getEnvironment();
//echo '<pre>';
//var_dump(Config::getConfig());
//echo '</pre>';
//Afficher le controller et l'action correspondant à l'URI
$uriStr = $_SERVER["REQUEST_URI"];
$uriExploded = explode("?", $uriStr);
$uriStr = strtolower(trim($uriExploded[0], "/"));

$uri = [];
if(empty($uriStr))
    $uri[0] = "default";
else $uri = explode('/', $uriStr);
if (!file_exists("routes.yml")) {
    Errors::define(500, "Le fichier routes.yml n'existe pas");
    exit;
}
$routes = yaml_parse_file("routes.yml");
$controller = null;
$action = null;
$routeArray = $routes;
if (count($uri) > 1) {
    foreach ($uri as $value) {
        if (isset($routeArray[$value])) {
            $routeArray = $routeArray[$value];
        } else {
            Errors::define(400, "Route not exist");
            exit;
        }
    }
} else {
    if (isset($routeArray[$uri[0]])) {
        $routeArray = $routeArray[$uri[0]];
    } else {
        Errors::define(400, "Route not exist");
        exit;
    }
}

if (isset($routeArray["controller"]) && $routeArray["action"]) {
    $controller = $routeArray["controller"];
    $action = $routeArray["action"];
} else {
    Errors::define(500, 'Pas de controller ou action');
    exit;
}

if (!file_exists("Controllers/" . $controller . ".php")) {
    Errors::define(500, "Le fichier Controllers/" . $controller . ".php n'existe pas");
    exit;

}
include "Controllers/" . $controller . ".php";

$controller = "\\App\\Controllers\\" . $controller;

if (!class_exists($controller)) {
    Errors::define(500, "La classe " . $controller . " n'existe pas");
    exit;
}
$objController = new $controller();

if (!method_exists($objController, $action)) {
    Errors::define(500, "L'action " . $action . " n'existe pas");
    exit;
}

// TEST si il y a une clé token dans la session et si le token est valide et que si le role de l'utilisateur est autorisé à accéder à la page
if(isset($routeArray["access"])) {
    $accessArray = array_map('strtolower', $routeArray["access"]);
    if(in_array("all", $accessArray) ){
        $objController->$action();
    }
    else{        
        if(isset($_SESSION["token"])) {
            $token = $_SESSION["token"];
            if(validateJWT($token)) {
                // tester le role de l'utilisateur en le comparent avec le role de la route
                $roleId = getSpecificDataFromToken($token, "roleId");
    
                $role = new Role(); 
                $whereSql = ["id" => $roleId];
                $role = (new RoleRepository())->getOneWhere($whereSql, $role);
                if(is_bool($role)){
                    Errors::define(400, 'Le role de l\'utilisateur n\'existe pas');
                    exit;
                } else{
                    switch($role->getRoleName()){
                        case "admin":
                            if(in_array("admin", $accessArray)){
                                $objController->$action();
                            }
                            else{
                                Errors::define(400, 'Vous n\'avez pas les droits pour accéder à cette page');
                                exit;
                            }
                            break;
                        case "user":
                            if(in_array("user", $accessArray)){
                                $objController->$action();
                            }
                            else{
                                Errors::define(400, 'Vous n\'avez pas les droits pour accéder à cette page');
                                exit;
                            }
                            break;
                        case "moderator":
                            if(in_array("moderator", $accessArray)){
                                $objController->$action();
                            }
                            else{
                                Errors::define(400, 'Vous n\'avez pas les droits pour accéder à cette page');
                                exit;
                            }
                        default:
                            Errors::define(400, 'Le role de l\'utilisateur n\'existe pas');
                            exit;
                    }
                }
            } else {
                Errors::define(400, 'Token invalide ou expiré');
                exit;
            }
       }
       else{
           Errors::define(400, 'Pas de Token');
           exit;
       }
    }
} else {
    Errors::define(400, 'the route doesn\'t have access key');
    exit;
}