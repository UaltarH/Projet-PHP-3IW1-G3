<?php

namespace App\Controllers;

use App\Core\View;

use App\Forms\CreateUser;
use App\Forms\EditUser;
use App\Repository\ArticleRepository;
use App\Repository\ArticleCategoryRepository;
use App\Repository\GameArticleRepository;
use App\Repository\GameCategoryRepository;
use App\Repository\GameRepository;
use App\Repository\UserRepository;
use App\Forms\SelectCategoryArticle;
use App\Forms\CreateArticleGame;
use App\Forms\CreateArticleAboutGame;

use App\Models\Article_Category;
use App\Models\Game_Category;
use App\Models\Article;
use App\Models\Game;
use App\Models\JoinTable\Game_Article;

use mysql_xdevapi\Exception;

use function App\Services\AddFileContent\AddFileContentFunction;
require_once '/var/www/html/Services/AddFileContent.php';

class System
{
    private ArticleRepository $articleRepository;
    private UserRepository $userRepository;
    private ArticleCategoryRepository $articleCategoryRepository;
    private GameCategoryRepository $gameCategoryRepository;
    private GameArticleRepository $gameArticleRepository;
    private GameRepository $gameRepository;
    public function __construct()
    {
        $this->articleRepository = new ArticleRepository();
        $this->articleCategoryRepository = new ArticleCategoryRepository();
        $this->gameCategoryRepository = new GameCategoryRepository();
        $this->userRepository = new UserRepository();
        $this->gameArticleRepository = new GameArticleRepository();
        $this->gameRepository = new GameRepository();
    }

