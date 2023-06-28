<?php

namespace App;

use App\Controllers\Errors;

//require "Core/View.php";

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

//Afficher le controller et l'action correspondant à l'URI

$uriStr = $_SERVER["REQUEST_URI"];
$uriExploded = explode("?", $uriStr);
$uriStr = strtolower(trim($uriExploded[0], "/"));


$uri = [];;
if (empty($uriStr))
    $uri[0] = "default";
else $uri = explode('/', $uriStr);
if (!file_exists("routes.yml")) {
    return Errors::define(500, "Le fichier routes.yml n'existe pas");
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
            return Errors::define(404, "Route not exist");
        }
    }
} else {
    if (isset($routeArray[$uri[0]])) {
        $routeArray = $routeArray[$uri[0]];
    } else {
        return Errors::define(404, "Route not exist");
    }
}
if (isset($routeArray["controller"]) && $routeArray["action"]) {
    $controller = $routeArray["controller"];
    $action = $routeArray["action"];
} else {
    return Errors::define(500, 'Pas de controller ou action');
}

// TODO : test privileges
//if(isset($routeArray["access"])) {
//    if(!isset($_SESSION["role"]) || !str_contains($routeArray["access"],$_SESSION["role"])) {
//        die('Error 404 access denied, not sufficient privileges');
//    }
//}

if (!file_exists("Controllers/" . $controller . ".php")) {
    return Errors::define(500, "Le fichier Controllers/" . $controller . ".php n'existe pas");
    // die("Error 500 Internal Server Error : Le fichier Controllers/" . $controller . ".php n'existe pas");
}
include "Controllers/" . $controller . ".php";

$controller = "\\App\\Controllers\\" . $controller;

if (!class_exists($controller)) {
    return Errors::define(500, "La classe " . $controller . " n'existe pas");
    // die("Error 500 Internal Server Error : La classe " . $controller . " n'existe pas");
}
$objController = new $controller();

if (!method_exists($objController, $action)) {
    return Errors::define(500, "L'action " . $action . " n'existe pas");
    // die("Error 500 Internal Server Error : L'action " . $action . " n'existe pas");
}

$objController->$action();
