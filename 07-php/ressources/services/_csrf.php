<?php 
if(session_status() === PHP_SESSION_NONE)
{
    session_start();
}
/**
 * Paramètre un token en session et ajoute un input:hidden contenant le token
 * 
 * Optionnellement ajoute un temps de vie au jeton
 *
 * @param integer $time temps en minute
 * @return void
 */
function setCSRF(int $time = 0): void
{
    if($time > 0)
    {
        $_SESSION["tokenExpire"] = time()+60*$time;
    }

    $_SESSION["token"] = bin2hex(random_bytes(50));

    echo '<input type="hidden" name="token" value="'.$_SESSION["token"] .'">';
}
/**
 * Vérifie si le jeton est valide.
 *
 * @return boolean
 */
function isCSRFValid(): bool
{
    // Si il n'y a pas de temps d'expiration, ou si celui-ci est toujours valide
    if(!isset($_SESSION["tokenExpire"]) || $_SESSION["tokenExpire"]>time())
    {
        // Si nous avons un jeton en session et en POST et qu'ils sont égaux.
        if(isset($_SESSION["token"], $_POST["token"]) && $_SESSION["token"] === $_POST["token"])
        {
            return true;
        }
    }
    http_response_code(405);
    return false;
}
/**
 * Filtre les données pour éviter toute attaque XSS
 *
 * @param string $data
 * @return string
 */
function cleanData(string $data): string
{
    $data = trim($data);
    $data = stripslashes($data);
    return htmlspecialchars($data);
}
?>