    public function userlist(): void
    {
        //TODO: access right
        $roles = $this->userRepository->fetchRoles();
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


    public function articlesManagement(): void
    {
        $view = new View("Article/articleManagment", "back");

        //récuperer toutes les category d'article qui existe dans la bdd, 
        $optionsCategoriesArticle = [];
        $resultQuery = $this->articleCategoryRepository->selectAll(new Article_Category());
        foreach($resultQuery as $category){
            $optionsCategoriesArticle[$category->getId()] = $category->getCategoryName();
        }
        //récuperer toutes les catégoris des jeux qui existe dans la bdd
        $optionsCategoryGames = [];
        $resultQueryAllCategoryGames = $this->gameCategoryRepository->selectAll(new Game_Category());
        foreach($resultQueryAllCategoryGames as $categoryGame){
            $optionsCategoryGames[$categoryGame->getId()] = $categoryGame->getCategoryName();
        }
        //récuperer tout les jeux qui existe dans la bdd
        $optionsGames= [];
        $resultQueryAllGames = $this->gameRepository->selectAll(new Game());
        foreach($resultQueryAllGames as $game){
            $optionsGames[$game->getId()] = $game->getTitle();
        }

        //créer le formulaire pour selectionner le type d'article:
        $formCategoryArticle = new SelectCategoryArticle();
        $formCategoryArticle->setConfig($optionsCategoriesArticle);  
        
        //créer le formulaire pour creer un article game:            
        $formCreateArticleGame = new CreateArticleGame();        
        $formCreateArticleGame->setConfig($optionsCategoryGames);

        //créer le formulaire pour creer un article about game(truc et astuce):
        $formCreateArticleAboutGame = new CreateArticleAboutGame();
        $formCreateArticleAboutGame->setConfig($optionsGames);

        if($formCategoryArticle->isSubmited() && $formCategoryArticle->isValid()){
            //get the category of article
            $category_article = $this->articleCategoryRepository->getOneWhere(["id" => $_POST['categoryArticle']], new Article);
            if(is_bool($category_article)){
                die("Erreur: la catégorie d'article n'existe pas");
            }            
            else{
                var_dump($category_article->getCategoryName());
                switch($category_article->getCategoryName()){
                    case "Jeux":                        
                        $view->assign("formCreateArticleGame", $formCreateArticleGame->getConfig());
                        $view->assign("formCreateArticleGameErrors", $formCreateArticleGame->errors);
                        break;          
                    case "Trucs et astuces":
                        $view->assign("formCreateArticleAboutGame", $formCreateArticleAboutGame->getConfig());
                        $view->assign("formCreateArticleAboutGameErrors", $formCreateArticleAboutGame->errors);
                        break;
                    default:
                        die("Erreur: la catégorie d'article n'existe pas");
                }
            }
        } else{          
            $view->assign("formCategoryArticle", $formCategoryArticle->getConfig());
            $view->assign("formCategoryArticleErrors", $formCategoryArticle->errors);
        }        
    }

    //pas utilisé 
    public function articlesManagementv0(): void
    {
        //utilisateur connecter et admin:

        $view = new View("Article/articleManagment", "back");
        //récuperer toutes les category d'article qui existe dans la bdd, 
        $optionsCategoriesArticle = [];
        $resultQuery = $this->articleCategoryRepository->selectAll(new Article_Category());
        foreach($resultQuery as $category){
            $optionsCategoriesArticle[$category->getId()] = $category->getCategoryName();
        }

        //créer le formulaire pour selectionner le type d'article:
        $formCategoryArticle = new SelectCategoryArticle();
        $formCategoryArticle->setConfig($optionsCategoriesArticle);

        if($formCategoryArticle->isSubmited() && $formCategoryArticle->isValid()){
            //get the category of article
            $category_article = $this->articleCategoryRepository->getOneWhere(["id" => $_POST['categoryArticle']], new Article_Category());
            if(is_bool($category_article)){
                die("Erreur: la catégorie d'article n'existe pas");
            }
            else{
                switch($category_article->getCategoryName()){
                    case "Jeux":
                        header("Location: /sys/article/create-article-game");
                        break;
                    case "Trucs et astuces":
                        header("Location: /sys/article/create-article-about-game");
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
    //pas utilisé 
    public function addArticleGame():void 
    {
        //utilisateur connecter et admin:

        $view = new View("Article/articleManagment", "back");
        //récuperer toutes les catégoris des jeux qui existe dans la bdd
        $optionsCategoryGames = [];
        $resultQueryAllCategoryGames = $this->gameCategoryRepository->selectAll(new Game_Category());
        foreach($resultQueryAllCategoryGames as $categoryGame){
            $optionsCategoryGames[$categoryGame->getId()] = $categoryGame->getCategoryName();
        }

        //créer le formulaire pour creer un article game:            
        $formCreateArticleGame = new CreateArticleGame();        
        $formCreateArticleGame->setConfig($optionsCategoryGames);

        if($formCreateArticleGame->isSubmited() && $formCreateArticleGame->isValid()){
            //create article game
            //get the category article game id:
            $categoryArticleGame = $this->articleCategoryRepository->getOneWhere(["category_name" => "Jeux"], new Article_Category());
            if(is_bool($categoryArticleGame)){
                die("Erreur: la catégorie d'article Jeux n'existe pas");
            }

            $categoryArticleGameId = $categoryArticleGame->getId();
            $errorMessage = [];
            $article = new Article();

            $whereSql = ["title" => $_POST['titleGame']];
            $resultQueryExist = $this->articleRepository->existOrNot($whereSql, $article);
            if(is_bool($resultQueryExist) || $resultQueryExist["column_exists"] == "none_exists"){ 
                //il n'y a aucun elements dans la table article qui contiens le meme titre
                $article->setTitle($_POST['titleGame']);
                $article->setContent($_POST['content']);
                $article->setCreatedDate(date("Y-m-d H:i:s"));
                $article->setUpdatedDate(date("Y-m-d H:i:s"));
                $article->setCategoryId($categoryArticleGameId);

                $responseQuery = $this->articleRepository->save($article);
                $idNewArticle = $responseQuery->idNewElement;
                if($responseQuery->success){
                    //article added in bdd
                    //add the game in bdd
                    $game = new Game();

                    $whereSql = ["title" => $_POST['titleGame']];
                    $resultQueryExist = $this->gameRepository->existOrNot($whereSql, $game);
                    if(is_bool($resultQueryExist) || $resultQueryExist["column_exists"] == "none_exists"){ 
                        //il n'y a aucun elements dans la table jeu qui contiens le meme titre 
                        $game->setTitle($_POST['titleGame']);
                        $game->setCategory_id($_POST['categoryGame']);
                        $responseInsert = $this->gameRepository->save($game);
                        $idNewGame = $responseInsert->idNewElement;

                        if($responseInsert->success){
                            //game added in bdd
                            //add in jointable article_jeux ref in bdd
                            $article_jeux = new Game_Article();
                            $article_jeux->setArticleId($idNewArticle);
                            $article_jeux->setJeuxId($idNewGame);
                            if($this->gameArticleRepository->insertIntoJoinTable($article)){
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
                                $arrayConfContent['joinTableClass'] = "GameContent";
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
    //pas utilisé 
    public function addArticleAboutGame():void
    {
        //utilisateur connecter et admin:

        $view = new View("Article/articleManagment", "back");

        //récuperer tout les jeux qui existe dans la bdd
        $optionsGames= [];
        $resultQueryAllGames = $this->gameRepository->selectAll(new Game);
        foreach($resultQueryAllGames as $game){
            $optionsGames[$game->getId()] = $game->getTitle();
        }

        //créer le formulaire pour creer un article about game(truc et astuce):
        $formCreateArticleAboutGame = new CreateArticleAboutGame();
        $formCreateArticleAboutGame->setConfig($optionsGames);

        if($formCreateArticleAboutGame->isSubmited() && $formCreateArticleAboutGame->isValid()){
            //ajouter l'article en base de donnée:
            $article = new Article();

            //get the category about game id:
            $categoryArticleAboutGame = $this->articleCategoryRepository->getOneWhere(["category_name" => "Trucs et astuces"], new Article_Category());
            if(is_bool($categoryArticleAboutGame)){
                die("Erreur: la catégorie d'article trucs et astuces n'existe pas");
            }
            $categoryArticleAboutGameId = $categoryArticleAboutGame->getId();;
            $errorMessage = [];
            
            $whereSql = ["title" => $_POST['titleArticle']];
            $resultQueryExist = $this->articleRepository->existOrNot($whereSql, $article);
            if(is_bool($resultQueryExist) || $resultQueryExist["column_exists"] == "none_exists"){ 
                //il n'y a aucun elements dans la table qui contiens le meme titre 
                $article->setTitle($_POST['titleArticle']);
                $article->setContent($_POST['content']);
                $article->setCreatedDate(date("Y-m-d H:i:s"));
                $article->setUpdatedDate(date("Y-m-d H:i:s"));
                $article->setCategoryId($categoryArticleAboutGameId);

                $responseQuery = $this->articleRepository->save($article);
                $idNewArticle = $responseQuery->idNewElement;
                if($responseQuery->success){ 
                    //article ajouté en bdd 
                    //enusite ajouter l'id de l'article et l'id du jeux dans la table de jointure entre article et jeux
                    $article_jeux = new Game_Article();
                    $article_jeux->setArticleId($idNewArticle);
                    $article_jeux->setJeuxId($_POST['game']);
                    if($this->gameArticleRepository->insertIntoJoinTable($article_jeux)){
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