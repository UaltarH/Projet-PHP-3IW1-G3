<?php

namespace App\Controllers;

use App\Core\View;

use App\Forms\ModerateComment;

use App\Models\Comment;

use App\Repository\AbstractRepository;
use App\Repository\CommentRepository;


class CommentController extends AbstractRepository
{
    private CommentRepository $commentRepository;

    public function __construct()
    {
        $this->commentRepository = new CommentRepository();
    }
    public function index() {
        $commentaireModel = $this->commentRepository;

        $whereSql = ["moderated" => false];
        $unmoderatedComment = $commentaireModel->getAllWhere($whereSql, new Comment());
        $whereSql = ["moderated" => true];
        $moderatedComment = $commentaireModel->getAllWhere($whereSql, new Comment());
        $allComment = $commentaireModel->selectAll(new Comment());

        $view = new View("System/commentlist", "back");
        $view->assign('unmoderatedComment', $unmoderatedComment);
        $view->assign('moderatedComment', $moderatedComment);
        $view->assign('allComment', $allComment);
    }

    public function edit() {
        if(empty($_GET) || $_SERVER['REQUEST_METHOD'] != "GET") {
            Errors::define(400, 'Bad HTTP request');
            echo json_encode("Bad Method");
            exit;
        }

        $id = $_GET["id"];
        $commentaireModel = $this->commentRepository;
        $moderateCommentForm = new ModerateComment();

        $whereSql = ["id" => $id];
        $commentInfos = $commentaireModel->getOneWhere($whereSql, new Comment());


        $view = new View("System/commentedit", "back");
        $view->assign('commentInfos', $commentInfos);
        $view->assign("moderateCommentForm", $moderateCommentForm->getConfig($commentInfos));
    }

    public function moderate() {
        if(empty($_POST) || $_SERVER['REQUEST_METHOD'] != "POST") {
            Errors::define(400, 'Bad HTTP request');
            echo json_encode(['success' => false]);
            exit;
        }

        $id = $_POST["id"];
        $accepted = $_POST['accepted'] ?? false;
        $commentaireModel = $this->commentRepository;

        $whereSql = ["id" => $id];
        $comment = $commentaireModel->getOneWhere($whereSql, new Comment());
        $comment->setModerated(true);
        $comment->setAccepted($accepted);
        $this->commentRepository->save($comment);

        header("Location: /sys/comment/list");
    }
}