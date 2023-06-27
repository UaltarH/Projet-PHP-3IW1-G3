<?php
namespace App\Services\TestUserIsConnected;
use App\Models\User;

class Response {
    public bool $success;
    public string $message;
    public User $userResult;
}

function UserExistForAccess($pseudo): Response {
    $response = new Response();
    $user = new User();
    $whereSql = ["pseudo" => $pseudo];
    $resultQuery = $user->getOneWhere($whereSql);
    if(!is_bool($resultQuery)) { //si le resultat de getOneWhere est un bool ca veut dire qu'il na pas trouver l'utilisateur 
        $response->userResult = $resultQuery;
        if($resultQuery->getEmailConfirmation() == false) { //tester si l'email est confirmé 
            $response->message = 'Vous etes pas connecter avec un compte confirmé, vous allez etre rediriger vers la page de connexion.';
            $response->success = true;
            return $response;
        } 
    } else {
        $response->userResult = new User();
        $response->message = 'Vous etes pas connecter avec un utilisateur correct, vous allez etre rediriger vers la page de connexion.';
        $response->success = true;
        return $response;
    }
    $response->userResult = new User();
    $response->success = true;
    $response->message = '';
    return $response;
}