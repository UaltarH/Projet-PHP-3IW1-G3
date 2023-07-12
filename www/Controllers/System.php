<?php

namespace App\Controllers;

use App\Core\View;

use App\Forms\CreateUser;
use App\Forms\EditUser;
use App\Models\User;
use App\Repository\UserRepository;


use mysql_xdevapi\Exception;

use function App\Services\AddFileContent\AddFileContentFunction;
require_once '/var/www/html/Services/AddFileContent.php';

class System
{
    public function userlist(): void
    {
        //TODO: access right
        $user = new User();
        $roles = UserRepository::fetchRoles();
        $rolesOption = [];
        $rolesOption[""] = "Choose a role";
        foreach ($roles as $role) {
            $rolesOption[$role["id"]] = $role["role_name"];
        }
        $createUserForm = new CreateUser();
        $editUserModalForm = new EditUser();
        $view = new View("System/userlist", "back");
        $view->assign("createUserForm", $createUserForm->getConfig($rolesOption));
        $view->assign("editUserForm", $editUserModalForm->getConfig($rolesOption));

        if(!empty($_GET["action"])) {
            $action = strtolower(trim($_GET["action"]));
            try{
                if($action == "faker") {
                    UserRepository::userFaker();
                }
            } catch (Exception) {
                die('Error 404');
            }
        }
        $view->assign("createUserFormErrors", $createUserForm->errors);
        $view->assign("editUserFormErrors", $editUserModalForm->errors);
    } // end of userList()

}