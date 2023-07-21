<?php
namespace App\Core\TokenJwt;

function generateJWT($payload) {
    // Clé secrète utilisée pour signer le JWT TODO : le mettre dans le fichier de config
    $secret = 'ma_cle_secrete_pastrop_secret_en_vrai'; 

    // Encodage de l'en-tête (typiquement "JWT") et de l'algorithme de signature (typiquement "HS256")
    $header = base64url_encode(json_encode(array(
        'alg' => 'HS256',
        'typ' => 'JWT'
    )));

    // Encodage des revendications (payload) en JSON
    $claims = base64url_encode(json_encode($payload));

    // Concaténation de l'en-tête, des revendications et de la signature avec des points
    $signature = hash_hmac('sha256', $header . '.' . $claims, $secret);
    $token = $header . '.' . $claims . '.' . $signature;

    return $token;
}

function validateJWT($token) {
    // Clé secrète utilisée pour signer le JWT TODO : le mettre dans le fichier de config
    $secret = 'ma_cle_secrete_pastrop_secret_en_vrai'; 

    $tokenParts = explode('.', $token);
    $header = base64_decode($tokenParts[0]);
    $payload = base64_decode($tokenParts[1]);
    $signature = $tokenParts[2];

    // Vérifier la signature
    $expectedSignature = hash_hmac('sha256', $tokenParts[0] . '.' . $tokenParts[1], $secret);
    
    if ($signature !== $expectedSignature) {
        // La signature n'est pas valide
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }
        
        return false;
    }

    // Vérifier l'expiration du token
    $payloadData = json_decode($payload, true);
    if (isset($payloadData['exp']) && $payloadData['exp'] < time()) {
        // Le token est expiré
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }
        return false;
    }

    // Le token est valide
    return true;
}

function getSpecificDataFromToken($token, $keyData):string 
{
    $tokenParts = explode('.', $token);
    $payload = base64_decode($tokenParts[1]);
    $payloadData = json_decode($payload, true);

    // Récupérer le nom depuis la charge utile
    $data = isset($payloadData[$keyData]) ? $payloadData[$keyData] : '';

    return $data;
}

function getAllInformationsFromToken($token):array
{
    $tokenParts = explode('.', $token);
    $payload = base64_decode($tokenParts[1]);
    $payloadData = json_decode($payload, true);

    return $payloadData;
}


// Fonction pour encoder une chaîne en base64url
function base64url_encode($data) {
    $base64 = base64_encode($data);
    $base64url = str_replace(['+', '/', '='], ['-', '_', ''], $base64);
    return $base64url;
}