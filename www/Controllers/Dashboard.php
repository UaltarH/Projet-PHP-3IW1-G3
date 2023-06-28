<?php

namespace App\Controllers;

use App\Core\View;
use App\Models\Article;
use App\Models\Category_jeux;
use App\Models\Jeux;
use App\Models\User;

class Dashboard {
    public function index() {
        $userModel = new User();
        $articleModel = new Article();
        $jeuxModel = new Jeux();
        $categorieJeuxModel = new Category_jeux();

        $totalUsers = $userModel->getTotalCount();
        $newUsersPerDay = $userModel->getNewUsersPerDay();

        $totalArticles = $articleModel->getTotalCount();

        $totalJeux = $jeuxModel->getTotalCount();

        $totalGamesByCategories = $categorieJeuxModel->getTotalGamesByCategories();

        $view = new View("System/dashboard", "back");
        $view->assign('totalUsers', $totalUsers);
        $view->assign('newUsersPerDay', $newUsersPerDay);
        $view->assign('totalArticles', $totalArticles);
        $view->assign('totalJeux', $totalJeux);
        $view->assign('totalGamesByCategories', $totalGamesByCategories);
    }
}