<?php
/* 
    créons dans nos ressources un nouveau dossier "service" et un fichier _shouldBeLogged.php
    Celui nous servira à vérifier si l'utilisateur est connecté, et sinon à le rediriger ailleurs.
*/
require("../ressources/service/_shouldBeLogged.php");
// * session_start() est appelé dans notre fichier _shouldBeLogged.php, donc pas besoin de l'appeler encore.
/* 
    On détruit notre variable de session, 
    on détruit à la session,
    on détruit le cookie.

    (si vous utiliser votre session pour autre chose que les informations de connexion, 
    il faudra être un peu plus précis, ne pas détruire la session et le cookie et 
    seulement "unset" les informations lié à notre connexion 
    (ici les clefs "username", "email", "logged" et "expire"))
*/
unset($_SESSION);
session_destroy();
setcookie("PHPSESSID","", time()-3600);
// puis on redirige notre utilisateur vers la page de connexion
header("location: ./04-connexion.php");
exit;
?>