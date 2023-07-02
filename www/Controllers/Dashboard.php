<?php

namespace App\Controllers;

use App\Core\View;
use App\Models\Article;
use App\Models\Category_jeux;
use App\Models\Comment;
use App\Models\Jeux;
use App\Models\User;
use function App\Core\TokenJwt\getAllInformationsFromToken;


class Dashboard {
    public function index() {
        $userModel = new User();
        $articleModel = new Article();
        $jeuxModel = new Jeux();
        $categorieJeuxModel = new Category_jeux();
        $commentaireModel = new Comment();

        $totalUsers = $userModel->getTotalCount();
        $newUsersPerDay = $userModel->getNewUsersPerDay();

        $userProfil = getAllInformationsFromToken($_SESSION["token"]);

        $totalArticles = $articleModel->getTotalCount();

        $totalJeux = $jeuxModel->getTotalCount();

        $whereSql = ["moderated" => false];
        $unmoderatedComment = $commentaireModel->getAllWhere($whereSql);

        $totalGamesByCategories = $categorieJeuxModel->getTotalGamesByCategories();

        $view = new View("System/dashboard", "back");
        $view->assign('totalUsers', $totalUsers);
        $view->assign('newUsersPerDay', $newUsersPerDay);
        $view->assign('totalArticles', $totalArticles);
        $view->assign('totalJeux', $totalJeux);
        $view->assign('totalGamesByCategories', $totalGamesByCategories);
        $view->assign('unmoderatedComment', $unmoderatedComment);
        $view->assign('userProfil', $userProfil);
    }
}