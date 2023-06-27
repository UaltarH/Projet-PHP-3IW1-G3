<?php

namespace App\Controllers;

use App\Core\View;
use App\Models\User;

class Api
{
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
            "columns" => ["pseudo", "first_name", "last_name", "email", "date_inscription", "role_id"],
            "start" => $start,
            "length" => $length,
            "search" => $search,
            "columnToSort" => $columnName,
            "sortOrder" => $columnSortOrder,
        ]));
    }
    public function userdelete(): void
    {
        //TODO: access right
        if(empty($_GET["id"])) {
            die("Error 404");
        }
        $user = new User();
        $delete = $user->delete(trim($_GET["id"]));
        echo "Delete ".($delete ? "success":"fail");
    }
}