<?php

namespace App\Controllers;

use App\Core\View;

use App\Forms\SelectCategoryArticle;
use App\Forms\CreateArticleGame;
use App\Forms\CreateArticleAboutGame;

use App\Models\Category_article;
use App\Models\Category_jeux;
use App\Models\JoinTable\Article_jeux;
use App\Models\JoinTable\Article_content;
use App\Models\JoinTable\Jeux_content;
use App\Models\Article;
use App\Models\Jeux;
use App\Models\Content;

use function App\Services\TestUserIsConnected\UserExistForAccess;
use function PHPSTORM_META\elementType;

require_once '/var/www/html/Services/TestUserIsConnected.php';

class Pages 
{
    public function getPage(){
        //TODO: adapter la fonction getPage pour récuperer le contenu associé a l'article puis utiliser assign avec les informations utile pour les afficher dans la vue
        //tester si l'utilisateur est connecter: 
        $error = false;
        $messageInfo = [];
        session_start();
        
        if (isset($_SESSION['pseudo'])) {
            //tester si le pseudo et si le compte est bien confirmé         
            $response = UserExistForAccess($_SESSION['pseudo']);
            if($response->success == false) {
                $messageInfo['noConnection'] = $response->message;
                $error = true;
            }
        } else {
            $messageInfo['noConnection'] = 'Vous etes pas connecter, vous allez etre rediriger vers la page de connexion.';
            $error = true;
        }
        
        if($error){
            //utilisateur non connecter:
            $view = new View("Common/NotAccess", "unauthorised");
            $view->assign("messageInfo", $messageInfo);
            $view->assign("typeError", 'noConnection');
        }
        else {
            //utilisateur connecter:
            //recuperer l'article en base de donnée:
            //tester si il y a un id dans l'url( avec GET )(le numéro represente l'id de l'article en bdd)
            if(isset($_GET['number'])){
                //si oui tester si il existe en base un article qui possede cet id :
                $article = new Article();
                $whereSql = ["id" => $_GET['number']];
                $resultQuery = $article->getOneWhere($whereSql);
                if(is_bool($resultQuery)) { //
                    //article not found:
                    $view = new View("Common/NotAccess", "unauthorised");
                    $messageInfo['noArticleFound'] = "La page que vous essayer d\'acceder n\'existe pas vous allez etre rediriger vers la page home" ;
                    $view->assign("messageInfo", $messageInfo);
                    $view->assign("typeError", 'noArticleFound');
                } else{
                    //article found:
                    $view = new View("Article/article", "front");
                    $view->assign("titre", $resultQuery->getTitle());
                    $view->assign("content", $resultQuery->getContent());
                }
                

            } else {
                //si non retourner une erreur 404 ou une redirection vers la page d'accueil
                $view = new View("Common/NotAccess", "unauthorised");
                $messageInfo['noArticleFound'] = "La page que vous essayer d\'acceder n\'existe pas vous allez etre rediriger vers la page home" ;
                $view->assign("messageInfo", $messageInfo);
                $view->assign("typeError", 'noArticleFound');
            }
        }
    }

