<?php

namespace App\Controllers;

use App\Core\View;
use App\Forms\CreateUser;
use App\Forms\EditUser;
use App\Models\User;
use App\Repository\UserRepository;
use mysql_xdevapi\Exception;

class System
{
    public function userlist(): void
    {
        //TODO: access right
        $user = new User();
        $roles = UserRepository::fetchRoles();
        $rolesOption = [];
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
                if($action == "delete") {
                    echo 'delete';
                    $user->delete(trim($_GET["id"]));
                }
                else if($action == "edit") {
                    $emptyFields = true;
                    if($editUserModalForm->isSubmited() && $editUserModalForm->isValid()) {
                        $fieldsToCheck = [];
                        if(!empty($_POST["phone_number"])) {
                            if(!$editUserModalForm->isPhoneNumberValid($_POST['phone_number']))
                                die('Invalid phone number');
                            else {
                                $fieldsToCheck["phone_number"] = $_POST["phone_number"];
                                $user->setPhoneNumber($_POST["phone_number"]);
                                $emptyFields = false;
                            }
                        }
                        if(!empty($_POST["password"])) {
                            if(!$editUserModalForm->isPasswordValid($_POST['password'], $_POST['passwordConfirm']))
                                die('Invalid password');
                            else {
                                $user->setPassword($_POST["password"]);
                                $emptyFields = false;
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
                        if($editUserModalForm->isFieldsInfoValid($user, $fieldsToCheck) && !$emptyFields) {
                            $user->setId($_GET["id"]);
                            if($user->save()->success) {
                                echo 'save successful';
                            }
                        }
                    }
                }
                else if($action == "add") {
                    if ($createUserForm->isSubmited() && $createUserForm->isValid()) {
                        if (isset($_POST["pseudo"]) && isset($_POST["first_name"]) && isset($_POST["last_name"]) && isset($_POST["email"]) && isset($_POST["phone_number"]) && isset($_POST["role"])) {
                            if($createUserForm->isPhoneNumberValid($_POST['phone_number']) && $createUserForm->isFieldsInfoValid($user, ["email"=>$_POST['email'], "pseudo"=>$_POST['pseudo'], "phone_number"=>$_POST['phone_number']])) {
                                $user->setPseudo($_POST['pseudo']);
                                $user->setFirstname($_POST['first_name']);
                                $user->setLastname($_POST['last_name']);
                                $user->setEmail($_POST['email']);
                                $user->setPassword(trim($_POST['pseudo']));
                                $user->setRoleId($_POST["role"]);
                                $user->setPhoneNumber($_POST['phone_number']);
                                $user->setEmailConfirmation(false);
                                $user->setDateInscription(date("Y-m-d H:i:s"));

                                if($user->save()->success) {
                                    echo 'Nouvel utilisateur créé avec succès';
                                }
                                else {
                                    echo 'L\'ajout d\'un nouvel utilisateur a échoué';
                                }
                            }else {
                                echo 'Invalid info';
                            }
                        }
                        else {
                            die('Missing fields');
                        }
                    }
                }
                else if($action == "faker") {
                    UserRepository::userFaker();
                }
            } catch (Exception) {
                die('Error 404');
            }
        }
        $view->assign("createUserFormErrors", $createUserForm->errors);
        $view->assign("editUserFormErrors", $editUserModalForm->errors);
    }

    public function useredit(): void
    {
        $form = new CreateUser();
        $view = new View("System/userlist", "back");
        $view->assign("form", $form->getConfig());
    }
}