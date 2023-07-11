<?php

namespace App\Controllers;

use App\Core\View;

use App\Models\Article AS ArticleModel;
use App\Models\Category_jeux;
use App\Models\Jeux;

class JeuxController
{
    public function allgames(){
        $view = new View("Jeux/allGames", "front");
        $jeuxModel = new Jeux();
        $categorieJeuxModel = new Category_jeux();

        $jeux = $jeuxModel->selectAll();
        $categories = $categorieJeuxModel->selectAll();

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