<?php
namespace App\Forms;
use App\Core\Validator;
class EditProfileFront extends Validator
{
    public string $method = "POST";
    protected array $config = [];
    public function getConfig($roles): array
    {
        $this->config = [
            "config"=>[
                "method"=>$this->method,
                "action"=>"/edit-profile",
                "id"=>"edit-user-form",
                "class"=>"form",
                "enctype"=>"",
                "submitLabel"=>"Modifier un utilisateur",
                "submitName"=>"submitEditUser",
                "reset"=>"Annuler"
            ],
            "inputs"=>[
                "pseudo"=>[
                    "id"=>"edit-user-form-pseudo",
                    "name"=>"setPseudo",
                    "class"=>"form-input",
                    "label"=>"Pseudo",
                    "placeholder"=>"Pseudo",
                    "type"=>"text",
                    "error"=>"Le pseudo existe dèja",
                    "min"=>2,
                    "max"=>60,
                ],
                "first_name"=>[
                    "id"=>"edit-user-form-firstName",
                    "name"=>"setFirstName",
                    "class"=>"form-input",
                    "label"=>"Prénom",
                    "placeholder"=>"Prénom",
                    "type"=>"text",
                    "error"=>"Le prénom doit faire entre 2 et 60 caractères",
                    "min"=>2,
                    "max"=>60,
                ],
                "last_name"=>[
                    "id"=>"edit-user-form-lastName",
                    "name"=>"setLastName",
                    "class"=>"form-input",
                    "label"=>"Nom",
                    "placeholder"=>"Nom",
                    "type"=>"text",
                    "error"=>"Le nom doit faire entre 2 et 120 caractères",
                    "min"=>2,
                    "max"=>120,
                ],
                "phone_number"=>[
                    "id"=>"edit-user-form-phoneNumber",
                    "name"=>"setPhoneNumber",
                    "class"=>"form-input",
                    "label"=>"Téléphone",
                    "placeholder"=>"ex : 06 12 34 56 78",
                    "type"=>"tel",
                    "pattern"=>"^0[67][0-9]{8}$",
                    "min"=>10,
                    "max"=>10,
                    "error"=>"Numéro de téléphone incorrect",
                ],
            ]
        ];
        return $this->config;
    }
}