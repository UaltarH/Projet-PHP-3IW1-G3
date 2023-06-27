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
                        "placeholder"=>"ex : 06 12 34 56 78",
                        "type"=>"tel",
                        "pattern"=>"^0[67][0-9]{8}$",
                        "min"=>10,
                        "max"=>10,
                        "error"=>"Le numéro de téléphone est incorrect",
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
}