    public function createPage(){
        //tester si l'utilisateur est connecter et si il est admin (roleId = 2)
        $error = false;
        $messageInfo = [];
        session_start();
        
        if (isset($_SESSION['pseudo'])) {
            //tester si le pseudo et si le compte est bien confirmé         
            $response = UserExistForAccess($_SESSION['pseudo']);
            if($response->success == false) {
                $messageInfo['noConnection'] = $response->message;
                $error = true;
            } else {
                //tester si l'utilisateur est admin:
                // if($response->userResult->getRoleId() != 2){
                //     $messageInfo['noConnection'] = "Vous n'avez pas les droits pour acceder a cette page";
                //     $error = true;
                // }
            }
            
        } else {
            $messageInfo['noConnection'] = 'Vous etes pas connecter, vous allez etre rediriger vers la page de connexion.';
            $error = true;
        }
        
        if($error){
            //utilisateur non connecter:
            $view = new View("Common/NotAccess", "unauthorised");
            $view->assign("messageInfo", $messageInfo);
            $view->assign("typeError", 'noConnection');
        } else {
            //utilisateur connecter et admin:
            $view = new View("Article/articleManagment", "back", 2);
        
            //récuperer toutes les category d'article qui existe dans la bdd, 
            $optionsCategoriesArticle = [];
            $category_article = new Category_article();
            $resultQuery = $category_article->selectAll();
            foreach($resultQuery as $category){
                $optionsCategoriesArticle[$category->getId()] = $category->getCategoryName();
            }

            //récuperer tout les jeux qui existe dans la bdd
            $optionsGames= [];
            $Game = new Jeux(); 
            $resultQueryAllGames = $Game->selectAll();
            foreach($resultQueryAllGames as $game){
                $optionsGames[$game->getId()] = $game->getTitle();
            }
            //récuperer toutes les catégoris des jeux qui existe dans la bdd
            $optionsCategoryGames = [];
            $category_jeux = new Category_jeux();
            $resultQueryAllCategoryGames = $category_jeux->selectAll();
            foreach($resultQueryAllCategoryGames as $categoryGame){
                $optionsCategoryGames[$categoryGame->getId()] = $categoryGame->getCategoryName();
            }

            //créer le formulaire pour selectionner le type d'article:
            $formCategoryArticle = new SelectCategoryArticle();
            $configFormCategoryArticle = $formCategoryArticle->getConfig($optionsCategoriesArticle);

            //créer le forulaire pour creer un article game:
            $formCreateArticleGame = new CreateArticleGame();
            $gameCategory[1] = $optionsCategoriesArticle[1]; //set a unique option for the select (category: articleGame)
            $configFormCreateArticleGame = $formCreateArticleGame->getConfig($gameCategory, $optionsCategoryGames);
            
            //créer le formulaire pour creer un article about game(truc et astuce):
            $formCreateArticleAboutGame = new CreateArticleAboutGame();
            $aboutGameCategory[2] = $optionsCategoriesArticle[2]; //set a unique option for the select (category: articleAboutGame)
            $configFormCreateArticleAboutGame = $formCreateArticleAboutGame->getConfig($aboutGameCategory, $optionsGames);

            //formulaire pour creer un article de categorie about game (truc et astuce en bdd):
            if($formCreateArticleAboutGame->isSubmited()){
                if($formCreateArticleAboutGame->isValid()){
                    //ajouter l'article en base de donnée:
                    $article = new Article();

                    $whereSql = ["title" => $_POST['titleArticle']];
                    $resultQueryExist = $article->existOrNot($whereSql);
                    if(is_bool($resultQueryExist) || $resultQueryExist["column_exists"] == "none_exists"){ 
                        //il n'y a aucun elements dans la table qui contiens le meme titre 
                        $article->setTitle($_POST['titleArticle']);
                        $article->setContent($_POST['content']);
                        $article->setCreatedDate(date("Y-m-d H:i:s"));
                        $article->setUpdatedDate(date("Y-m-d H:i:s"));
                        $article->setCategoryId($_POST['categoryArticle']);

                        $responseQuery = $article->save();
                        $idNewArticle = $responseQuery->idNewElement;
                        if($responseQuery->success){ 
                            //article ajouté en bdd 
                            echo"infoCreationArticle", "l'article a bien été crée";
                            
                            //enusite ajouter l'id de l'article et l'id du jeux dans la table de jointure entre article et jeux
                            $article_jeux = new Article_jeux();
                            $article_jeux->setArticleId($idNewArticle);
                            $article_jeux->setJeuxId($_POST['game']);
                            if($article_jeux->insertIntoJoinTable()){
                                //article_jeux ajouté en bdd
                                echo"l'article a bien été crée et lier au jeux";

                                //ajouter le contenu (images) de l'article dans un dossier spécifique de notre solution
                                $countfiles = count($_FILES['imagesArticle']['name']);
                                if($countfiles > 0){
                                    for($i=0;$i<$countfiles;$i++){
                                        $filename = str_replace(" ", "_", $_FILES['imagesArticle']['name'][$i]);
                                        $articleName = str_replace(" ", "_", $_POST['titleArticle']);
                                        ## Location
                                        $directory = "/var/www/html/uploads/articles/".$articleName."/";
                                        $location = $directory.$filename;
                                        
                                        $extension = pathinfo($location,PATHINFO_EXTENSION);
                                        $extension = strtolower($extension);
                            
                                        ## File upload allowed extensions
                                        $valid_extensions = array("jpg","jpeg","png","svg");
                                        ## Check file extension
                                        if(in_array(strtolower($extension), $valid_extensions)) {
                                            // Create directory if it doesn't exist
                                            if (!is_dir($directory)) {
                                                mkdir($directory, 0777, true);
                                            }
                                            ## Upload file
                                            if(move_uploaded_file($_FILES['imagesArticle']['tmp_name'][$i],$location)){
                                                echo "file name : ".$filename." added<br/>";

                                                //ensuite ajouter le chemin de l'image dans la table content de la bdd
                                                $content = new Content();
                                                $content->setPathContent($location);
                                                $resultQuery = $content->save();
                                                if($resultQuery->success){
                                                    //content ajouté en bdd
                                                    echo"l'article a bien été crée et lier au jeux et le contenu a bien été ajouté";
                                                    //ensuite ajouter l'id content et l'id article dans la table de jointure entre article et content
                                                    $article_content = new Article_content();
                                                    $article_content->setArticleId($idNewArticle);
                                                    $article_content->setContentId($resultQuery->idNewElement);
                                                    if($article_content->insertIntoJoinTable()){
                                                        //article_content ajouté en bdd
                                                        echo"l'article a bien été crée et lier au jeux et le contenu a bien été ajouté et lier a l'article";
                                                    }
                                                    else{
                                                        //article_content non ajouté en bdd
                                                        echo "l'article a bien été crée et lier au jeux et le contenu a bien été ajouté mais n'a pas pu etre lier a l'article";
                                                    }
                                                }
                                            }
                                            else{
                                                echo "file name : ".$filename."not added<br/>";
                                            }
                                        }
                                        else {
                                            echo("extension non valide");
                                        }
                                    }
                                } else {
                                    echo("aucune image n'a été recu");
                                }
                            }
                            else{
                                //article_jeux non ajouté en bdd
                                echo"l'article a bien été crée mais n'a pas pu etre lier au jeux";
                            }
                        }
                        else{
                            //article non ajouté en bdd 
                            echo("l'article n'a pas pu etre crée");
                        }
                    } else{
                        echo"le titre de l'article existe deja";
                    }
                    

                } else {
                    //réafficher le formulaire pour creer un articleAboutGame et afficher les erreurs:
                    $view->assign("FormCreateArticleAboutGame", $configFormCreateArticleAboutGame);
                    $view->assign("FormCreateArticleAboutGameErrors", $formCreateArticleAboutGame->errors);
                }
            }
            else if($formCreateArticleGame->isSubmited()){
                if($formCreateArticleGame->isValid()){
                    //ajouter l'article en base de donnée:
                    //premierement ajouter le jeu en bdd 
                    $article = new Article();

                    $whereSql = ["title" => $_POST['titleGame']];
                    $resultQueryExist = $article->existOrNot($whereSql);
                    if(is_bool($resultQueryExist) || $resultQueryExist["column_exists"] == "none_exists"){ 
                        //il n'y a aucun elements dans la table article qui contiens le meme titre 
                        $article->setTitle($_POST['titleGame']);
                        $article->setContent($_POST['content']);
                        $article->setCreatedDate(date("Y-m-d H:i:s"));
                        $article->setUpdatedDate(date("Y-m-d H:i:s"));
                        $article->setCategoryId($_POST['categoryArticle']);

                        $responseQuery = $article->save();
                        $idNewArticle = $responseQuery->idNewElement;
                        if($responseQuery->success){
                            //article ajouté en bdd
                            //ensuite ajouter le jeu en bdd
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
                                    //jeu ajouté en bdd
                                    echo" le jeu a bien été ajouté en bdd ";

                                    //ensuite ajouter dans la table de jointure entre article et jeux
                                    $article_jeux = new Article_jeux();
                                    $article_jeux->setArticleId($idNewArticle);
                                    $article_jeux->setJeuxId($idNewGame);
                                    if($article_jeux->insertIntoJoinTable()){
                                        //article_jeux ajouté en bdd
                                        echo" l'article a bien été crée et lier au jeux ";

                                        //ensuite ajouter le content pour l'article dans notre solution de stockage
                                        $countfiles = count($_FILES['imagesArticle']['name']);
                                        if($countfiles > 0){
                                            for($i=0;$i<$countfiles;$i++){
                                                $filename = str_replace(" ", "_", $_FILES['imagesArticle']['name'][$i]);
                                                $articleName = str_replace(" ", "_", $_POST['titleGame']);
                                                ## Location
                                                $directory = "/var/www/html/uploads/articles/".$articleName."/";
                                                $location = $directory.$filename;
                                                
                                                $extension = pathinfo($location,PATHINFO_EXTENSION);
                                                $extension = strtolower($extension);
                                    
                                                ## File upload allowed extensions
                                                $valid_extensions = array("jpg","jpeg","png","svg");
                                                ## Check file extension
                                                if(in_array(strtolower($extension), $valid_extensions)) {
                                                    // Create directory if it doesn't exist
                                                    if (!is_dir($directory)) {
                                                        mkdir($directory, 0777, true);
                                                    }
                                                    ## Upload file
                                                    if(move_uploaded_file($_FILES['imagesArticle']['tmp_name'][$i],$location)){
                                                        echo "file name : ".$filename." added<br/>";

                                                        //ensuite ajouter le content en bdd
                                                        //ensuite ajouter le chemin de l'image dans la table content de la bdd
                                                        $content = new Content();
                                                        $content->setPathContent($location);
                                                        $resultQuery = $content->save();
                                                        if($resultQuery->success){
                                                            //content ajouté en bdd
                                                            echo"l'article a bien été créer et lier au jeux et le contenu a bien été ajouté";
                                                            //ensuite ajouter l'id content et l'id article dans la table de jointure entre article et content
                                                            $article_content = new Article_content();
                                                            $article_content->setArticleId($idNewArticle);
                                                            $article_content->setContentId($resultQuery->idNewElement);
                                                            if($article_content->insertIntoJoinTable()){
                                                                //article_content ajouté en bdd
                                                                echo"l'article a bien été crée et lier au jeux et le contenu a bien été ajouté et lier a l'article";

                                                                //enfin ajouter la photo du jeu: 
                                                            }
                                                            else{
                                                                //article_content non ajouté en bdd
                                                                echo "l'article a bien été crée et lier au jeux et le contenu a bien été ajouté mais n'a pas pu etre lier a l'article";
                                                            }
                                                        }
                                                    }
                                                    else {
                                                        echo "file name : ".$filename." not added<br/>";
                                                    }
                                                } else {
                                                    echo("extension non valide pour une des image de l'article");
                                                }
                                            }
                                        } else{
                                            echo("aucune image n'a été recu pour larticle");
                                        }
                                        //enfin ajouter la photo du jeu dans notre solution puis en base de donnée: 
                                        
                                        $filename = str_replace(" ", "_", $_FILES['imageGame']['name']);
                                        $gameName = str_replace(" ", "_", $_POST['titleGame']);
                                        ## Location
                                        $directory = "/var/www/html/uploads/jeux/".$gameName."/";
                                        $location = $directory.$filename;
                                        
                                        $extension = pathinfo($location,PATHINFO_EXTENSION);
                                        $extension = strtolower($extension);
                            
                                        ## File upload allowed extensions
                                        $valid_extensions = array("jpg","jpeg","png","svg");
                                        ## Check file extension
                                        if(in_array(strtolower($extension), $valid_extensions)) {
                                            // Create directory if it doesn't exist
                                            if (!is_dir($directory)) {
                                                mkdir($directory, 0777, true);
                                            }
                                            ## Upload file
                                            if(move_uploaded_file($_FILES['imageGame']['tmp_name'],$location)){
                                                echo "file name : ".$filename." added<br/>";

                                                //ensuite ajouter le content en bdd
                                                //ensuite ajouter le chemin de l'image dans la table content de la bdd
                                                $content = new Content();
                                                $content->setPathContent($location);
                                                $resultQuery = $content->save();
                                                if($resultQuery->success){
                                                    //content ajouté en bdd
                                                    echo"l'article a bien été créer et lier au jeux et le contenu a bien été ajouté";
                                                    
                                                    //ensuite ajouter l'id content et l'id article dans la table de jointure entre article et content
                                                    $article_content = new Jeux_content();
                                                    $article_content->setJeuId($idNewGame);
                                                    $article_content->setContentId($resultQuery->idNewElement);
                                                    if($article_content->insertIntoJoinTable()){
                                                        //article_content ajouté en bdd
                                                        echo"tout cest bien passé";
                                                    }
                                                    else{
                                                        //article_content non ajouté en bdd
                                                        echo "l'insert ion dans la table de jointure jeux content n'a pas fonctionné";
                                                    }
                                                } else {
                                                    echo "content game not added in bdd";
                                                }
                                            }
                                            else {
                                                echo "file name : ".$filename." not added<br/>";
                                            }
                                        } else {
                                            echo("extension non valide pour limage du jeux");
                                        }                                            
                                    }
                                    else{
                                        //article_jeux non ajouté en bdd
                                        echo" l'article a bien été crée mais n'a pas pu etre lier au jeux ";
                                    }
                                }
                                else{
                                    //jeu non ajouté en bdd 
                                    echo("le jeu n'a pas pu etre ajouté a la bdd ");
                                }    
                            } else{
                                //il y a deja un element dans la table jeu qui contiens le meme titre
                                echo"le titre du jeu existe deja ";
                            }
                        }
                        else {
                            //article non ajouté en bdd 
                            echo("l'article n'a pas pu etre ajouté a la bdd ");
                        }
                    }
                    else {
                        //il y a deja un element dans la table qui contiens le meme titre
                        echo"le titre de l'article existe deja ";
                    }
                } else { 
                    //réafficher le formulaire pour creer un articleGame et afficher les erreurs:
                    $view->assign("FormCreateArticleGame", $configFormCreateArticleGame);
                    $view->assign("FormCreateArticleGameErrors", $formCreateArticleGame->errors);
                }
            }
            else if($formCategoryArticle->isSubmited()){
                if($formCategoryArticle->isValid()){
                    if($_POST['categoryArticle'] == "1"){ //article Game 
                        //afficher le formulaire pour creer un articleGame
                        $view->assign("FormCreateArticleGame", $configFormCreateArticleGame);
                        $view->assign("FormCreateArticleGameErrors", $formCreateArticleGame->errors);                        
                    } else { //article About a game 
                        //afficher le formulaire pour creer un articleAboutGame                    
                        $view->assign("FormCreateArticleAboutGame", $configFormCreateArticleAboutGame);
                        $view->assign("FormCreateArticleAboutGameErrors", $formCreateArticleAboutGame->errors);
                    }
                } else {
                    //afficher le formulaire pour selectionner le type d'article:
                    $view->assign("formCategoryArticle", $configFormCategoryArticle);
                    $view->assign("formCategoryArticleErrors", $formCategoryArticle->errors);
                }
            }
            else {
                //afficher le formulaire pour selectionner le type d'article:
                $view->assign("formCategoryArticle", $configFormCategoryArticle);
                $view->assign("formCategoryArticleErrors", $formCategoryArticle->errors);
            }
        }
    }

    //faire le crud avec datatables pour les pages 
}