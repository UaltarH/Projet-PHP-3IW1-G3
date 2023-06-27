<?php

namespace App\Controllers;

use App\Core\View;
use App\Forms\CreateUser;
use App\Models\User;
use mysql_xdevapi\Exception;

class System
{
    public function userlist(): void
    {
        //TODO: access right
        $form = new CreateUser();
        $view = new View("System/userlist", "back");
        $view->assign("form", $form->getConfig());

        $user = new User();
        if(!empty($_GET["action"])) {

            $action = strtolower(trim($_GET["action"]));
            try{
                if($action == "delete") {
                    echo 'delete';
                    $user->delete(intval(trim($_GET["id"])));
                }
                else if($action == "edit") {
                    echo 'edit';
                }
                else if($action == "add") {
                    if ($form->isSubmited() && $form->isValid()) {
                        if (isset($_POST["pseudo"]) && isset($_POST["first_name"]) && isset($_POST["last_name"]) && isset($_POST["email"]) && isset($_POST["phone_number"]) && isset($_POST["role"])) {
                            echo 'phone number : '.$_POST["phone_number"];
                            if($form->isPhoneNumberValid($_POST['phone_number']) && $form->isUserInfoValid($user, $_POST['email'], $_POST['pseudo'], $_POST['phone_number'])) {
                                $user->setPseudo($_POST['pseudo']);
                                $user->setFirstname($_POST['first_name']);
                                $user->setLastname($_POST['last_name']);
                                $user->setEmail($_POST['email']);
                                $user->setPassword(trim($_POST['pseudo']));
                                $user->setPhoneNumber($_POST['phone_number']);
                                $user->setEmailConfirmation(false);
                                $user->setDateInscription(date("Y-m-d H:i:s"));

                                if($user->save()) {
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
                    $user->faker($user->userFaker());
                }
            } catch (Exception) {
                die('Error 404');
            }
        }
        $view->assign("formErrors", $form->errors);
    }

    public function useredit(): void
    {
        $form = new CreateUser();
        $view = new View("System/userlist", "back");
        $view->assign("form", $form->getConfig());
    }
}