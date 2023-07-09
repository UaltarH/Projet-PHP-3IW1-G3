<?php
namespace App\Forms;
use App\Core\Validator;
class CreateUser extends Validator
{
    public string $method = "POST";
    protected array $config = [];
    public function getConfig($roles): array
    {
        $this->config = [
            "config"=>[
                "method"=>$this->method,
                "action"=>"",
                "id"=>"create-user-form",
                "class"=>"form inline-form",
                "enctype"=>"",
                "submitLabel"=>"Ajouter un utilisateur",
                "submitName"=>"submitCreateUser",
                "reset"=>"Annuler"
            ],
            "inputs"=>[
                "pseudo"=>[
                    "id"=>"create-user-form-pseudo",
                    "class"=>"form-input",
                    "label"=>"Pseudo",
                    "placeholder"=>"Pseudo",
                    "type"=>"text",
                    "error"=>"Le pseudo existe dèja",
                    "min"=>2,
                    "max"=>60,
                    "required"=>true
                ],
                "first_name"=>[
                    "id"=>"create-user-form-firstname",
                    "class"=>"form-input",
                    "label"=>"Prénom",
                    "placeholder"=>"Prénom",
                    "type"=>"text",
                    "error"=>"Le prénom doit faire entre 2 et 60 caractères",
                    "min"=>2,
                    "max"=>60,
                    "required"=>true
                ],
                "last_name"=>[
                    "id"=>"create-user-form-lastname",
                    "class"=>"form-input",
                    "label"=>"Nom",
                    "placeholder"=>"Nom",
                    "type"=>"text",
                    "error"=>"Le nom doit faire entre 2 et 120 caractères",
                    "min"=>2,
                    "max"=>120,
                    "required"=>true
                ],
                "email"=>[
                    "id"=>"create-user-form-email",
                    "class"=>"form-input",
                    "label"=>"E-mail",
                    "placeholder"=>"E-mail",
                    "type"=>"email",
                    "error"=>"L'email est incorrect",
                    "required"=>true
                ],
                "phone_number"=>[
                    "id"=>"create-user-form-phoneNumber",
                    "class"=>"form-input",
                    "label"=>"Téléphone",
                    "placeholder"=>"ex : 06 12 34 56 78",
                    "type"=>"tel",
                    "pattern"=>"^0[67][0-9]{8}$",
                    "min"=>10,
                    "max"=>10,
                    "error"=>"Numéro de téléphone incorrect",
                    "required"=>true
                ],
                //TODO : select join role/user
                "role"=>[
                    "id"=>"create-user-form-role",
                    "class"=>"form-input",
                    "label"=>"Role",
                    "placeholder"=>"Role",
                    "type"=>"select",
                    "options"=>$roles,
                    "error"=>"Role inexistant",
                    "required"=>true
                ],
            ]
        ];
        return $this->config;
    }
}