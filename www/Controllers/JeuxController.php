<?php

namespace App\Controllers;

use App\Core\View;

use App\Models\Article;
use App\Models\Category_jeux;
use App\Models\Comment;
use App\Models\Jeux;
use App\Models\JoinTable\Article_jeux;
use App\Models\JoinTable\Comment_article;
use App\Models\Article AS ArticleModel;
use App\Models\Game_Category;
use App\Models\Game;
use App\Repository\GameCategoryRepository;
use App\Repository\GameRepository;

class JeuxController
{
    private GameRepository $gameRepository;
    private GameCategoryRepository $gameCategoryRepository;
    public function __construct() {
        $this->gameRepository = new GameRepository();
        $this->gameCategoryRepository = new GameCategoryRepository();
    }
    public function allgames(){
        $view = new View("Jeux/allGames", "front");
        $jeux = $this->gameRepository->selectAll(new Game);
        $categories = $this->gameCategoryRepository->selectAll(new Game_Category());

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
        $view = new View("Article/allArticles", "front");
        $jeuxModel = $this->gameRepository;
        $categorieJeuxModel = $this->gameCategoryRepository;
        $commentModel = $this->commentRepository;
        $articleJeuModel = $this->gameArticleRepository;
        $commentArticleModel = $this->commentArticleRepository;
        $articleModel = $this->articleRepository;

        $whereSql = ["title_game" => "Poker"];
        $jeu = $jeuxModel->getOneWhere($whereSql, new Game());

        $whereSql = ["id" => $jeu->getCategory_id()];
        $categorie = $categorieJeuxModel->getOneWhere($whereSql, new Game_Category());

        $view->assign("jeu", $jeu);
        $view->assign("categorie", $categorie);

        $whereSql = ["jeux_id" => $jeu->getId()];
        $articlesJeu = $articleJeuModel->getAllWhere($whereSql, new Game_Article());

        if ($articlesJeu) {
            $articles = [];
            $commentsByArticles = [];
            foreach ($articlesJeu as $articleJeu){
                $whereSql = ["id" => $articleJeu->getArticleId()];
                $article = $articleModel->getOneWhere($whereSql, new \App\Models\Article());
                $articles[] = $article;

                $whereSql = ["article_id" => $articleJeu->getArticleId()];
                $commentArticle = $commentArticleModel->getOneWhere($whereSql, new Comment_article());

                if ($commentArticle) {
                    $whereSql = ["id" => $commentArticle->getCommentId()];
                    $comments = $commentModel->getAllWhere($whereSql, new Comment());
                    $commentsByArticles[] = ["articleId" => $article->getId(), "comments" => $comments];
                }
            }
            $view->assign("articles", $articles);
            $view->assign("commentsByArticles", $commentsByArticles);
        }
    }
}
