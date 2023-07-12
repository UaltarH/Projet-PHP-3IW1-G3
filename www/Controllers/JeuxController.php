<?php

namespace App\Controllers;

use App\Core\View;

use App\Models\Article;
use App\Models\Category_jeux;
use App\Models\Comment;
use App\Models\Jeux;
use App\Models\JoinTable\Article_jeux;
use App\Models\JoinTable\Comment_article;

class JeuxController
{
    public function allgames()
    {
        $view = new View("Jeux/allGames", "front");
        $jeuxModel = new Jeux();
        $categorieJeuxModel = new Category_jeux();

        $jeux = $jeuxModel->selectAll();
        $categories = $categorieJeuxModel->selectAll();

        $result = [];
        foreach ($jeux as $index => $value) {
            foreach ($categories as $categorie) {
                if ($value->getCategory_id() == $categorie->getId()) {
                    $result[] = ["title" => $value->getTitle(), "categorie" => $categorie->getCategoryName()];
                }
            }
        }

        $view->assign("jeux", $result);
    }

    public function oneGame()
    {
        $view = new View("Jeux/oneGame", "front");
        $jeuxModel = new Jeux();
        $categorieJeuxModel = new Category_jeux();
        $commentModel = new Comment();
        $articleJeuModel = new Article_jeux();
        $commentArticleModel = new Comment_article();
        $articleModel = new Article();

        $whereSql = ["title" => $_GET["id"]];
        $jeu = $jeuxModel->getOneWhere($whereSql);

        $whereSql = ["id" => $jeu->getCategory_id()];
        $categorie = $categorieJeuxModel->getOneWhere($whereSql);

        $view->assign("jeu", $jeu);
        $view->assign("categorie", $categorie);

        $whereSql = ["jeux_id" => $jeu->getId()];
        $articlesJeu = $articleJeuModel->getAllWhere($whereSql);

        if ($articlesJeu) {
            $articles = [];
            $commentsByArticles = [];
            foreach ($articlesJeu as $articleJeu){
                $whereSql = ["id" => $articleJeu->getArticleId()];
                $article = $articleModel->getOneWhere($whereSql);
                $articles[] = $article;

                $whereSql = ["article_id" => $articleJeu->getArticleId()];
                $commentArticle = $commentArticleModel->getOneWhere($whereSql);

                if ($commentArticle) {
                    $whereSql = ["id" => $commentArticle->getCommentId()];
                    $comments = $commentModel->getAllWhere($whereSql);
                    $commentsByArticles[] = ["articleId" => $article->getId(), "comments" => $comments];
                }
            }
            $view->assign("articles", $articles);
            $view->assign("commentsByArticles", $commentsByArticles);
        }
    }
}