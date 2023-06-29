<?php
namespace App\Forms;
use App\Core\Validator;

class CreateArticleGame extends Validator
{
    public $method = "POST";
    protected array $config = [];
    public function getConfig(): array
    {
        return $this->config;
    }
    public function setConfig(array $optionsCategoriesGame): void
    {
        $this->config = [
                "config"=>[
                    "method"=>$this->method,
                    "action"=>"", 
                    "id"=>"createArticleGame-form",
                    "class"=>"form",
                    "enctype"=>"multipart/form-data",
                    "submitLabel"=>"Créer l'article",
                    "submitName"=>"submitCreateArticleGame",
                    "reset"=>"Annuler"
                ],
                "inputs"=>[
                    "titleGame"=>[
                        "id"=>"createArticleGame-form-titleGame",
                        "class"=>"form-input",
                        "placeholder"=>"Votre titre de jeu et de l'article",
                        "type"=>"text",
                        "error"=>"Votre titre de jeu est trop court",
                        "min"=>2,
                        "required"=>true
                    ],
                    "categoryGame"=>[
                        "id"=>"createArticleGame-form-categoryGame",
                        "class"=>"form-input",
                        "type"=>"select",
                        "error"=>"La categorie n'existe pas",
                        "options"=> $optionsCategoriesGame,
                        "required"=>true
                    ],
                    "imageGame"=>[
                        "id"=>"createArticleGame-form-imageGame",
                        "class"=>"form-input",
                        "type"=>"file",
                        "error"=>"",
                        "required"=>true,
                        "multiple"=>false,
                        "label"=>"Ajouter un logo a votre jeu"
                    ],
                    "content"=>[
                        "id"=>"createArticleGame-form-content",
                        "class"=>"form-input",
                        "placeholder"=>"Votre contenu de l'article",
                        "type"=>"text",
                        "error"=>"Votre contenu est trop court",
                        "min"=>5,
                        "required"=>true
                    ],
                    "imagesArticle[]"=>[
                        "id"=>"createArticleGame-form-imageArticle",
                        "class"=>"form-input",
                        "type"=>"file",
                        "error"=>"",
                        "required"=>true,
                        "multiple"=>true,
                        "label"=>"Ajouter des images a votre article"
                    ],
                ]
        ];

    }
}