<?php
namespace App\Forms;
use App\Core\Validator;

class ResetPassword extends Validator
{
    public $method = "POST";
    protected array $config = [];
    public function getConfig(): array
    {
        $this->config = [
                "config"=>[
                    "method"=>$this->method,
                    "action"=>"", 
                    "id"=>"resetPassword-form",
                    "class"=>"form",
                    "enctype"=>"",
                    "submitLabel"=>"Changer de mot de passe",
                    "submitName"=>"submit",
                    "reset"=>"Annuler"
                ],
                "inputs"=>[
                    "password"=>[
                        "id"=>"resetPassword-form-password",
                        "class"=>"form-input",
                        "placeholder"=>"Votre nouveau mot de passe",
                        "type"=>"password",
                        "error"=>"Votre mot de passe doit faire au minimum 8 caractères avec minimum 1 minuscules, 1 majuscules et 1 chiffre",
                        "min"=>8,
                        "required"=>true
                    ],
                    "passwordConfirm"=>[
                        "id"=>"resetPassword-form-passwordConfirm",
                        "class"=>"form-input",
                        "placeholder"=>"Confirmez votre mot de passe",
                        "type"=>"password",
                        "error"=>"Votre mot de passe ne correspond pas",
                        "required"=>true
                    ]
                ]
        ];
        return $this->config;
    }
}