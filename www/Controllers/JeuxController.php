<?php

namespace App\Controllers;

use App\Core\View;

use App\Models\Article as ArticleModel;
use App\Models\Category_jeux;
use App\Models\Comment;
use App\Models\Jeux;
use App\Models\JoinTable\Article_jeux;

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
        $articleModel = new Article();
        $articleJeuModel = new Article_jeux();

        $whereSql = ["title" => $_GET["id"]];
        $jeu = $jeuxModel->getOneWhere($whereSql);

        $whereSql = ["id" => $jeu->getCategory_id()];
        $categorie = $categorieJeuxModel->getOneWhere($whereSql);

        $whereSql = ["id" => $jeu->getCategory_id()];
        $comments = $commentModel->getAllWhere($whereSql);

        $view->assign("jeu", $jeu);
        $view->assign("categorie", $categorie);
        $view->assign("comments", $comments);
    }
}