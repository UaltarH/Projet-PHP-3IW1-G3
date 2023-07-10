<?php

namespace App\Controllers;

use App\Core\View;
use App\Models\User;
use App\Models\Article;
use App\Repository\UserRepository;

class Api
{
    /**
     * @throws \Exception
     */
    public function userlist(): void
    {
        //TODO: access right
        // deny access to this url
        $length = intval(trim($_GET['length']));
        $start = intval(trim($_GET['start']));
        $search = '';
        // if there's a sorting
        $columnIndex = intval($_GET['order'][0]['column']); // Column index
        $columnName = trim($_GET['columns'][$columnIndex]['data']); // Column name
        $columnSortOrder = trim($_GET['order'][0]['dir']); // asc or desc
        if (isset($_GET['search']) && !empty($_GET['search']['value'])) {
            $search = trim($_GET['search']['value']);
        }
        $user = new User();
        echo json_encode($user->list([
            "columns" => ["pseudo", "first_name", "last_name", "email", "date_inscription", "role_name"],
            "start" => $start,
            "length" => $length,
            "search" => $search,
            "columnToSort" => $columnName,
            "sortOrder" => $columnSortOrder,
            "join" => [
                [
                    "table" => "carte_chance_role",
                    "foreignKeys" => [
                        "originColumn" => "role_id",
                        "targetColumn" => "id"
                    ]
                ]
            ]
        ], $user));
    }

    public function articlelist(): void
    {
        //TODO: access right
        // deny access to this url
        $length = intval(trim($_GET['length']));
        $start = intval(trim($_GET['start']));
        $search = '';
        // if there's a sorting
        $columnIndex = intval($_GET['order'][0]['column']); // Column index
        $columnName = trim($_GET['columns'][$columnIndex]['data']); // Column name
        $columnSortOrder = trim($_GET['order'][0]['dir']); // asc or desc
        if (isset($_GET['search']) && !empty($_GET['search']['value'])) {
            $search = trim($_GET['search']['value']);
        }
        $article = new Article();
        echo json_encode($article->list([
            "columns" => ["title", "created_date", "updated_date", "category_name"],
            "start" => $start,
            "length" => $length,
            "search" => $search,
            "columnToSort" => $columnName,
            "sortOrder" => $columnSortOrder,
            "join" => [
                [
                    "table" => "carte_chance_category_article",
                    "foreignKeys" => [
                        "originColumn" => "category_id",
                        "targetColumn" => "id"
                    ]
                ]
            ]
        ], $article));
    }
}