<?php
namespace App\Forms;
use App\Core\Validator;

class CreateArticleAboutGame extends Validator
{
    public $method = "POST";
    protected array $config = [];
    public function getConfig(array $optionsCategoriesArticle, array $optionsGames): array
    {
        $this->config = [
                "config"=>[
                    "method"=>$this->method,
                    "action"=>"", 
                    "id"=>"createArticleGame-form",
                    "class"=>"form",
                    "enctype"=>"multipart/form-data",
                    "submitLabel"=>"Créer l'article",
                    "submitName"=>"submitCreateArticleAboutGame",
                    "reset"=>"Annuler"
                ],
                "inputs"=>[
                    "categoryArticle"=>[
                        "id"=>"createArticleAboutGame-form-categoryArticle",
                        "class"=>"form-input",
                        "type"=>"select",
                        "error"=>"La categorie n'existe pas",
                        "options"=> $optionsCategoriesArticle,
                        "required"=>true
                    ],
                    "game"=>[
                        "id"=>"createArticleAboutGame-form-Game",
                        "class"=>"form-input",
                        "type"=>"select",
                        "error"=>"Votre jeu n'existe pas",
                        "options"=> $optionsGames,
                        "required"=>true
                    ],
                    "titleArticle"=>[
                        "id"=>"createArticleAboutGame-form-titleArticle",
                        "class"=>"form-input",
                        "placeholder"=>"Votre titre de l'article",
                        "type"=>"text",
                        "error"=>"Votre titre est trop court",
                        "min"=>5,
                        "required"=>true
                    ],
                    "content"=>[
                        "id"=>"createArticleAboutGame-form-content",
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
        return $this->config;
    }
}