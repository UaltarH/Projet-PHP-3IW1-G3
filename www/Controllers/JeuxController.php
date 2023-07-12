<?php

namespace App\Controllers;

use App\Core\View;

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
        foreach ($jeux as $index => $value){
            foreach ($categories as $categorie) {
                if ($value->getCategory_id() == $categorie->getId()){
                    $result[] = ["title"=>$value->getTitle(), "categorie"=>$categorie->getCategoryName()];
                }
            }
        }

        $view->assign("jeux", $result);
    }
}