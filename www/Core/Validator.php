<?php

namespace App\Core;

class Validator
{
    private array $data = [];
    public array $errors = [];
    public function isSubmited(): bool
    {
        $this->data = ($this->method == "POST")?$_POST:$_GET;
        if(isset($this->data["submit"])){
            return true;
        }
        return false;
    }
    public function isValid(): bool
    {
        //TODO 
        //ajouter la verification si l'email et le pseudo existe deja 
        //ajouter une verification pour le numéro de telephone 

        //La bonne method ? -> tester si la request method (POST / GET / PUT ect...) est la meme que celle qui est attendu par la classe enfant
        if($_SERVER["REQUEST_METHOD"] != $this->method){
            die("Tentative de Hack");
        }
        //Le nb de inputs -> pour tester si le nombre d'input envoyer et le meme que celui qui est attendu par la classe enfant
        if(count($this->config["inputs"])+1 != count($this->data)){ //+1 car "submit" est envoyé aussi
            die("Tentative de Hack");
        }

        //tester les inputs envoyer
        foreach ($this->config["inputs"] as $name=>$configInput){
            //tester si le nom de l'input est attendu 
            if(!isset($this->data[$name])){
                die("Tentative de Hack");
            }
            //tester dans le cas ou l'input ne doit pas etre vide(required) 
            if(isset($configInput["required"]) && self::isEmpty($this->data[$name])){
                die("Tentative de Hack");
            }
            //tester si l'input a un minimum de taille 
            if(isset($configInput["min"]) && !self::isMinLength($this->data[$name], $configInput["min"])){
                $this->errors[]=$configInput["error"];
            }
            //tester si l'input a un maximum de taille 
            if(isset($configInput["max"]) && !self::isMaxLength($this->data[$name], $configInput["max"])){
                $this->errors[]=$configInput["error"];
            }
        }
        //tester si il y a une erreur lors de la validation 
        if(empty($this->errors)){
            return true;
        }
        return false;
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