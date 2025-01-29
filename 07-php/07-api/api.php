<?php 
// Limite l'accès à certaines sources (* pour aucune limite)
// header("Access-Control-Allow-Origin: http://localhost");
header("Access-Control-Allow-Origin: *");

// format des données envoyées
header("Content-Type: application/json; charset=UTF-8");

// on indique la possibilité d'échanger des identifiants.
header("Access-Control-Allow-Credentials: true");

// Durée de vie de la requête
header("Access-Control-Max-Age: 3600");

// Entête authorisées par la requête reçu.
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

/**
 * Envoi une réponse au format JSON
 *
 * @param array $data
 * @param integer $status
 * @param string $message
 * @return void
 */
function sendResponse(array $data, int $status, string $message): void
{
    http_response_code($status);
    echo json_encode([
        'data' => $data, 
        'message' => $message,
        'status'=>$status
    ]);
    exit;
}
/**
 * Sauvegarde des erreurs ou retourne le tableau d'erreur
 *
 * @param boolean $property
 * @param boolean $message
 * @return void
 */
function setError($property = false, $message = false)
{
    static $error = [];
    if(!$property || !$message)return $error;
    $error[]=[
        'property' => $property,
        'message' => $message
    ];
}