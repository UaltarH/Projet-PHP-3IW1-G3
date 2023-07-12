<?php

namespace App\Controllers;

use App\Core\View;

use App\Models\Article AS ArticleModel;
use App\Models\Category_article;
use App\Models\Category_jeux;
use App\Models\Jeux;
use App\Models\Comment;
use App\Models\Content;

use App\Models\JoinTable\Article_jeux;
use App\Models\JoinTable\Comment_article;
use App\Models\JoinTable\Article_content;

use function App\Services\AddFileContent\AddFileContentFunction;
use function App\Services\HttpMethod\getHttpMethodVarContent;
require_once '/var/www/html/Services/HttpMethod.php';
require_once '/var/www/html/Services/AddFileContent.php';

class Article
{
    public function getArticle(){
        $view = new View("Article/article", "front");
        //tester si il y a un id dans l'url( avec GET )(le numéro represente l'id de l'article en bdd)
        if(isset($_GET['number'])){
            //si oui tester si il existe en base un article qui possede cette id :
            $article = new ArticleModel();
            $whereSql = ["id" => $_GET['number']];
            $resultQuery = $article->getOneWhere($whereSql);
            if(is_bool($resultQuery)) { //
                //article not found:                
                $view->assign("error", 'Article Not Found');
            } else{
                //article found:
                $view->assign("titre", $resultQuery->getTitle());
                $view->assign("content", $resultQuery->getContent());
            }
        } else {
            //si non retourner une erreur 404 ou une redirection vers la page d'accueil
            $view->assign("error", 'Article Not Found');
        }        
    }

    public function GetAllArticlesGame(){
        $view = new View("Article/allArticlesGame", "front");
        $article = new ArticleModel();
        $whereSql = ["category_name" => "Jeux"];
        $fkInfosQuery = [
            [
                "table" => "carte_chance_category_article",
                "foreignKeys" => [
                    "originColumn" => "category_id",
                    "targetColumn" => "id"
                ]
            ]
        ];
        //"Trucs et astuces"
        $resultQuery = $article->selectWithFkAndWhere($fkInfosQuery,$whereSql);

        if(is_bool($resultQuery)) { //
            //article not found:                
            $view->assign("error", 'Article Not Found');
        } else{
            //article found:
            $view->assign("articles", $resultQuery);
        }
    }

    public function GetAllArticlesAboutGame(){
        $view = new View("Article/allArticlesAboutGame", "front");
        $article = new ArticleModel();
        $whereSql = ["category_name" => "Trucs et astuces"];
        $fkInfosQuery = [
            [
                "table" => "carte_chance_category_article",
                "foreignKeys" => [
                    "originColumn" => "category_id",
                    "targetColumn" => "id"
                ]
            ]
        ];
        //"Trucs et astuces"
        $resultQuery = $article->selectWithFkAndWhere($fkInfosQuery,$whereSql);

        if(is_bool($resultQuery)) { //
            //article not found:                
            $view->assign("error", 'Article Not Found');
        } else{
            //article found:
            $view->assign("articles", $resultQuery);
        }
    }

    public function articlesManagement(): void
    {
        $view = new View("Article/articleManagment", "back");

        //récuperer toutes les category d'article qui existe dans la bdd, 
        $optionsCategoriesArticle = [];
        $category_article = new Category_article();
        $resultQuery = $category_article->selectAll();
        foreach($resultQuery as $category){
            $optionsCategoriesArticle[$category->getId()] = $category->getCategoryName();
        }
        $optionsForms["categoriesArticle"] = $optionsCategoriesArticle;

        //récuperer toutes les catégoris des jeux qui existe dans la bdd
        $optionsCategoryGames = [];
        $category_jeux = new Category_jeux();
        $resultQueryAllCategoryGames = $category_jeux->selectAll();
        foreach($resultQueryAllCategoryGames as $categoryGame){
            $optionsCategoryGames[$categoryGame->getId()] = $categoryGame->getCategoryName();
        }
        $optionsForms["categoriesGame"] = $optionsCategoryGames;
        
        //récuperer tout les jeux qui existe dans la bdd
        $optionsGames= [];
        $Game = new Jeux(); 
        $resultQueryAllGames = $Game->selectAll();
        foreach($resultQueryAllGames as $game){
            $optionsGames[$game->getId()] = $game->getTitle();
        }

        $optionsForms["games"] = $optionsGames;
        $view->assign("optionsForms", $optionsForms);      
    }

