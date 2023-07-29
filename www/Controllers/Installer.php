<?php

namespace App\Controllers;

use App\Core\Config;
use App\Core\Validator;
use App\Core\Errors;
use App\Core\View;

use PDO;
use PDOException;


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
        Config::getInstance()->updateConfig(['installation', 'on-going'], true);
    }
    public function setAdmin()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            Errors::define(400, "La méthode utilisée n'est pas POST");
            exit();
        }
        // Récupérer les données envoyées en tant que JSON
        $data = json_decode(file_get_contents('php://input'), true);


        // Accéder aux valeurs des champs du formulaire
        $pseudo = htmlspecialchars(trim($data['pseudo']));
        $first_name = htmlspecialchars(trim($data['first_name']));
        $last_name = htmlspecialchars(trim($data['last_name']));
        $email = htmlspecialchars(trim($data['email']));
        $phone_number = $data['phone_number'];
        $password = $data['password'];
        $passwordConfirm = $data['passwordConfirm'];

        // Instancier le validateur
        $validator = new Validator();

        // Effectuer les traitements nécessaires avec les valeurs des champs

        header('Content-Type: application/json; charset=utf-8');
        //$pseudo
        if ($validator->isEmpty($pseudo)) {
            $response = array('success' => false, 'message' => 'Le pseudo est vide');
            echo json_encode($response);
            exit();
        }
        if (!$validator->isMinLength($pseudo, 2)) {
            $response = array('success' => false, 'message' => 'Le pseudo doit faire au moins 2 caractères', 'data' => $pseudo);
            echo json_encode($response);
            exit();
        }
        if (!$validator->isMaxLength($pseudo, 15)) {
            $response = array('success' => false, 'message' => 'Le pseudo doit faire au maximum 15 caractères');
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
            $response = array('success' => false, 'message' => 'Le prénom doit faire au moins 2 caractères');
            echo json_encode($response);
            exit();
        }
        if (!$validator->isMaxLength($first_name, 20)) {
            $response = array('success' => false, 'message' => 'Le prénom doit faire au maximum 60 caractères');
            echo json_encode($response);
            exit();
        }

        //$last_name
        if ($validator->isEmpty($last_name)) {
            $response = array('success' => false, 'message' => 'Le nom est vide');
            echo json_encode($response);
            exit();
        }
        if (!$validator->isMinLength($last_name, 2)) {
            $response = array('success' => false, 'message' => 'Le nom doit faire au moins 2 caractères');
            echo json_encode($response);
            exit();
        }
        if (!$validator->isMaxLength($last_name, 60)) {
            $response = array('success' => false, 'message' => 'Le nom doit faire au maximum 20 caractères');
            echo json_encode($response);
            exit();
        }

        //$email

        if ($validator->isEmpty($email)) {
            $response = array('success' => false, 'message' => 'L\'email est vide');
            echo json_encode($response);
            exit();
        }
        if (!$validator->isMinLength($email, 6)) {
            $response = array('success' => false, 'message' => 'L\'email doit faire au moins 6 caractères');
            echo json_encode($response);
            exit();
        }
        if (!$validator->isMaxLength($email, 64)) {
            $response = array('success' => false, 'message' => 'L\'email doit faire au maximum 64 caractères');
            echo json_encode($response);
            exit();
        }
        if (!$validator->isEmailValid($email)) {
            $response = array('success' => false, 'message' => 'Email invalide');
            echo json_encode($response);
            exit();
        }

        // phone_number

        if ($validator->isEmpty($phone_number)) {
            $response = array('success' => false, 'message' => 'Le numéro de téléphone est vide');
            echo json_encode($response);
            exit();
        }
        if (!$validator->isMinLength($phone_number, 10)) {
            $response = array('success' => false, 'message' => 'Le numéro de téléphone doit faire au moins 10 caractères');
            echo json_encode($response);
            exit();
        }
        if (!$validator->isMaxLength($phone_number, 10)) {
            $response = array('success' => false, 'message' => 'Le numéro de téléphone doit faire au maximum 10 caractères');
            echo json_encode($response);
            exit();
        }

        if (!$validator->isPhoneNumberValid($phone_number)) {
            $response = array('success' => false, 'message' => 'Le numéro de téléphone est invalide');
            echo json_encode($response);
            exit();
        };

        // password

        if ($validator->isEmpty($password)) {
            $response = array('success' => false, 'message' => 'Le mot de passe est vide');
            echo json_encode($response);
            exit();
        }
        if (!$validator->isMinLength($password, 8)) {
            $response = array('success' => false, 'message' => 'Le mot de passe doit faire au moins 8 caractères');
            echo json_encode($response);
            exit();
        }
        if (!$validator->isMaxLength($password, 32)) {
            $response = array('success' => false, 'message' => 'Le mot de passe doit faire au maximum 32 caractères');
            echo json_encode($response);
            exit();
        }

        if (!$validator->isPasswordValid($password, $passwordConfirm)) {
            $response = array('success' => false, 'message' => 'Les mots de passe ne correspondent pas');
            echo json_encode($response);
            exit();
        };

        // Renvoyer une réponse JSON de succès
        
        $response = array('success' => true, 'message' => 'Le formulaire a été traité avec succès',);

        $conf = Config::getInstance();
        $conf->updateConfig(['bdd', 'user', 'pseudo'], $pseudo);
        $conf->updateConfig(['bdd', 'user', 'firstname'], $first_name);
        $conf->updateConfig(['bdd', 'user', 'lastname'], $last_name);
        $conf->updateConfig(['bdd', 'user', 'email'], $email);
        $conf->updateConfig(['bdd', 'user', 'phone'], $phone_number);
        $conf->updateConfig(['bdd', 'user', 'password'], password_hash($password, PASSWORD_DEFAULT));

        echo json_encode($response);

    }

    public function setDatabase()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            Errors::define(400, "La méthode utilisée n'est pas POST");
            exit();
        }
        // Récupérer les données envoyées en tant que JSON
        $data = json_decode(file_get_contents('php://input'), true);

        $bddPrefix = strtolower(htmlspecialchars(trim($data['bddPrefix'])));
        $siteName = htmlspecialchars(trim($data['siteName']));
        $siteDescription = htmlspecialchars(trim($data['siteDescription']));
        $adminEmail = htmlspecialchars(trim($data['adminEmail']));

        $validator = new Validator();

        // nom du site
        if ($validator->isEmpty($siteName)) {
            $response = array('success' => false, 'message' => 'Nom du site vide');
            echo json_encode($response);
            exit();
        }
        if (!$validator->isMinLength($siteName, 2)) {
            $response = array('success' => false, 'message' => 'Le nom du site doit faire au moins 2 caractères');
            echo json_encode($response);
            exit();
        }
        if (!$validator->isMaxLength($siteName, 20)) {
            $response = array('success' => false, 'message' => 'Le nom du site doit faire au maximum 20 caractères');
            echo json_encode($response);
            exit();
        }
        // description du site
        if ($validator->isEmpty($siteDescription)) {
            $response = array('success' => false, 'message' => 'Description du site vide');
            echo json_encode($response);
            exit();
        }
        if (!$validator->isMinLength($siteDescription, 2)) {
            $response = array('success' => false, 'message' => 'La description du site doit faire au moins 2 caractères');
            echo json_encode($response);
            exit();
        }
        if (!$validator->isMaxLength($siteDescription, 80)) {
            $response = array('success' => false, 'message' => 'La description du site doit faire au maximum 20 caractères');
            echo json_encode($response);
            exit();
        }
        // prefix
        if ($validator->isEmpty($bddPrefix) || !$validator->isPrefixValid($bddPrefix)) {
            $response = array('success' => false, 'message' => 'Prefix invalide');
            echo json_encode($response);
            exit();
        }
        if (!$validator->isMinLength($bddPrefix, 2)) {
            $response = array('success' => false, 'message' => 'Le prefix doit faire au moins 2 caractères');
            echo json_encode($response);
            exit();
        }
        if (!$validator->isMaxLength($bddPrefix, 64)) {
            $response = array('success' => false, 'message' => 'Le prefix doit faire au maximum 64 caractères');
            echo json_encode($response);
            exit();
        }
        // email admin
        if ($validator->isEmpty($adminEmail)) {
            $response = array('success' => false, 'message' => 'Email vide');
            echo json_encode($response);
            exit();
        }
        if (!$validator->isMinLength($adminEmail, 6)) {
            $response = array('success' => false, 'message' => 'L\'email doit faire au moins 2 caractères');
            echo json_encode($response);
            exit();
        }
        if (!$validator->isMaxLength($adminEmail, 64)) {
            $response = array('success' => false, 'message' => 'L\'email doit faire au maximum 20 caractères');
            echo json_encode($response);
            exit();
        }
        if(!$validator->isEmailValid($adminEmail)) {
            $response = array('success' => false, 'message' => 'Format d\'email invalide');
            echo json_encode($response);
            exit();
        }

        $conf = Config::getInstance();
        $conf->updateConfig(['bdd', 'prefix'], $bddPrefix);
        $conf->updateConfig(['website', 'name'], $siteName);
        $conf->updateConfig(['website', 'description'], $siteDescription);
        $conf->updateConfig(['mail', 'mailFrom'], $adminEmail);

        $response = array('success' => true, 'message' => 'Le formulaire a été traité avec succès');
        echo json_encode($response);
    }

    /**
     * Init the website
     * @return void
     */
    public function init()
    {

        //recuperer le contenu de mon script sql et remplacer le prefix et ajouter dans le script l'ajout du user de base
        $scriptPath = "/var/www/html/script/sql/create_db/script_carte_chance_template.sql";
        $scriptSQLcontent = file_get_contents($scriptPath);
        $scriptSQLParsed = str_replace('$prefix$', Config::getConfig()['bdd']['prefix'], $scriptSQLcontent);
        $userBdd = Config::getConfig()['bdd']['user'];
        
        $lengthKey = 20;
        $userId = "";
        for ($i = 0; $i < $lengthKey; $i++) {
            $userId .= mt_rand(0, 20);
        }

        $userToken = "";
        for ($i = 0; $i < $lengthKey; $i++) {
            $userToken .= mt_rand(0, 20);


        $stringInsertUser = " INSERT INTO ". Config::getConfig()['bdd']['prefix'] . "_user (id, pseudo, first_name, last_name, email, password, email_confirmation, confirm_and_reset_token, phone_number, date_inscription, role_id)
        VALUES ( uuid_generate_v4(), '". $userBdd['pseudo'] ."', '" . $userBdd['firstname'] . "', '" . $userBdd['lastname'] . "', '" . $userBdd['email'] . "', '" . $userBdd['password'] . "', TRUE," . $userToken . ", " . $userBdd['phone'] . ", '" . date("Y-m-d H:i:s") . "', (SELECT id FROM " . Config::getConfig()['bdd']['prefix'] . "_role WHERE role_name = 'admin'));";

        $scriptSQLParsed .= $stringInsertUser;
        

        //si le script a bien été modifié, on execute le script
        $dbHost = Config::getConfig()['bdd']['host']; 
        $dbName = Config::getConfig()['bdd']['dbname']; 
        $dbPort = Config::getConfig()['bdd']['port'];  
        $dbUser = Config::getConfig()['bdd']['username']; 
        $dbPass = Config::getConfig()['bdd']['password']; 
        
        try {
                
            $pdo = new PDO("pgsql:host=" . $dbHost . ";dbname=" . $dbName . ";port=" . $dbPort , $dbUser , $dbPass);
            $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, 1);
            $queryPrepared = $pdo->prepare($scriptSQLParsed);
            $res = $queryPrepared->execute();

            if($res){
                
                //si le script a bien été executé, on met à jour le fichier de config
                $conf = Config::getInstance();
                $conf->updateConfig(['installation', 'done'], true);
                $conf->updateConfig(['installation', 'on-going'], false);
                $response = array('success' => true, 'message' => 'L\'initialisation du site a été effectuée avec succès');
                echo json_encode($response);
            }
            else{
                
                $response = array('success' => false, 'message' => 'Erreur lors du lancement du script return false');
                echo json_encode($response);
            }

        } catch (PDOException $e) {
            // Gérer les erreurs de connexion ou d'exécution du script SQL
            echo "Erreur : " . $e->getMessage();
            $response = array('success' => false, 'message' => 'Erreur lors du lancement du script SQL');
            echo json_encode($response);
        }

    }
}
