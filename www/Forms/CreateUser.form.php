<?php
namespace App\Forms;
use App\Core\Validator;
class CreateUser extends Validator
{
    public string $method = "POST";
    protected array $config = [];
    public function getConfig(): array
    {
        $this->config = [
            "config"=>[
                "method"=>$this->method,
                "action"=>"/sys/user/list?action=add",
                "id"=>"createUser-form",
                "class"=>"form",
                "enctype"=>"",
                "submitLabel"=>"Ajouter un utilisateur",
                "submitName"=>"submitCreateUser",
                "reset"=>"Annuler"
            ],
            "inputs"=>[
                "pseudo"=>[
                    "id"=>"create-user-form-pseudo",
                    "class"=>"form-input",
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
                    "placeholder"=>"E-mail",
                    "type"=>"email",
                    "error"=>"L'email est incorrect",
                    "required"=>true
                ],
                //TODO : check phone number format
                "phone_number"=>[
                    "id"=>"create-user-form-phoneNumber",
                    "class"=>"form-input",
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
                    "placeholder"=>"Role",
                    "type"=>"number",
                    "error"=>"Role inexistant",
                    "required"=>true
                ],
            ]
        ];
        return $this->config;
    }
}