    public function articleDatatable(): void
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
        $article = new ArticleModel();
        echo json_encode($article->list([
            "columns" => ["title", "created_date", "updated_date", "content", "category_name", "category_game_name", "title_game" ],
            "start" => $start,
            "length" => $length,
            "search" => $search,
            "columnToSort" => $columnName,
            "sortOrder" => $columnSortOrder,
            "join" => [
                [
                    "table" => "carte_chance_article_jeux",
                    "foreignKeys" => [
                        "originColumn" => ["id" => "id",
                                           "table" => "carte_chance_article"
                                          ],
                        "targetColumn" => "article_id"
                    ]
                ],
                [
                    "table" => "carte_chance_jeux",
                    "foreignKeys" => [
                        "originColumn" => ["id" => "jeux_id",
                                           "table" => "carte_chance_article_jeux"
                                          ],
                        "targetColumn" => "id"
                    ]
                ],
                [
                    "table" => "carte_chance_category_article",
                    "foreignKeys" => [
                        "originColumn" => ["id" => "category_id",
                                           "table" => "carte_chance_article"
                                          ],
                        "targetColumn" => "id"
                    ]
                ],
                [
                    "table" => "carte_chance_category_jeux",
                    "foreignKeys" => [
                        "originColumn" => ["id" => "category_id",
                                           "table" => "carte_chance_jeux"
                                          ],
                        "targetColumn" => "id"
                    ]
                ]
            ]
        ]));
    }

    public function createArticleGame(){
        header('Content-Type: application/json');
        if($_SERVER['REQUEST_METHOD'] != "POST") {
            Errors::define(400, 'Bad HTTP request');
            echo json_encode(['success' => false]);
            exit;
        }


        if(!empty($_POST["createArticleGame-form-titleGame"]) && 
           !empty($_POST["createArticleGame-form-categoryGame"]) && 
           !empty($_FILES["createArticleGame-form-imageJeu"]) && 
           !empty($_POST["content"])){


            $categoryArticleGame = new Category_article();
            $categoryArticleGame = $categoryArticleGame->getOneWhere(["category_name" => "Jeux"]);
            if(is_bool($categoryArticleGame)){
                die("Erreur: la catégorie d'article Jeux n'existe pas");
            }

            $categoryArticleGameId = $categoryArticleGame->getId();
            $article = new ArticleModel();

            $whereSql = ["title" => $_POST['createArticleGame-form-titleGame']];
            $resultQueryExist = $article->existOrNot($whereSql);
            if(is_bool($resultQueryExist) || $resultQueryExist["column_exists"] == "none_exists"){
                $article->setTitle($_POST['createArticleGame-form-titleGame']);
                $article->setContent("pre content");
                $article->setCreatedDate(date("Y-m-d H:i:s"));
                $article->setUpdatedDate(date("Y-m-d H:i:s"));
                $article->setCategoryId($categoryArticleGameId);

                $responseQuery = $article->save();
                $idNewArticle = $responseQuery->idNewElement;

                if($responseQuery->success){
                    //article added in bdd
                    //add the game in bdd
                    $game = new Jeux();

                    $whereSql = ["title_game" => $_POST['createArticleGame-form-titleGame']];
                    $resultQueryExist = $game->existOrNot($whereSql);
                    if(is_bool($resultQueryExist) || $resultQueryExist["column_exists"] == "none_exists"){ 
                        //il n'y a aucun elements dans la table jeu qui contiens le meme titre 
                        $game->setTitle($_POST['createArticleGame-form-titleGame']);
                        $game->setCategory_id($_POST['createArticleGame-form-categoryGame']);
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
                               
                                //ici on ajoute dans la table content les paths des images 
                                //avant il faut parser le content pour trouver les balises img(base 64 ou url) et les remplacer par les paths des images
                                $originContent = $_POST['content'];
                                $pattern = '/<img[^>]+src="([^">]+)"/';
                                preg_match_all($pattern, $originContent, $matches);
                                
                                $srcImages = $matches[1];
                                $newContent = "";
                                foreach ($srcImages as $src) {
                                    if (strpos($src, 'data:image') === 0) { //verifie si c'est une image en base 64
                                        $fileData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $src));
                                        
                                        $extension = self::getBase64ImageExtension($src); // Obtenir l'extension à partir du base64
        
                                        $filename = uniqid() . '.' . $extension;
                                        
                                        $gameName = str_replace(" ", "_", $_POST['createArticleGame-form-titleGame']);
                                
                                        $arrayConfContent = [];
                                        $arrayConfContent['directory'] = "/var/www/html/uploads/articles/".$gameName."/";
                                        $arrayConfContent['location'] = $arrayConfContent['directory'].$filename;
                                        $arrayConfContent['fileName'] = $filename;
                                        $arrayConfContent['fileContent'] = $fileData;
                                        $arrayConfContent['fileExtension'] = strtolower(pathinfo($arrayConfContent['location'], PATHINFO_EXTENSION));
                                        $arrayConfContent['validExtensions'] = array("jpg","jpeg","png","svg");
                                        $arrayConfContent['joinTableClass'] = "Article_content";
                                        $arrayConfContent['joinTableId'] = $idNewArticle;
                                        $arrayConfContent['joinTableMethodToSetId'] = "setArticleId";
                                        $arrayConfContent['from$_FILES'] = false;

                                        $responseAddContent = AddFileContentFunction($arrayConfContent);
                                        if($responseAddContent->success){
                                            //image article ajouté
                                            //remplacer la balise img dans originContent
                                            $replaceSrc = "/uploads/articles/".$gameName."/".$filename; //on fait ca car avec le path entier ca ne marche pas dans l'htlm
                                            $newContent = str_replace($src, $replaceSrc , $originContent);
                                        }
                                        else{
                                            //image article non ajouté en bdd     
                                            echo json_encode(['success' => false, 'error' => $responseAddContent->message]);
                                        }
                                    }
                                }
                                //mettre a jour le content de l'article avec les paths des images qui vont bien 
                                $articleMaj = new ArticleModel();
                                $articleMaj->setId($idNewArticle);
                                $articleMaj->setContent($newContent);
                                if($articleMaj->save()){
                                    //content de l'article mis a jour

                                    //enfin ajouter la photo du jeu dans notre solution puis en base de donnée:                                 
                                    $filename = str_replace(" ", "_", $_FILES['createArticleGame-form-imageJeu']['name']);
                                    $gameName = str_replace(" ", "_", $_POST['createArticleGame-form-titleGame']);
                                    
                                    $arrayConfContent = [];
                                    $arrayConfContent['directory'] = "/var/www/html/uploads/jeux/".$gameName."/";
                                    $arrayConfContent['location'] = $arrayConfContent['directory'].$filename;
                                    $arrayConfContent['fileName'] = $filename;
                                    $arrayConfContent['fileContent'] = $_FILES['createArticleGame-form-imageJeu']['tmp_name'];
                                    $arrayConfContent['fileExtension'] = strtolower(pathinfo($arrayConfContent['location'],PATHINFO_EXTENSION));
                                    $arrayConfContent['validExtensions'] = array("jpg","jpeg","png","svg");
                                    $arrayConfContent['joinTableClass'] = "Jeux_content";
                                    $arrayConfContent['joinTableId'] = $idNewGame;
                                    $arrayConfContent['joinTableMethodToSetId'] = "setJeuId";
                                    $arrayConfContent['from$_FILES'] = true;
                                    
                                    $responseAddContent = AddFileContentFunction($arrayConfContent);
                                    if($responseAddContent->success){
                                        //image du jeu ajouter en bdd
                                        echo json_encode(['success' => true]);
                                    }
                                    else{
                                        //image non ajouté en bdd
                                        //"image game ".$filename." non ajouté en bdd";
                                        http_response_code(400);
                                        Errors::define(500, 'Internal Server Error');
                                        echo json_encode(['success' => false]);
                                    }

                                } else{
                                    //erreur lors de la mise a jour du content de l'article
                                    http_response_code(400);
                                    Errors::define(500, 'Internal Server Error');
                                    echo json_encode(['success' => false]);
                                }
                            }
                            else{
                                //error : add in jointable article_jeux ref in bdd
                                //"erreur sql : article_jeux ref non ajouté en bdd";
                                http_response_code(400);
                                Errors::define(500, 'Internal Server Error');
                                echo json_encode(['success' => false]);
                            }
                        }
                        else{
                            //error add game in bdd
                            //"erreur sql : jeu non ajouté en bdd";
                            http_response_code(400);
                            Errors::define(500, 'Internal Server Error');
                            echo json_encode(['success' => false]);
                        }    
                    }
                    else{
                        //le titre du jeu existe deja dans la bdd
                        http_response_code(400);
                        Errors::define(400, 'Invalid Info');
                        echo json_encode(['success' => false]);
                    }
                }
                else{
                    //erreur sql : article non ajouté en bdd
                    http_response_code(400);
                    Errors::define(500, 'Internal Server Error');
                    echo json_encode(['success' => false]);
                }
            }
            else{
                //article not added in bdd : title already used
                http_response_code(400);
                Errors::define(400, 'Invalid Info');
                echo json_encode(['success' => false]);
            }
        }
        else{
            //manque des informations dans les posts 
            http_response_code(400);
            Errors::define(400, 'Invalid Info');
            echo json_encode(['success' => false]);
        }
        
    }

    public function createArticleAboutGame(){
        header('Content-Type: application/json');
        if($_SERVER['REQUEST_METHOD'] != "POST") {
            Errors::define(400, 'Bad HTTP request');
            echo json_encode(['success' => false]);
            exit;
        }

        if(!empty($_POST["createArticleAboutGame-form-game"]) && 
           !empty($_POST["createArticleAboutGame-form-titleArticle"]) && 
           !empty($_POST["content"])){

            //ajouter l'article en base de donnée:
            $article = new ArticleModel();

            //get the category about game id:
            $categoryArticleAboutGame = new Category_article();
            $categoryArticleAboutGame = $categoryArticleAboutGame->getOneWhere(["category_name" => "Trucs et astuces"]);
            if(is_bool($categoryArticleAboutGame)){
                die("Erreur: la catégorie d'article trucs et astuces n'existe pas");
            }
            $categoryArticleAboutGameId = $categoryArticleAboutGame->getId();

            $whereSql = ["title" => $_POST['createArticleAboutGame-form-titleArticle']];
            $resultQueryExist = $article->existOrNot($whereSql);
            if(is_bool($resultQueryExist) || $resultQueryExist["column_exists"] == "none_exists"){ 
                //il n'y a aucun elements dans la table qui contiens le meme titre 
                $article->setTitle($_POST['createArticleAboutGame-form-titleArticle']);
                $article->setContent(" ");
                $article->setCreatedDate(date("Y-m-d H:i:s"));
                $article->setUpdatedDate(date("Y-m-d H:i:s"));
                $article->setCategoryId($categoryArticleAboutGameId);

                $responseQuery = $article->save();
                $idNewArticle = $responseQuery->idNewElement;
                if($responseQuery->success){ 
                    //article ajouté en bdd 
                    //enusite ajouter l'id de l'article et l'id du jeux dans la table de jointure entre article et jeux
                    $article_jeux = new Article_jeux();
                    $article_jeux->setArticleId($idNewArticle);
                    $article_jeux->setJeuxId($_POST['createArticleAboutGame-form-game']);
                    if($article_jeux->insertIntoJoinTable()){
                        //article_jeux ajouté en bdd
                        
                        //ici on ajoute dans la table content les paths des images de notre article
                        //avant il faut parser le content pour trouver les balises img(base 64 ou url) et les remplacer par les paths des images
                        $originContent = $_POST['content'];
                        $pattern = '/<img[^>]+src="([^">]+)"/';
                        preg_match_all($pattern, $originContent, $matches);
                        
                        $srcImages = $matches[1];
                        $newContent = "";
                        foreach ($srcImages as $src) {
                            if (strpos($src, 'data:image') === 0) { //verifie si c'est une image en base 64
                                $fileData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $src));
                                
                                $extension = self::getBase64ImageExtension($src); // Obtenir l'extension à partir du base64

                                $filename = uniqid() . '.' . $extension;
                                
                                $articleName = str_replace(" ", "_", $_POST['createArticleAboutGame-form-titleArticle']);
                        
                                $arrayConfContent = [];
                                $arrayConfContent['directory'] = "/var/www/html/uploads/articles/".$articleName."/";
                                $arrayConfContent['location'] = $arrayConfContent['directory'].$filename;
                                $arrayConfContent['fileName'] = $filename;
                                $arrayConfContent['fileContent'] = $fileData;
                                $arrayConfContent['fileExtension'] = strtolower(pathinfo($arrayConfContent['location'], PATHINFO_EXTENSION));
                                $arrayConfContent['validExtensions'] = array("jpg","jpeg","png","svg");
                                $arrayConfContent['joinTableClass'] = "Article_content";
                                $arrayConfContent['joinTableId'] = $idNewArticle;
                                $arrayConfContent['joinTableMethodToSetId'] = "setArticleId";
                                $arrayConfContent['from$_FILES'] = false;

                                $responseAddContent = AddFileContentFunction($arrayConfContent);
                                if($responseAddContent->success){
                                    //image article ajouté
                                    //remplacer la balise img dans originContent
                                    $replaceSrc = "/uploads/articles/".$articleName."/".$filename; //on fait ca car avec le path entier ca ne marche pas dans l'htlm
                                    $newContent = str_replace($src, $replaceSrc , $originContent);
                                }
                                else{
                                    //image article non ajouté en bdd     
                                    echo json_encode(['success' => false, 'error' => $responseAddContent->message]);
                                }
                            }
                        }
                        //mettre a jour le content de l'article avec les paths des images qui vont bien 
                        $articleMaj = new ArticleModel();
                        $articleMaj->setId($idNewArticle);
                        $articleMaj->setContent($newContent);
                        if($articleMaj->save()){
                            //content de l'article mis a jour
                            echo json_encode(['success' => true]);
                        } else{
                            //erreur lors de la mise a jour du content de l'article
                            http_response_code(400);
                            Errors::define(500, 'Internal Server Error');
                            echo json_encode(['success' => false]);
                        }
                    }
                    else{
                        //joinTable ref not added
                        //erreur sql : article_jeux ref non ajouté en bdd
                        http_response_code(400);
                        Errors::define(500, 'Internal Server Error');
                        echo json_encode(['success' => false]);
                    }
                }
                else{
                    //article not added in bdd 
                    //erreur sql : article non ajouté en bdd
                    http_response_code(400);
                    Errors::define(500, 'Internal Server Error');
                    echo json_encode(['success' => false]);
                }
            } else{
                // title is already used
                //Le titre de l'article existe déjà
                http_response_code(400);
                Errors::define(500, 'Internal Server Error');
                echo json_encode(['success' => false]);
            }
        } else{
            //manque des informations dans les posts 
            http_response_code(400);
            Errors::define(400, 'Invalid Info');
            echo json_encode(['success' => false]);
        }

    }

    public function updateArticle(){
        header('Content-Type: application/json');
        if($_SERVER['REQUEST_METHOD'] != "POST") {
            Errors::define(400, 'Bad HTTP request');
            echo json_encode("Bad Method");
            exit;
        }

        if(!empty($_POST["content"]) || !empty($_POST["editArticle-form-title"])){
            $article = new ArticleModel();
            $article->setId($_POST["id"]);
            if(!empty($_POST["content"])){
                //avant de set le content on doit parser le content pour trouver les balises img et les remplacer par les paths des images si il yen a de nouvelles:

                $originContent = $_POST['content'];
                $pattern = '/<img[^>]+src="([^">]+)"/';
                preg_match_all($pattern, $originContent, $matches);
                
                $srcImages = $matches[1];
                
                foreach ($srcImages as $src) {
                    if (strpos($src, 'data:image') === 0) { //verifie si c'est une image en base 64
                        $fileData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $src));
                        
                        $extension = self::getBase64ImageExtension($src); // Obtenir l'extension à partir du base64

                        $filename = uniqid() . '.' . $extension;
                        
                        $gameName = str_replace(" ", "_", $_POST['title_game']);
                
                        $arrayConfContent = [];
                        $arrayConfContent['directory'] = "/var/www/html/uploads/articles/".$gameName."/";
                        $arrayConfContent['location'] = $arrayConfContent['directory'].$filename;
                        $arrayConfContent['fileName'] = $filename;
                        $arrayConfContent['fileContent'] = $fileData;
                        $arrayConfContent['fileExtension'] = strtolower(pathinfo($arrayConfContent['location'], PATHINFO_EXTENSION));
                        $arrayConfContent['validExtensions'] = array("jpg","jpeg","png","svg");
                        $arrayConfContent['joinTableClass'] = "Article_content";
                        $arrayConfContent['joinTableId'] = $_POST["id"];
                        $arrayConfContent['joinTableMethodToSetId'] = "setArticleId";
                        $arrayConfContent['from$_FILES'] = false;

                        $responseAddContent = AddFileContentFunction($arrayConfContent);
                        if($responseAddContent->success){
                            //image article ajouté
                            //remplacer la balise img dans originContent
                            $replaceSrc = "/uploads/articles/".$gameName."/".$filename; //on fait ca car avec le path entier ca ne marche pas dans l'htlm
                            $newContent = str_replace($src, $replaceSrc , $originContent);
                        }
                        else{
                            //image article non ajouté en bdd     
                            echo json_encode(['success' => false, 'error' => $responseAddContent->message]);
                        }
                    }
                }


                $article->setContent($newContent);
            }
            if(!empty($_POST["editArticle-form-title"])){
                $whereSql = ["title" => $_POST['editArticle-form-title']];
                $resultQueryExist = $article->existOrNot($whereSql);
                if(is_bool($resultQueryExist) || $resultQueryExist["column_exists"] == "none_exists"){
                    $article->setTitle($_POST["editArticle-form-title"]);
                } else{
                    // title is already used
                    //Le titre de l'article existe déjà
                    http_response_code(400);
                    Errors::define(500, 'Internal Server Error');
                    echo json_encode(['success' => false]);
                }
            } 
            $article->setUpdatedDate(date("Y-m-d H:i:s"));
            
            if($article->save()->success){
                echo json_encode(['success' => true]);
            } else{
                //erreur sql : article non ajouté en bdd
                http_response_code(400);
                Errors::define(500, 'Internal Server Error');
                echo json_encode(['success' => false]);
            }
            

        } else{
            //manque des informations dans les posts 
            http_response_code(400);
            Errors::define(400, 'Invalid Info');
            echo json_encode(['success' => false]);
        }
    }

    public function deleteArticle(){
        header('Content-Type: application/json');
        if(empty($_SERVER['REQUEST_METHOD']) || $_SERVER['REQUEST_METHOD'] != "DELETE") {
            Errors::define(400, 'Bad HTTP request');
            echo json_encode("Bad Method");
            exit;
        }
        $delete = getHttpMethodVarContent();
        if(empty($delete['id']) && empty($delete['categoryArticle'])) {
            Errors::define(400, 'Bad Request');
            echo json_encode("Bad Request");
            exit;
        }

        $article = new ArticleModel();
        $article->setId($delete['id']);
        //avant de supprimé l'article il faut recuperer les id des contents et des commentaires 
        //id des commentaires lié a l'article
        $comment_article = new Comment_article();
        $comment_articles = $comment_article->getAllWhere(["article_id" => $delete['id']]);
        $ids_comments = [];
        foreach($comment_articles as $comment_article){
            array_push($ids_comments, $comment_article->getCommentId());
        }

        //id des contents lié a l'article
        $article_content = new Article_content();
        $article_contents = $article_content->getAllWhere(["article_id" => $delete['id']]);
        $ids_contents = [];
        foreach($article_contents as $article_content){
            array_push($ids_contents, $article_content->getContentId());
        }
        
        //si l'article est de type jeu il faut supprimé le jeu et le contenu du jeu(son image) ainsi que les articles de type about game qui sont lié a cette article
        // if($delete['categoryArticle'] == "jeu"){
        //     $article_jeux = new Article_jeux();
        //     $article_jeux = $article_jeux->getOneWhere(["article_id" => $delete['id']]);
        //     $jeux_id = $article_jeux->getJeuxId(); //to delete 
        //     $article_jeux = $article_jeux->getAllWhere(["jeux_id" => $jeux_id]);
        //     $ids_articles = []; //to delete 
        //     foreach($article_jeux as $article_jeu){
        //         array_push($ids_articles, $article_jeu->getArticleId());
        //     }
        //     $jeux_content = new Jeux_content();
        //     $jeux_content = $jeux_content->getOneWhere(["jeux_id" => $jeux_id]);
        //     $content_id = $jeux_content->getContentId(); //to delete
        // }

        if($article->delete()) {
            //article supprimé en bdd  
            //supprimé les commentaires liée a l'article
            if(!empty($ids_comments)){
                $comment = new Comment();
                $resDeleteComments = $comment->multipleDelete("id", $ids_comments);
            }
            else{
                $resDeleteComments = true;
            }
            
            //supprimé les contents liée a l'article
            if(!empty($ids_contents)){
                $content = new Content();
                $resDeleteContents  = $content->multipleDelete("id", $ids_contents);
            }
            else{
                $resDeleteContents = true;
            }


            if($resDeleteComments && $resDeleteContents){
                //commentaires et contents supprimé en bdd
                echo json_encode(['success' => true]);
            }
            else{
                //erreur sql : commentaires ou contents non supprimé en bdd
                http_response_code(400);
                Errors::define(500, 'Internal Server Error');
                echo json_encode(['success' => false]);
            }
        }
        else {
            Errors::define(500, 'Internal Server Error');
            echo json_encode("Internal Server Error");
        }
        exit();
    }



    public static function getBase64ImageExtension($base64Image) {
        $imageInfo = explode(';', $base64Image);
        $imageType = explode('/', $imageInfo[0]);
        $extension = end($imageType);
        return $extension;
    }
    
}