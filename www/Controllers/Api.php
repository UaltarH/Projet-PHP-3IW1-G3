<?php

namespace App\Controllers;

use App\Core\Errors;
use App\Core\Validator;
use App\Models\Article;
use App\Models\User;
use App\Repository\ArticleRepository;
use App\Repository\UserRepository;

class Api
{
    private UserRepository $userRepository;
    private ArticleRepository $articleRepository;
    public function __construct() {
        $this->userRepository = new UserRepository();
        $this->articleRepository = new ArticleRepository();
    }

    /**
     * @return void
     */
    public function usercreate(): void
    {
        header('Content-Type: application/json');
        if($_SERVER['REQUEST_METHOD'] != "POST") {
            Errors::define(400, 'Bad HTTP request');
            echo json_encode(['success' => false]);
            exit;
        }
        $user = new User();
        $validator = new Validator();
        if (!empty($_POST["pseudo"]) && !empty($_POST["first_name"]) && !empty($_POST["last_name"]) && !empty($_POST["email"]) && !empty($_POST["phone_number"]) && !empty($_POST["role"])) {
            if($validator->isPhoneNumberValid($_POST['phone_number']) && $validator->isFieldsInfoValid($user, ["email"=>$_POST['email'], "pseudo"=>$_POST['pseudo'], "phone_number"=>$_POST['phone_number']])) {
                $user->setPseudo($_POST['pseudo']);
                $user->setFirstname($_POST['first_name']);
                $user->setLastname($_POST['last_name']);
                $user->setEmail($_POST['email']);
                $user->setPassword(trim($_POST['pseudo']));
                $user->setRoleId($_POST["role"]);
                $user->setPhoneNumber($_POST['phone_number']);
                $user->setEmailConfirmation(false);
                $user->setDateInscription(date("Y-m-d H:i:s"));

                if($this->userRepository->save($user)->success) {
                    echo json_encode(['success' => true]);
                    exit();
                }
                else {
                    Errors::define(500, 'Internal Server Error');
                    echo json_encode(['success' => false, 'message'=> 'Internal Server Error']);
                    exit();
                }
            } else {
                Errors::define(400, 'Invalid Info');
                echo json_encode(['success' => false, 'message' => 'Invalid Info']);
                exit();
            }
        }
        else {
            Errors::define(400, 'Missing Info');
            echo json_encode(['success' => false, 'message' => 'Missing Info']);
            exit();
        }

    }

    /**
     * @throws \Exception
     */
    public function userlist(): void
    {
        if($_SERVER['REQUEST_METHOD'] != "GET") {
            Errors::define(400, 'Bad HTTP request');
            echo json_encode("Bad Method");
            exit;
        }
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
        echo json_encode($this->userRepository->list([
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
        ], new User()));
    }

    /**
     * @throws \Exception
     */
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
        echo json_encode($this->articleRepository->list([
            "columns" => ["title", "created_date", "updated_date", "category_name"],
            "start" => $start,
            "length" => $length,
            "search" => $search,
            "columnToSort" => $columnName,
            "sortOrder" => $columnSortOrder,
            "join" => [
                [
                    "table" => "carte_chance_article_category",
                    "foreignKeys" => [
                        "originColumn" => "category_id",
                        "targetColumn" => "id"
                    ]
                ]
            ]
        ], $article));
    }
    public function useredit(): void
    {
        header('Content-Type: application/json');
        if($_SERVER['REQUEST_METHOD'] != "POST") {
            Errors::define(400, 'Bad HTTP request');
            echo json_encode("Bad Method");
            exit;
        }
        $emptyFields = true;
        $user = new User();
        $validator = new Validator();
        $fieldsToCheck = [];
        if(empty($_POST["id"])) {
            Errors::define(400, 'Missing ID');
            exit;
        }
        if(!empty($_POST["phone_number"])) {
            if(!$validator->isPhoneNumberValid($_POST['phone_number'])) {
                Errors::define(400, 'Invalid phone number');
                exit;
            }
            else {
                $fieldsToCheck["phone_number"] = $_POST["phone_number"];
                $user->setPhoneNumber($_POST["phone_number"]);
                $emptyFields = false;
            }
        }
        if(!empty($_POST["password"])) {
            if(!empty($_POST['passwordConfirm'])){
                if (!$validator->isPasswordValid($_POST['password'], $_POST['passwordConfirm'])) {
                    Errors::define(400, 'Invalid password');
                    exit;
                }
                else {
                    $user->setPassword($_POST["password"]);
                    $emptyFields = false;
                }
            }
            else {
                Errors::define(400, 'Password confirm missing');
                exit;
            }
        }
        if(!empty($_POST["pseudo"])) {
            $fieldsToCheck["pseudo"] = $_POST["pseudo"];
            $user->setPseudo($_POST["pseudo"]);
            $emptyFields = false;
        }
        if(!empty($_POST["email"])) {
            $fieldsToCheck["email"] = $_POST["email"];
            $user->setEmail($_POST["email"]);
            $emptyFields = false;
        }
        if(!empty($_POST["first_name"])) {
            $user->setFirstname($_POST["first_name"]);
            $emptyFields = false;
        }
        if(!empty($_POST["last_name"])) {
            $user->setLastname($_POST["last_name"]);
            $emptyFields = false;
        }
        if(!empty($_POST["role"])) {
            $user->setRoleId($_POST["role"]);
            $emptyFields = false;
        }
        if($validator->isFieldsInfoValid($user, $fieldsToCheck) && !$emptyFields) {
            $user->setId($_POST["id"]);
            if($this->userRepository->save($user)->success) {
                echo json_encode(['success' => true]);
            }
            else {
                Errors::define(500, 'Internal Server Error');
                echo json_encode(['success' => false, 'message'=>'Internal Server Error']);
            }
            exit();
        }
        else {
            Errors::define(500, 'Invalid Info');
            echo json_encode(['success' => false, 'message'=>'Invalid Info']);
            exit();
        }
    }
    public function userdelete(): void
    {
        header('Content-Type: application/json');
        if(empty($_SERVER['REQUEST_METHOD']) || $_SERVER['REQUEST_METHOD'] != "DELETE") {
            Errors::define(400, 'Bad HTTP request');
            echo json_encode("Bad Method");
            exit;
        }
        $delete = self::getHttpMethodVarContent();
        if(empty($delete['id'])) {
            Errors::define(400, 'Bad Request');
            echo json_encode("Bad Request");
            exit;
        }
        $user = new User();
        $user->setId($delete['id']);
        if($this->userRepository->delete($user)) {
            echo json_encode(['success' => true]);
        }
        else {
            Errors::define(500, 'Internal Server Error');
            echo json_encode("Internal Server Error");
        }
        exit();
    }

    /**
     * Parse les arguments passés par les méthodes PUT et DELETE uniquement, puis les passes dans un tableau
     * eg : $post_vars['id']
     * @return array
     */
    public static function getHttpMethodVarContent(): array
    {
        $post_vars = [];
        if ($_SERVER["CONTENT_TYPE"] === 'application/x-www-form-urlencoded; charset=UTF-8') {
            parse_str(file_get_contents("php://input"), $post_vars);
        }
        return $post_vars;
    }
}