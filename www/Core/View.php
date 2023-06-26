<?php
namespace App\Core;

use App\Models\Article;
use App\Models\Category_article;


class View {

    private String $view;
    private String $template;
    private $data = [];

    public function __construct(String $view, String $template = "back", int $roleId = 0) {
        $this->setView($view);
        $this->setTemplate($template);
        $this->setMenuOptions($roleId);
    }

    public function assign(String $key, $value): void
    {
        $this->data[$key] = $value;
    }

    /**
     * @param String $view
     */
    public function setView(string $view): void
    {
        $this->view = "Views/".$view.".view.php";
        if(!file_exists($this->view)){
            die("La vue ".$this->view." n'existe pas");
        }
    }

    /**
     * @param String $template
     */
    public function setTemplate(string $template): void
    {
        $this->template = "Views/".$template.".tpl.php";
        if(!file_exists($this->template)){
            die("Le template ".$this->template." n'existe pas");
        }
    }

    public function partial(String $name, array $config, $errors = []): void
    {
        if(!file_exists("Views/Partials/".$name.".ptl.php")){
            die("Le partial ".$name." n'existe pas");
        }
        include "Views/Partials/".$name.".ptl.php";
    }

    public function setMenuOptions(int $userRoleId): void
    {
        if($this->template != 'unauthorised'){
            //set options for the menu depanding on the template used        
            //get articles and categories with query which have a foreign key bteewen them
            $article = new Article();
            //utiliser la methode selectWithFk avec la var  $article, le parametre vas contenir un array de cette forme :
            $fkInfosQuery = [
                [
                    "table" => "carte_chance_category_article",
                    "foreignKeys" => [
                        "originColumn" => "category_id",
                        "targetColumn" => "id"
                    ]
                ]
            ];
            $resultQuery = $article->selectWithFk($fkInfosQuery);

            //create array used for fill menu links 
            $informationsToFillMenu = [];

            $informationsToFillMenu["Home"] = "/default";
            $informationsToFillMenu["Connection"] = "/login";
            $informationsToFillMenu["Deconnection"] = "/logout";
            $informationsToFillMenu["Inscription"] = "/s-inscrire";
            foreach ($resultQuery as $row) {
                $category = $row['category_name'];
                $articleName = $row['title'];
                $articleId = $row['id'];
            
                // Si la catégorie n'existe pas dans le tableau, l'ajouter
                if (!isset($informationsToFillMenu["Articles"]["categories"][$category])) {
                    $informationsToFillMenu["Articles"]["categories"][$category] = ['links' => []];
                }
            
                // Ajouter l'article à la catégorie correspondante
                $informationsToFillMenu["Articles"]["categories"][$category]['links'][$articleName] = "page?number=" .$articleId;
            }

            //depanding of the template change links menu 
            if($userRoleId == 2){ //is admin ajouter les pages d'admin
                $informationsToFillMenu["Admin"]["categories"]["Article"]["links"]["Page managment"] = "/page-managment";
            }

            $this->assign("menuOpt", $informationsToFillMenu);
        }       
    }

    public function __destruct(){
        extract($this->data);
        include $this->template;
    }


}