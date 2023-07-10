<?php
namespace App\Forms;
use App\Core\Validator;
class ModerateComment extends Validator
{
    public string $method = "POST";
    protected array $config = [];
    public function getConfig($comment): array
    {
        $this->config = [
            "config"=>[
                "method"=>$this->method,
                "action"=>"/sys/comment/moderate",
                "id"=>"moderate-comment-form",
                "class"=>"form",
                "enctype"=>"",
                "submitLabel"=>"Modérer le commentaire",
                "submitName"=>"submitModerateComment",
                "reset"=>"Annuler"
            ],
            "inputs"=>[
                "creation_date"=>[
                    "id"=>"moderate-comment-form-creation_date",
                    "value"=>$comment->getCreationDate(),
                    "class"=>"form-control",
                    "readonly"=>true,
                    "label"=>"Date de création :",
                    "type"=>"text",
                ],
                "content"=>[
                    "id"=>"moderate-comment-form-content",
                    "value"=>$comment->getContent(),
                    "class"=>"form-control",
                    "readonly"=>true,
                    "label"=>"Contenu du commentaire :",
                    "type"=>"text",
                ],
                "accepted"=>[
                    "id"=>"moderate-comment-form-accepted",
                    "value"=>"setAccepted",
                    "class"=>"",
                    "readonly"=>false,
                    "type"=>"checkbox",
                    "label"=>"Accepter :",
                ],
            ]
        ];
        return $this->config;
    }
}