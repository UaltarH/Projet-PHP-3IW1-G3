<?php

namespace App\Controllers;

use App\Core\Config;
use App\Core\Validator;
use App\Core\Errors;
use App\Core\View;


class Installer extends Validator
{
    public function installer()
    {
        if(Config::getConfig()['installation']['done']) {
            Errors::define(400, "Bad Request");
            exit();
        }
        $view = new View('Installer/installer', "front");
        //écrire dans application-[ENV].yml => installation.on-going = true
        Config::updateConfig(['installation', 'on-going'], true);
    }
    public function setAdmin()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            Errors::define(400, "La méthode utilisée n'est pas POST");
            exit();
        }
        // Récupérer les données envoyées en tant que JSON
        $data = json_decode(file_get_contents('php://input'), true);
        $hasErrors = false;


        // Accéder aux valeurs des champs du formulaire
        $pseudo = $data['pseudo'];
        $first_name = $data['first_name'];
        $last_name = $data['last_name'];
        $email = $data['email'];
        $phone_number = $data['phone_number'];
        $password = $data['password'];
        $passwordConfirm = $data['passwordConfirm'];

        // Instancier le validateur
        $validator = new Validator();

        // Effectuer les traitements nécessaires avec les valeurs des champs

        header('Content-Type: application/json; charset=utf-8');
        //$pseudo
        if ($validator->isEmpty($pseudo)) {
            $hasErrors = true;
            $response = array('success' => false, 'message' => 'Le pseudo est vide');
            echo json_encode($response);
            exit();
        }
        if (!$validator->isMinLength($pseudo, 2)) {
            $hasErrors = true;
            $response = array('success' => false, 'message' => 'Le pseudo doit faire au moins 2 caractères', 'data' => $pseudo);
            echo json_encode($response);
            exit();
        }
        if (!$validator->isMaxLength($pseudo, 20)) {
            $hasErrors = true;
            $response = array('success' => false, 'message' => 'Le pseudo doit faire au maximum 20 caractères');
            echo json_encode($response);
            exit();
        }

        //$first_name
        if ($validator->isEmpty($first_name)) {
            $response = array('success' => false, 'message' => 'Le prénom est vide');
            echo json_encode($response);
            exit();
        }
        if (!$validator->isMinLength($first_name, 2)) {
            $hasErrors = true;
            $response = array('success' => false, 'message' => 'Le prénom doit faire au moins 2 caractères');
            echo json_encode($response);
            exit();
        }
        if (!$validator->isMaxLength($first_name, 20)) {
            $hasErrors = true;
            $response = array('success' => false, 'message' => 'Le prénom doit faire au maximum 20 caractères');
            echo json_encode($response);
            exit();
        }

        //$last_name
        if ($validator->isEmpty($last_name)) {
            $hasErrors = true;
            $response = array('success' => false, 'message' => 'Le nom est vide');
            echo json_encode($response);
            exit();
        }
        if (!$validator->isMinLength($last_name, 2)) {
            $hasErrors = true;
            $response = array('success' => false, 'message' => 'Le nom doit faire au moins 2 caractères');
            echo json_encode($response);
            exit();
        }
        if (!$validator->isMaxLength($last_name, 20)) {
            $hasErrors = true;
            $response = array('success' => false, 'message' => 'Le nom doit faire au maximum 20 caractères');
            echo json_encode($response);
            exit();
        }

        //$email

        if ($validator->isEmpty($email)) {
            $hasErrors = true;
            $response = array('success' => false, 'message' => 'L\'email est vide');
            echo json_encode($response);
            exit();
        }

        // phone_number

        if ($validator->isEmpty($phone_number)) {
            $hasErrors = true;
            $response = array('success' => false, 'message' => 'Le numéro de téléphone est vide');
            echo json_encode($response);
            exit();
        }
        if (!$validator->isMinLength($phone_number, 10)) {
            $hasErrors = true;
            $response = array('success' => false, 'message' => 'Le numéro de téléphone doit faire au moins 10 caractères');
            echo json_encode($response);
            exit();
        }

        if (!$validator->isPhoneNumberValid($phone_number)) {
            $hasErrors = true;
            $response = array('success' => false, 'message' => 'Le numéro de téléphone est invalide');
            echo json_encode($response);
            exit();
        };

        // password

        if ($validator->isEmpty($password)) {
            $hasErrors = true;
            $response = array('success' => false, 'message' => 'Le mot de passe est vide');
            echo json_encode($response);
            exit();
        }
        if (!$validator->isMinLength($password, 8)) {
            $hasErrors = true;
            $response = array('success' => false, 'message' => 'Le mot de passe doit faire au moins 8 caractères');
            echo json_encode($response);
            exit();
        }

        if (!$validator->isPasswordValid($password, $passwordConfirm)) {
            $hasErrors = true;
            $response = array('success' => false, 'message' => 'Les mots de passe ne correspondent pas');
            echo json_encode($response);
            exit();
        };

        if (!$hasErrors) {
            // Renvoyer une réponse JSON de succès
            $response = array('success' => true, 'message' => 'Le formulaire a été traité avec succès',);
            // TODO : remplir fichier config
            echo json_encode($response);
        } else {
            // Renvoyer une réponse JSON avec un message d'erreur
            $response = array('success' => false, 'message' => 'Internal Server Error');
            echo json_encode($response);
        }
    }

    public function setDatabase()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            Errors::define(400, "La méthode utilisée n'est pas POST");
            exit();
        }
        // Récupérer les données envoyées en tant que JSON
        $data = json_decode(file_get_contents('php://input'), true);
        $hasErrors = false;

        $bddPrefix = $data['bddPrefix'];
        $siteName = $data['siteName'];
        $siteDescription = $data['siteDescription'];
        $adminEmail = $data['adminEmail'];


    }

    /**
     * Init the website
     * @return void
     */
    public function init()
    {
    //TODO : écrire à la toute fin de la page 2 dans le fichier application-[ENV].yml => installation.on-going = false && installation.done = true;
    }
}
