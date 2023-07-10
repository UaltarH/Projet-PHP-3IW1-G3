<?php

namespace App\Controllers;

use App\Core\SQL;
use App\Core\View;

use App\Models\Article AS ArticleModel;

class Article extends SQL
{
    public function getArticle(){
        $view = new View("Article/article", "front");
        //tester si il y a un id dans l'url( avec GET )(le numéro represente l'id de l'article en bdd)
        if(isset($_GET['number'])){
            //si oui tester si il existe en base un article qui possede cette id :
            $article = new ArticleModel();
            $whereSql = ["id" => $_GET['number']];
            $resultQuery = $article->getOneWhere($whereSql);
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

    public function GetAllArticlesGame(){
        $view = new View("Article/allArticlesGame", "front");
        $article = new ArticleModel();
        $whereSql = ["category_name" => "Jeux"];
        $fkInfosQuery = [
            [
                "table" => "carte_chance_category_article",
                "foreignKeys" => [
                    "originColumn" => "category_id",
                    "targetColumn" => "id"
                ]
            ]
        ];
        //"Trucs et astuces"
        $resultQuery = $article->selectWithFkAndWhere($fkInfosQuery,$whereSql);

        if(is_bool($resultQuery)) { //
            //article not found:                
            $view->assign("error", 'Article Not Found');
        } else{
            //article found:
            $view->assign("articles", $resultQuery);
        }
    }

    public function GetAllArticlesAboutGame(){
        $view = new View("Article/allArticlesAboutGame", "front");
        $article = new ArticleModel();
        $whereSql = ["category_name" => "Trucs et astuces"];
        $fkInfosQuery = [
            [
                "table" => "carte_chance_category_article",
                "foreignKeys" => [
                    "originColumn" => "category_id",
                    "targetColumn" => "id"
                ]
            ]
        ];
        //"Trucs et astuces"
        $resultQuery = $article->selectWithFkAndWhere($fkInfosQuery,$whereSql);

        if(is_bool($resultQuery)) { //
            //article not found:                
            $view->assign("error", 'Article Not Found');
        } else{
            //article found:
            $view->assign("articles", $resultQuery);
        }
    }
}