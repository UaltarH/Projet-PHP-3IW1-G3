<?php
namespace App\Forms;
use App\Core\Validator;
use App\Core\SQL;

class Register extends Validator
{
    public $method = "POST";
    protected array $config = [];
    public function getConfig(): array
    {
        $this->config = [
                "config"=>[
                    "method"=>$this->method,
                    "action"=>"",
                    "id"=>"register-form",
                    "class"=>"form",
                    "enctype"=>"",
                    "submit"=>"Nous rejoindre",
                    "reset"=>"Annuler"
                ],
                "inputs"=>[
                    "pseudo"=>[
                        "id"=>"register-form-pseudo",
                        "class"=>"form-input",
                        "placeholder"=>"Votre pseudo",
                        "type"=>"text",
                        "error"=>"Votre pseudo existe dèja",
                        "min"=>2,
                        "max"=>60,
                        "required"=>true
                    ],
                    "first_name"=>[
                        "id"=>"register-form-firstname",
                        "class"=>"form-input",
                        "placeholder"=>"Votre prénom",
                        "type"=>"text",
                        "error"=>"Votre prénom doit faire entre 2 et 60 caractères",
                        "min"=>2,
                        "max"=>60,
                        "required"=>true
                    ],
                    "last_name"=>[
                        "id"=>"register-form-lastname",
                        "class"=>"form-input",
                        "placeholder"=>"Votre nom",
                        "type"=>"text",
                        "error"=>"Votre nom doit faire entre 2 et 120 caractères",
                        "min"=>2,
                        "max"=>120,
                        "required"=>true
                    ],
                    "email"=>[
                        "id"=>"register-form-email",
                        "class"=>"form-input",
                        "placeholder"=>"Votre email",
                        "type"=>"email",
                        "error"=>"Votre email est incorrect",
                        "required"=>true
                    ],
                    "phone_number"=>[
                        "id"=>"register-form-phoneNumber",
                        "class"=>"form-input",
                        "placeholder"=>"Votre numéro de telephone",
                        "type"=>"number",
                        "number"=>"Votre numéro est incorrect",
                        "required"=>true
                    ],
                    "password"=>[
                        "id"=>"register-form-pwd",
                        "class"=>"form-input",
                        "placeholder"=>"Votre mot de passe",
                        "type"=>"password",
                        "error"=>"Votre mot de passe doit faire au minimum 8 caractères avec minuscules, majuscules et chiffres",
                        "min"=>8,
                        "required"=>true
                    ],
                    "passwordConfirm"=>[
                        "id"=>"register-form-pwd-confirm",
                        "class"=>"form-input",
                        "placeholder"=>"Confirmation",
                        "type"=>"password",
                        "error"=>"Votre mot de passe de confirmation ne correspond pas",
                        "required"=>true
                    ],
                ]
        ];
        return $this->config;
    }

    public function isValidSpecific($user, string $password, string $passwordConfirm, string $email, string $pseudo): bool
    {
        //todo tester si le password est identique + tester si l'email et l'email existe deja 
        if($password != $passwordConfirm){
            $this->errors[]=$this->config["inputs"]["passwordConfirm"]["error"];
            return false;
        }
        $regex = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)/'; //regex pour verifier si le psw contiens au moins 1 majuscule, miniscule, chiffre
        if (!preg_match($regex, $password)) {
            $this->errors[]=$this->config["inputs"]["password"]["error"];
            return false;
        }

        $whereSql = ["pseudo" => $pseudo, "email" => $email];
        $resultQuery = $user->existOrNot($whereSql);
        if(is_bool($resultQuery)){ 
            //il n'y a aucun elements dans la table donc on return true
            return true;
        }

        $found = false;
        $column = "";

        //verifier si le resultat de la requete contiens l'une des clés de $whereSql 
        foreach (array_keys($whereSql) as $key) {
            if (strpos($resultQuery["column_exists"], $key) !== false) {
                $found = true;
                $column = $key;
                break;
            }
        }
        
        if ($found) {
            //email or pseudo already exist
            switch($column) {
                case "pseudo":
                    $this->errors[]= "le pseudo est dèja utilisé";
                case "email":
                    $this->errors[]= "l'email est dèja utilisé";
            }            
            return false;
        } 
        return true;
    }
}