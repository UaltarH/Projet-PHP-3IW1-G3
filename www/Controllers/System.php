<?php

namespace App\Controllers;

use App\Core\View;
use App\Core\Errors;

use App\Forms\CreateUser;
use App\Forms\EditUser;

use App\Repository\UserRepository;


use mysql_xdevapi\Exception;

class System
{
    private UserRepository $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }

    public function userlist(): void
    {
        $roles = $this->userRepository->fetchRoles();
        $rolesOption = [];
        $rolesOption[""] = "Choose a role";
        foreach ($roles as $role) {
            $rolesOption[$role["id"]] = $role["role_name"];
        }
        $createUserForm = new CreateUser();
        $editUserModalForm = new EditUser();
        $createUserForm->setConfig($rolesOption);
        $editUserModalForm->setConfig($rolesOption);
        $view = new View("/System/userlist", "back");
        $view->assign("createUserForm", $createUserForm->getConfig());
        $view->assign("editUserForm", $editUserModalForm->getConfig());
        $view->assign("createUserFormErrors", $createUserForm->errors);
        $view->assign("editUserFormErrors", $editUserModalForm->errors);
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
    } 

}