<?php

namespace App\Controllers;

use App\Core\View;

use App\Models\Article AS ArticleModel;

class Article
{
    public function getPage(){
        //recuperer l'article en base de donnée:
        //tester si il y a un id dans l'url( avec GET )(le numéro represente l'id de l'article en bdd)
        if(isset($_GET['number'])){
            //si oui tester si il existe en base un article qui possede cet id :
            $article = new ArticleModel();
            $whereSql = ["id" => $_GET['number']];
            $resultQuery = $article->getOneWhere($whereSql);
            if(is_bool($resultQuery)) { //
                //article not found:
                $view = new View("Common/NotAccess", "unauthorised");
                $messageInfo['noArticleFound'] = "La page que vous essayer d'acceder n'existe pas vous allez etre rediriger vers la page home" ;
                $view->assign("messageInfo", $messageInfo);
                $view->assign("typeError", 'noArticleFound');
            } else{
                //article found:
                $view = new View("Article/article", "front");
                $view->assign("titre", $resultQuery->getTitle());
                $view->assign("content", $resultQuery->getContent());
            }
        } else {
            //si non retourner une erreur 404 ou une redirection vers la page d'accueil
            $view = new View("Common/NotAccess", "unauthorised");
            $messageInfo['noArticleFound'] = "La page que vous essayer d'acceder n'existe pas vous allez etre rediriger vers la page home" ;
            $view->assign("messageInfo", $messageInfo);
            $view->assign("typeError", 'noArticleFound');
        }
        
    }
}