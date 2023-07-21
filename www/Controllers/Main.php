<?php

namespace App\Controllers;

use App\Core\View;
use App\Core\Errors;

use App\Models\AbstractModel;
use App\Models\Game;

use App\Repository\ArticleRepository;
use App\Repository\GameRepository;

use function App\Core\TokenJwt\getSpecificDataFromToken;
use function App\Core\TokenJwt\validateJWT;
require_once '/var/www/html/Core/TokenJwt.php';

class Main extends AbstractModel
{
    private ArticleRepository $articleRepository;
    private GameRepository $gameRepository;

    public function __construct()
    {
        $this->articleRepository = new ArticleRepository();
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
        $view = new View("Main/contact", "front");
    }

    public function aboutUs(): void
    {
        $view = new View("Main/about-us", "front");
    }

    public function search(): void
    {
        if(empty($_GET) || $_SERVER['REQUEST_METHOD'] != "GET") {
            Errors::define(400, 'Bad HTTP request');
            echo json_encode("Bad Method");
            exit;
        }

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