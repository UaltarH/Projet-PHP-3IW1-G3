<?php

namespace App\Controllers;

use App\Core\View;
use App\Models\AbstractModel;
use App\Models\Article_Category;
use App\Models\Game;
use App\Models\Game_Category;
use App\Repository\ArticleCategoryRepository;
use App\Repository\ArticleRepository;
use App\Repository\GameCategoryRepository;
use App\Repository\GameRepository;
use function App\Core\TokenJwt\getSpecificDataFromToken;
use function App\Core\TokenJwt\validateJWT;
require_once '/var/www/html/Core/TokenJwt.php';

class Main extends AbstractModel
{
    private ArticleRepository $articleRepository;
    private ArticleCategoryRepository $articleCategoryRepository;
    private GameCategoryRepository $gameCategoryRepository;
    private GameRepository $gameRepository;

    public function __construct()
    {
        $this->articleRepository = new ArticleRepository();
        $this->articleCategoryRepository = new ArticleCategoryRepository();
        $this->gameCategoryRepository = new GameCategoryRepository();
        $this->gameRepository = new GameRepository();
    }

    public function home(): void     
    {
        $view = new View("Main/home", "front");
        if(isset($_SESSION['token']) && validateJWT($_SESSION['token'])){
            $view->assign("pseudo", getSpecificDataFromToken($_SESSION['token'], "pseudo"));
            $view->assign("roleId", getSpecificDataFromToken($_SESSION['token'], "roleId"));
        }

        $articleModel = $this->articleRepository;
        $newArticles = array_slice($articleModel->selectAll(new \App\Models\Article()), -5, 5);
        $view->assign("newArticles", $newArticles);
    }

    public function contact(): void
    {
        echo "Page de contact";
    }

    public function aboutUs(): void
    {
        echo "Page à propos";
    }

    public function search(): void
    {
        $query = $_GET["search"];
        $articleModel = $this->articleRepository;
        $jeuxModel = $this->gameRepository;
        $void = $articleModel->selectAll(new \App\Models\Article());

        $attributes = ["title", "content"];
        $articleWhere = [];
        foreach ($attributes as $value){
            $whereSql = [$value => $query];
            $results = $articleModel->getAllWhereInsensitiveLike($whereSql, new \App\Models\Article());

            foreach ($results as $result) {
                if (!in_array($result, $articleWhere)) {
                    $articleWhere[] = $result;
                }
            }
        }

        $attributes = ["title_game"];
        $jeuxWhere = [];
        foreach ($attributes as $value){
            $whereSql = [$value => $query];
            $results = $jeuxModel->getAllWhereInsensitiveLike($whereSql, new Game());

            foreach ($results as $result) {
                if (!in_array($result, $jeuxWhere)) {
                    $jeuxWhere[] = $result;
                }
            }
        }

        $view = new View("Main/search", "front");
        $view->assign("articleWhere", $articleWhere);
        $view->assign("jeuxWhere", $jeuxWhere);
    }

}