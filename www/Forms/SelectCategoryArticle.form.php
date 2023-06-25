<?php
namespace App\Forms;
use App\Core\Validator;

class SelectCategoryArticle extends Validator
{
    public $method = "POST";
    protected array $config = [];
    public function getConfig(array $options): array
    {
        $this->config = [
                "config"=>[
                    "method"=>$this->method,
                    "action"=>"", 
                    "id"=>"SelectCategory-form",
                    "class"=>"form",
                    "enctype"=>"",
                    "submitLabel"=>"Valider",
                    "submitName"=>"submitSelectCategoryArticle",
                    "reset"=>"Annuler"
                ],
                "inputs"=>[
                    "categoryArticle"=>[
                        "id"=>"SelectCategory-form-category",
                        "class"=>"form-input",
                        "type"=>"select",
                        "error"=>"La categorie n'existe pas",
                        "options"=> $options,
                        "required"=>true
                    ]
                ]
        ];
        return $this->config;
    }
}