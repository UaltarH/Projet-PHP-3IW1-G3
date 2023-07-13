<?php

namespace App\Controllers;

use App\Core\View;
use App\Models\Category_article;
use App\Models\Category_jeux;
use App\Models\Jeux;
use App\Models\Article;

use function App\Core\TokenJwt\getSpecificDataFromToken;
use function App\Core\TokenJwt\validateJWT;
require_once '/var/www/html/Core/TokenJwt.php';

class Main
{
    public function home(): void     
    {
        //utilisateur connecter:        
        $view = new View("Main/home", "front");
        if(isset($_SESSION['token']) && validateJWT($_SESSION['token'])){
            $view->assign("pseudo", getSpecificDataFromToken($_SESSION['token'], "pseudo"));
            $view->assign("roleId", getSpecificDataFromToken($_SESSION['token'], "roleId"));
        }
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
        $articleModel = new Article();
        $jeuxModel = new Jeux();
        $categorieJeuxModel = new Category_jeux();
        $categorieArticleModel = new Category_article();

        $attributes = ["title", "content"];
        $articleWhere = [];
        foreach ($attributes as $value){
            $whereSql = [$value => $query];
            $results = $articleModel->getAllWhereInsensitiveLike($whereSql);

            foreach ($results as $result) {
                if (!in_array($result, $articleWhere)) {
                    $articleWhere[] = $result;
                }
            }
        }

        $attributes = ["title"];
        $jeuxWhere = [];
        foreach ($attributes as $value){
            $whereSql = [$value => $query];
            $results = $jeuxModel->getAllWhereInsensitiveLike($whereSql);

            foreach ($results as $result) {
                if (!in_array($result, $jeuxWhere)) {
                    $jeuxWhere[] = $result;
                }
            }
        }

        $attributes = ["category_name", "description"];
        $categorieJeuxWhere = [];
        foreach ($attributes as $value){
            $whereSql = [$value => $query];
            $results = $categorieJeuxModel->getAllWhereInsensitiveLike($whereSql);

            foreach ($results as $result) {
                if (!in_array($result, $categorieJeuxWhere)) {
                    $categorieJeuxWhere[] = $result;
                }
            }
        }

        $attributes = ["category_name", "description"];
        $categorieArticlesWhere = [];
        foreach ($attributes as $value){
            $whereSql = [$value => $query];
            $results = $categorieArticleModel->getAllWhereInsensitiveLike($whereSql);

            foreach ($results as $result) {
                if (!in_array($result, $categorieArticlesWhere)) {
                    $categorieArticlesWhere[] = $result;
                }
            }
        }

        $view = new View("Main/search", "front");
        $view->assign("articleWhere", $articleWhere);
        $view->assign("jeuxWhere", $jeuxWhere);
        $view->assign("categorieArticlesWhere", $categorieArticlesWhere);
        $view->assign("categorieJeuxWhere", $categorieJeuxWhere);
    }

}