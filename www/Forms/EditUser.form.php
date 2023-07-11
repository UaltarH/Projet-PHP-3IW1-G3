<?php
namespace App\Forms;
use App\Core\Validator;
class EditUser extends Validator
{
    public string $method = "POST";
    protected array $config = [];
    public function getConfig($roles): array
    {
        $this->config = [
            "config"=>[
                "method"=>$this->method,
                "action"=>"",
                "id"=>"edit-user-form",
                "class"=>"form",
                "enctype"=>"",
                "submitLabel"=>"Modifier un utilisateur",
                "submitName"=>"submitEditUser",
                "reset"=>"Réinitialiser"
            ],
            "inputs"=>[
                "pseudo"=>[
                    "id"=>"edit-user-form-pseudo",
                    "class"=>"form-input",
                    "label"=>"Pseudo",
                    "placeholder"=>"Pseudo",
                    "type"=>"text",
                    "error"=>"Le pseudo existe dèja",
                    "min"=>2,
                    "max"=>60,
                ],
                "first_name"=>[
                    "id"=>"edit-user-form-firstname",
                    "class"=>"form-input",
                    "label"=>"Prénom",
                    "placeholder"=>"Prénom",
                    "type"=>"text",
                    "error"=>"Le prénom doit faire entre 2 et 60 caractères",
                    "min"=>2,
                    "max"=>60,
                ],
                "last_name"=>[
                    "id"=>"edit-user-form-lastname",
                    "class"=>"form-input",
                    "label"=>"Nom",
                    "placeholder"=>"Nom",
                    "type"=>"text",
                    "error"=>"Le nom doit faire entre 2 et 120 caractères",
                    "min"=>2,
                    "max"=>120,
                ],
                "email"=>[
                    "id"=>"edit-user-form-email",
                    "class"=>"form-input",
                    "label"=>"Email",
                    "placeholder"=>"E-mail",
                    "type"=>"email",
                    "error"=>"L'email est incorrect",
                ],
                "phone_number"=>[
                    "id"=>"edit-user-form-phoneNumber",
                    "class"=>"form-input",
                    "label"=>"Téléphone",
                    "placeholder"=>"ex : 06 12 34 56 78",
                    "type"=>"tel",
                    "pattern"=>"^0[67][0-9]{8}$",
                    "min"=>10,
                    "max"=>10,
                    "error"=>"Numéro de téléphone incorrect",
                ],
                //TODO : select join role/user
                "role"=>[
                    "id"=>"edit-user-form-role",
                    "class"=>"form-input",
                    "label"=>"Role",
                    "placeholder"=>"Role",
                    "type"=>"select",
                    "options"=>$roles,
                    "error"=>"Role inexistant",
                ],
                "password"=>[
                    "id"=>"edit-user-form-pwd",
                    "class"=>"form-input",
                    "label"=>"Mot de passe",
                    "placeholder"=>"Mot de passe",
                    "type"=>"password",
                    "error"=>"Le mot de passe doit faire au minimum 8 caractères avec minuscules, majuscules et chiffres",
                    "min"=>8,
                ],
                "passwordConfirm"=>[
                    "id"=>"edit-user-form-pwd-confirm",
                    "class"=>"form-input",
                    "label"=>"Confirmation",
                    "placeholder"=>"Confirmez votre mot de passe",
                    "type"=>"password",
                    "error"=>"Le mot de passe de confirmation ne correspond pas",
                ],
            ]
        ];
        return $this->config;
    }
}