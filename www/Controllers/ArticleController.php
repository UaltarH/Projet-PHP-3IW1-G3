<?php

namespace App\Controllers;

use App\Core\Config;
use App\Core\View;

use App\Models\Article;
use App\Repository\AbstractRepository;
use App\Repository\ArticleRepository;

class ArticleController extends AbstractRepository
{
    private array $config;
    private ArticleRepository $articleRepository;
    public function __construct() {
        $this->config = Config::getInstance()->getConfig();
        $this->articleRepository = new ArticleRepository();
    }
    public function getArticle(): void
    {
        $view = new View("Article/article", "front");
        //tester si il y a un id dans l'url( avec GET )(le numéro represente l'id de l'article en bdd)
        if(isset($_GET['number'])){
            //si oui tester si il existe en base un article qui possede cette id :
            $article = new Article();
            $whereSql = ["id" => $_GET['number']];
            $resultQuery = $this->articleRepository->getOneWhere($whereSql, $article);
            if(is_bool($resultQuery)) { //
                //article not found:                
                $view->assign("error", 'Article Not Found');
            } else{
                //article found:
                $view->assign("titre", $resultQuery->getTitle());
                $view->assign("content", $resultQuery->getContent());
            }
        } else {
            //si non retourner une erreur 404 ou une redirection vers la page d'accueil
            $view->assign("error", 'Article Not Found');
        }        
    }

    public function GetAllArticlesGame(): void
    {
        $view = new View("Article/allArticlesGame", "front");
        $article = new Article();
        $whereSql = ["category_name" => "Jeux"];
        $fkInfosQuery = [
            [
                "table" => $this->config['bdd']['prefix']."article_category",
                "foreignKeys" => [
                    "originColumn" => "category_id",
                    "targetColumn" => "id"
                ]
            ]
        ];
        //"Trucs et astuces"
        $resultQuery = $this->articleRepository->selectWithFkAndWhere($fkInfosQuery,$whereSql,$article);

        if(is_bool($resultQuery)) { //
            //article not found:                
            $view->assign("error", 'Article Not Found');
        } else{
            //article found:
            $view->assign("articles", $resultQuery);
        }
    }

    public function GetAllArticlesAboutGame(): void
    {
        $view = new View("Article/allArticlesAboutGame", "front");
        $article = new Article();
        $whereSql = ["category_name" => "Trucs et astuces"];
        $fkInfosQuery = [
            [
                "table" => $this->config['bdd']['prefix']."article_category",
                "foreignKeys" => [
                    "originColumn" => "category_id",
                    "targetColumn" => "id"
                ]
            ]
        ];
        //"Trucs et astuces"
        $resultQuery = $this->articleRepository->selectWithFkAndWhere($fkInfosQuery,$whereSql,$article);

        if(is_bool($resultQuery)) { //
            //article not found:                
            $view->assign("error", 'Article Not Found');
        } else{
            //article found:
            $view->assign("articles", $resultQuery);
        }
    }
}