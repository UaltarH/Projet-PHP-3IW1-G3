<?php

namespace App\Controllers;

use App\Core\View;
use App\Forms\ModerateComment;
use App\Models\Article;
use App\Models\Category_jeux;
use App\Models\Comment;
use App\Models\Jeux;
use App\Models\User;



class CommentController {
    public function index() {
        $id = $_GET["id"];
        $commentaireModel = new Comment();

        $whereSql = ["moderated" => false];
        $unmoderatedComment = $commentaireModel->getAllWhere($whereSql);
        $whereSql = ["moderated" => true];
        $moderatedComment = $commentaireModel->getAllWhere($whereSql);
        $allComment = $commentaireModel->selectAll();

        $view = new View("System/commentlist", "back");
        $view->assign('unmoderatedComment', $unmoderatedComment);
        $view->assign('moderatedComment', $moderatedComment);
        $view->assign('allComment', $allComment);
    }

    public function edit() {
        $id = $_GET["id"];
        $commentaireModel = new Comment();
        $moderateCommentForm = new ModerateComment();

        $whereSql = ["id" => $id];
        $commentInfos = $commentaireModel->getOneWhere($whereSql);


        $view = new View("System/commentedit", "back");
        $view->assign('commentInfos', $commentInfos);
        $view->assign("moderateCommentForm", $moderateCommentForm->getConfig($commentInfos));
    }
}