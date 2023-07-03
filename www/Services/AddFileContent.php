<?php
namespace App\Services\AddFileContent;
use App\Models\Content;
use App\Core\ResponseGeneral;

//this function will have a array parameter with informations : 
    // directory
    // location
    // file name
    // file content
    // file extension
    // valid_extensions
    // joinTable class
    // joinTable id (for use: id_article or id_jeux)
// ex param:
// $arrayConfContent['directory'] = "/var/www/html/uploads/articles/".$articleName."/";
// $arrayConfContent['location'] = $arrayConfContent['directory'].$filename;
// $arrayConfContent['fileName'] = $filename;
// $arrayConfContent['fileContent'] = $_FILES['imagesArticle']['tmp_name'][$i];
// $arrayConfContent['fileExtension'] = strtolower(pathinfo($arrayConfContent['location'],PATHINFO_EXTENSION));
// $arrayConfContent['validExtensions'] = array("jpg","jpeg","png","svg");
// $arrayConfContent['joinTableClass'] = "Article_content";
// $arrayConfContent['joinTableId'] = $idNewArticle;
// $arrayConfContent['joinTableMethodToSetId'] = "setArticleId";
// And return a responseGeneral object with success and message


function AddFileContentFunction(array $InformationContent): ResponseGeneral
{
    $response = new ResponseGeneral();
    if(in_array($InformationContent['fileExtension'], $InformationContent['validExtensions'])) {
        // Create directory if it doesn't exist
        if(!is_dir($InformationContent['directory'])) {
            mkdir($InformationContent['directory'], 0777, true);
        }
        // Upload file
        if(move_uploaded_file($InformationContent['fileContent'],$InformationContent['location'])){
            // add content in content table
            $content = new Content();
            $content->setPathContent($InformationContent['location']);
            $resultQuery = $content->save();
            if($resultQuery->success){
                //content added 
                $class = "App\\Models\\JoinTable\\".$InformationContent['joinTableClass'];
                $method = $InformationContent['joinTableMethodToSetId'];
                $joinTable_content = new $class();
                $joinTable_content->$method($InformationContent['joinTableId']);
                $joinTable_content->setContentId($resultQuery->idNewElement);
                if($joinTable_content->insertIntoJoinTable()){
                    //ref added in joinTable
                    $response->success = true;
                    $response->message = "tout est ok";  
                }
                else{
                    //ref not added in joinTable
                    $response->success = false;
                    $response->message = "content added in solution and in content table but not added in join table"; 
                }
            }
            else{
                //content not added
                $response->success = false;
                $response->message = "content not added";
            }
        }
        else{
            $response->success = false;
            $response->message = "file name : ".$InformationContent['fileName']." not added";
        }
    }
    else {
        $response->success = false;
        $response->message = "extension non valide";
    }
    return $response;
}
