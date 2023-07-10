<?php

namespace App\Core;

use App\Models\User;
use App\Repository\RoleRepository;
use App\Repository\UserRepository;

class Validator
{
    private array $data = [];
    public array $errors = [];
    
    public function isSubmited(): bool
    {
        $this->data = ($this->method == "POST")?$_POST:$_GET;
        if(isset($this->data[$this->config["config"]["submitName"]])){
            return true;
        }
        return false;
    }
    public function isValid(): bool
    {
        //La bonne methode ? -> tester si la request method (POST / GET / PUT ect...) est la meme que celle qui est attendu par la classe enfant
        if($_SERVER["REQUEST_METHOD"] != $this->method){
            die("Tentative de Hack methode differente");
        }

        //exclude les inputs qui on un type file
        $filteredConf = array_filter($this->config["inputs"], function($element) {
            return !isset($element["type"]) || $element["type"] !== "file";
        });
        
        //Le nb de inputs -> pour tester si le nombre d'input envoyer et le meme que celui qui est attendu par la classe enfant
        if(count($filteredConf)+1 != count($this->data)){ //+1 car "submit" est envoyé aussi
            die("Tentative de Hack nombre d'input different");
        }

        //tester les inputs envoyer
        foreach ($filteredConf as $name=>$configInput){
            //tester si le nom de l'input est attendu 
            if(!isset($this->data[$name])){
                die("Tentative de Hack, input non attendu");
            }
            //tester dans le cas ou l'input ne doit pas etre vide(required)
            if(isset($configInput["required"]) && self::isEmpty($this->data[$name])){
                die("Tentative de Hack, input vide");
            }
            //tester si l'input a un minimum de taille
            if(isset($configInput["min"]) && !self::isMinLength($this->data[$name], $configInput["min"]) && !self::isEmpty($this->data[$name])){
                $this->errors[]=$configInput["error"];
            }
            //tester si l'input a un maximum de taille
            if(isset($configInput["max"]) && !self::isMaxLength($this->data[$name], $configInput["max"]) && !self::isEmpty($this->data[$name])){
                $this->errors[]=$configInput["error"];
            }
        }
        //tester si il y a une erreur lors de la validation 
        if(empty($this->errors)){
            return true;
        }
        return false;
    }
    public function isPasswordValid(string $password, $passwordConfirm): bool
    {
        if($password != $passwordConfirm){
            $this->errors[]=$this->config["inputs"]["passwordConfirm"]["error"];
            return false;
        }
        $regex = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)/'; //regex pour verifier si le psw contiens au moins 1 majuscule, miniscule, chiffre
        if (!preg_match($regex, $password)) {
            $this->errors[]=$this->config["inputs"]["password"]["error"];
            return false;
        }
        return true;
    }
    public function isPhoneNumberValid(int $phoneNumber): bool
    {
        $regex =  '/^[67][0-9]{8}$/';//regex pour verifier le format du numéro de téléphone ex: [06 | 07] 12 34 56 78
        if (!preg_match($regex, $phoneNumber)) {
            $this->errors[]=$this->config["inputs"]["phone_number"]["error"];
            echo 'regex err phone';
            return false;
        }
        return true;
    }
    public function isFieldsInfoValid($model, array $fields): bool
    {
        $class = explode('\\', get_class($model));
        $repo = "App\\Repository\\".ucfirst(end($class))."Repository";
        $resultQuery = $repo::existOrNot($fields, $model);
        if(is_bool($resultQuery)){
            //il n'y a aucun elements dans la table donc on return true
            return true;
        }

        $found = false;
        $column = "";

        //verifier si le resultat de la requete contiens l'une des clés de $whereSql
        foreach (array_keys($fields) as $key) {
            if (strpos($resultQuery["column_exists"], $key) !== false) {
                $found = true;
                $column = $key;
                break;
            }
        }
        if ($found) {

            $this->errors[]= "$column dèja utilisé";
            return false;
        }
        return true;
    }
    public static function isEmpty(String $string): bool
    {
        return empty(trim($string));
    }
    public static function isMinLength(String $string, $length): bool
    {
        return strlen(trim($string))>=$length;
    }
    public static function isMaxLength(String $string, $length): bool
    {
        return strlen(trim($string))<=$length;
    }

}