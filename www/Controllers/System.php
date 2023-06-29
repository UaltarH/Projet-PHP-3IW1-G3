<?php

namespace App\Controllers;

use App\Core\View;

use App\Forms\CreateUser;
use App\Forms\SelectCategoryArticle;
use App\Forms\CreateArticleGame;
use App\Forms\CreateArticleAboutGame;

use App\Models\User;
use App\Models\Category_article;
use App\Models\Category_jeux;
use App\Models\Article;
use App\Models\Jeux;
use App\Models\JoinTable\Article_jeux;

use mysql_xdevapi\Exception;

use function App\Services\AddFileContent\AddFileContentFunction;
require_once '/var/www/html/Services/AddFileContent.php';

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

    public function articlesManagement(): void
    {
        //utilisateur connecter et admin:

        $view = new View("Article/articleManagment", "back", 2);
        //récuperer toutes les category d'article qui existe dans la bdd, 
        $optionsCategoriesArticle = [];
        $category_article = new Category_article();
        $resultQuery = $category_article->selectAll();
        foreach($resultQuery as $category){
            $optionsCategoriesArticle[$category->getId()] = $category->getCategoryName();
        }

        //créer le formulaire pour selectionner le type d'article:
        $formCategoryArticle = new SelectCategoryArticle();
        $formCategoryArticle->setConfig($optionsCategoriesArticle);

        if($formCategoryArticle->isSubmited() && $formCategoryArticle->isValid()){
            //get the category of article
            $category_article = $category_article->getOneWhere(["id" => $_POST['categoryArticle']]);
            if(is_bool($category_article)){
                die("Erreur: la catégorie d'article n'existe pas");
            }
            else{
                switch($category_article->getCategoryName()){
                    case "Jeux":
                        header("Location: sys/article/createArticleGame");
                        break;
                    case "Trucs et astuces":
                        header("Location: sys/article/createArticleAboutGame");
                        break;
                    default:
                        die("Erreur: la catégorie d'article n'existe pas");
                }
            }
        }
        else{
            //afficher le formulaire pour selectionner le type d'article:
            $view->assign("formCategoryArticle", $formCategoryArticle->getConfig());
            $view->assign("formCategoryArticleErrors", $formCategoryArticle->errors);
        }
    }

    public function addArticleGame():void 
    {
        //utilisateur connecter et admin:

        $view = new View("Article/articleManagment", "back", 2);
        //récuperer toutes les catégoris des jeux qui existe dans la bdd
        $optionsCategoryGames = [];
        $category_jeux = new Category_jeux();
        $resultQueryAllCategoryGames = $category_jeux->selectAll();
        foreach($resultQueryAllCategoryGames as $categoryGame){
            $optionsCategoryGames[$categoryGame->getId()] = $categoryGame->getCategoryName();
        }

        //créer le formulaire pour creer un article game:            
        $formCreateArticleGame = new CreateArticleGame();        
        $formCreateArticleGame->setConfig($optionsCategoryGames);

        if($formCreateArticleGame->isSubmited() && $formCreateArticleGame->isValid()){
            //create article game
            $categoryArticleGame = 1;
            $errorMessage = [];
            $article = new Article();

            $whereSql = ["title" => $_POST['titleGame']];
            $resultQueryExist = $article->existOrNot($whereSql);
            if(is_bool($resultQueryExist) || $resultQueryExist["column_exists"] == "none_exists"){ 
                //il n'y a aucun elements dans la table article qui contiens le meme titre
                $article->setTitle($_POST['titleGame']);
                $article->setContent($_POST['content']);
                $article->setCreatedDate(date("Y-m-d H:i:s"));
                $article->setUpdatedDate(date("Y-m-d H:i:s"));
                $article->setCategoryId($categoryArticleGame);

                $responseQuery = $article->save();
                $idNewArticle = $responseQuery->idNewElement;
                if($responseQuery->success){
                    //article added in bdd
                    //add the game in bdd
                    $game = new Jeux();

                    $whereSql = ["title" => $_POST['titleGame']];
                    $resultQueryExist = $game->existOrNot($whereSql);
                    if(is_bool($resultQueryExist) || $resultQueryExist["column_exists"] == "none_exists"){ 
                        //il n'y a aucun elements dans la table jeu qui contiens le meme titre 
                        $game->setTitle($_POST['titleGame']);
                        $game->setCategory_id($_POST['categoryGame']);
                        $responseInsert = $game->save();
                        $idNewGame = $responseInsert->idNewElement;

                        if($responseInsert->success){
                            //game added in bdd
                            //add in jointable article_jeux ref in bdd
                            $article_jeux = new Article_jeux();
                            $article_jeux->setArticleId($idNewArticle);
                            $article_jeux->setJeuxId($idNewGame);
                            if($article_jeux->insertIntoJoinTable()){
                                //article_jeux ref added in bdd
                                //add the content of the article in solution + in content table + in join table article_content
                                $countfiles = count($_FILES['imagesArticle']['name']);
                                if($countfiles > 0){
                                    for($i=0;$i<$countfiles;$i++){
                                        $filename = str_replace(" ", "_", $_FILES['imagesArticle']['name'][$i]);
                                        $articleName = str_replace(" ", "_", $_POST['titleGame']);

                                        $arrayConfContent = [];
                                        $arrayConfContent['directory'] = "/var/www/html/uploads/articles/".$articleName."/";
                                        $arrayConfContent['location'] = $arrayConfContent['directory'].$filename;
                                        $arrayConfContent['fileName'] = $filename;
                                        $arrayConfContent['fileContent'] = $_FILES['imagesArticle']['tmp_name'][$i];
                                        $arrayConfContent['fileExtension'] = strtolower(pathinfo($arrayConfContent['location'],PATHINFO_EXTENSION));
                                        $arrayConfContent['validExtensions'] = array("jpg","jpeg","png","svg");
                                        $arrayConfContent['joinTableClass'] = "Article_content";
                                        $arrayConfContent['joinTableId'] = $idNewArticle;
                                        $arrayConfContent['joinTableMethodToSetId'] = "setArticleId";
                                        
                                        $responseAddContent = AddFileContentFunction($arrayConfContent);
                                        if($responseAddContent->success){
                                            //image article ajouté
                                            //echo "image article ".$filename." ajouté ";
                                        }
                                        else{
                                            //image article non ajouté en bdd
                                            $errorMessage [] = "image article ".$filename." non ajouté en bdd";
                                        }
                                    }
                                } 
                                //enfin ajouter la photo du jeu dans notre solution puis en base de donnée: 
                                
                                $filename = str_replace(" ", "_", $_FILES['imageGame']['name']);
                                $gameName = str_replace(" ", "_", $_POST['titleGame']);
                                

                                $arrayConfContent = [];
                                $arrayConfContent['directory'] = "/var/www/html/uploads/jeux/".$gameName."/";
                                $arrayConfContent['location'] = $arrayConfContent['directory'].$filename;
                                $arrayConfContent['fileName'] = $filename;
                                $arrayConfContent['fileContent'] = $_FILES['imageGame']['tmp_name'];
                                $arrayConfContent['fileExtension'] = strtolower(pathinfo($arrayConfContent['location'],PATHINFO_EXTENSION));
                                $arrayConfContent['validExtensions'] = array("jpg","jpeg","png","svg");
                                $arrayConfContent['joinTableClass'] = "Jeux_content";
                                $arrayConfContent['joinTableId'] = $idNewGame;
                                $arrayConfContent['joinTableMethodToSetId'] = "setJeuId";
                                
                                $responseAddContent = AddFileContentFunction($arrayConfContent);
                                if($responseAddContent->success){
                                    //image du jeu ajouter en bdd
                                    //echo "image game ".$filename." ajouté ";
                                }
                                else{
                                    //image non ajouté en bdd
                                    $errorMessage [] = "image game ".$filename." non ajouté en bdd";
                                    $errorMessage [] = $responseAddContent->message;
                                }
                            }
                            else{
                                //error : add in jointable article_jeux ref in bdd
                                $errorMessage [] = "erreur sql : article_jeux ref non ajouté en bdd";
                            }
                        }
                        else{
                            //error add game in bdd
                            $errorMessage [] = "erreur sql : jeu non ajouté en bdd";
                        }    
                    } else{
                        //error add game in bdd : title already used
                        $errorMessage [] = "Le titre du jeu existe déjà ";
                    }
                }
                else {
                    //article not added in bdd
                    $errorMessage [] = "erreur sql : article non ajouté en bdd";
                }
            }
            else {
                //article not added in bdd : title already used
                $errorMessage [] = "Le titre de l'article existe déjà ";
            }                
            if(count($errorMessage) > 0){
                $view->assign("errorMessage", $errorMessage);
            }
            else{
                $view->assign("successMessage", "L'article a bien été ajouté");
            }
        }
        else{
            $view->assign("FormCreateArticleGame", $formCreateArticleGame->getConfig());
            $view->assign("FormCreateArticleGameErrors", $formCreateArticleGame->errors);
        }
    }

    public function addArticleAboutGame():void
    {
        //utilisateur connecter et admin:

        $view = new View("Article/articleManagment", "back", 2);

        //récuperer tout les jeux qui existe dans la bdd
        $optionsGames= [];
        $Game = new Jeux(); 
        $resultQueryAllGames = $Game->selectAll();
        foreach($resultQueryAllGames as $game){
            $optionsGames[$game->getId()] = $game->getTitle();
        }

        //créer le formulaire pour creer un article about game(truc et astuce):
        $formCreateArticleAboutGame = new CreateArticleAboutGame();
        $formCreateArticleAboutGame->setConfig($optionsGames);

        if($formCreateArticleAboutGame->isSubmited() && $formCreateArticleAboutGame->isValid()){
            //ajouter l'article en base de donnée:
            $article = new Article();
            $categoryArticleAboutGame = 2;
            $errorMessage = [];
            
            $whereSql = ["title" => $_POST['titleArticle']];
            $resultQueryExist = $article->existOrNot($whereSql);
            if(is_bool($resultQueryExist) || $resultQueryExist["column_exists"] == "none_exists"){ 
                //il n'y a aucun elements dans la table qui contiens le meme titre 
                $article->setTitle($_POST['titleArticle']);
                $article->setContent($_POST['content']);
                $article->setCreatedDate(date("Y-m-d H:i:s"));
                $article->setUpdatedDate(date("Y-m-d H:i:s"));
                $article->setCategoryId($categoryArticleAboutGame);

                $responseQuery = $article->save();
                $idNewArticle = $responseQuery->idNewElement;
                if($responseQuery->success){ 
                    //article ajouté en bdd 
                    //enusite ajouter l'id de l'article et l'id du jeux dans la table de jointure entre article et jeux
                    $article_jeux = new Article_jeux();
                    $article_jeux->setArticleId($idNewArticle);
                    $article_jeux->setJeuxId($_POST['game']);
                    if($article_jeux->insertIntoJoinTable()){
                        //article_jeux ajouté en bdd
                        //ajouter les contenu (images) de l'article dans un dossier spécifique de notre solution
                        $countfiles = count($_FILES['imagesArticle']['name']);
                        if($countfiles > 0){
                            for($i=0;$i<$countfiles;$i++){
                                $filename = str_replace(" ", "_", $_FILES['imagesArticle']['name'][$i]);
                                $articleName = str_replace(" ", "_", $_POST['titleArticle']);
                                
                                $arrayConfContent = [];
                                $arrayConfContent['directory'] = "/var/www/html/uploads/articles/".$articleName."/";
                                $arrayConfContent['location'] = $arrayConfContent['directory'].$filename;
                                $arrayConfContent['fileName'] = $filename;
                                $arrayConfContent['fileContent'] = $_FILES['imagesArticle']['tmp_name'][$i];
                                $arrayConfContent['fileExtension'] = strtolower(pathinfo($arrayConfContent['location'],PATHINFO_EXTENSION));
                                $arrayConfContent['validExtensions'] = array("jpg","jpeg","png","svg");
                                $arrayConfContent['joinTableClass'] = "Article_content";
                                $arrayConfContent['joinTableId'] = $idNewArticle;
                                $arrayConfContent['joinTableMethodToSetId'] = "setArticleId";
                                
                                $responseAddContent = AddFileContentFunction($arrayConfContent);
                                if($responseAddContent->success){
                                    //image ajouté 
                                    //echo " image ajouté ";
                                }
                                else{
                                    //image non ajouté en bdd
                                    $errorMessage [] = "image ".$filename." non ajouté en bdd";
                                }
                            }
                        }
                    }
                    else{
                        //joinTable ref not added
                        $errorMessage [] = "erreur sql : article_jeux ref non ajouté en bdd";
                    }
                }
                else{
                    //article not added in bdd 
                    $errorMessage [] = "erreur sql : article non ajouté en bdd";
                }
            } else{
                // title is already used
                $errorMessage [] = "Le titre de l'article existe déjà ";
            }

            if(count($errorMessage) > 0){
                $view->assign("errorMessage", $errorMessage);
            }
            else{
                $view->assign("successMessage", "L'article a bien été ajouté");
            }
        }
        else{
            $view->assign("FormCreateArticleAboutGame", $formCreateArticleAboutGame->getConfig());
            $view->assign("FormCreateArticleAboutGameErrors", $formCreateArticleAboutGame->errors);
        }
    }
}