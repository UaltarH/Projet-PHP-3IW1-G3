<?php

namespace App\Controllers;

use App\Core\View;

use App\Models\Article;
use App\Models\Comment;
use App\Models\Game;
use App\Models\User;

use App\Repository\ArticleRepository;
use App\Repository\CommentRepository;
use App\Repository\GameCategoryRepository;
use App\Repository\GameRepository;
use App\Repository\UserRepository;


class Dashboard {
    private UserRepository $userRepository;
    private ArticleRepository $articleRepository;
    private GameRepository $gameRepository;
    private GameCategoryRepository $gameCategoryRepository;
    private CommentRepository $commentRepository;
    public function __construct()
    {
        $this->userRepository = new UserRepository();
        $this->commentRepository = new CommentRepository();
        $this->articleRepository = new ArticleRepository();
        $this-> gameRepository = new GameRepository();
        $this->gameCategoryRepository = new GameCategoryRepository();
    }

    /**
     * @throws \Exception
     */
    public function index(): void
    {
        $totalUsers = $this->userRepository->getTotalCount(new User);
        $newUsersPerDay = $this->userRepository->getNewUsersPerDay();
        $totalArticles = $this->articleRepository->getTotalCount(new Article());
        $totalJeux = $this->gameRepository->getTotalCount(new Game());

        $whereSql = ["moderated" => false];
        $unmoderatedComment = $this->commentRepository->getAllWhere($whereSql, new Comment());

        $totalGamesByCategories = $this->gameCategoryRepository->getTotalGamesByCategories();

        $view = new View("System/dashboard", "back");
        $view->assign('totalUsers', $totalUsers);
        $view->assign('newUsersPerDay', $newUsersPerDay);
        $view->assign('totalArticles', $totalArticles);
        $view->assign('totalJeux', $totalJeux);
        $view->assign('totalGamesByCategories', $totalGamesByCategories);
        $view->assign('unmoderatedComment', $unmoderatedComment);
    }
}