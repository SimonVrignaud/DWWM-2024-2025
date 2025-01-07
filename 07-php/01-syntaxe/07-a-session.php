<?php 

// Pour commencer à utiliser les sessions, il faudra avant tout lancer la fonction suivante.
session_start();
/* 
Elle commence une nouvelle session ou en récupère une existante si un ID a été trouvé dans les cookies
de l'utilisateur.
Après son activation on pourra d'ailleurs voir un cookie apparaître si on regarde le contenu de notre 
inspecteur de stockage.
On aura le temps de reparler des cookies, mais il faut savoir que les cookies sont des informations qui 
sont transféré à chaque échange avec le serveur.
Le navigateur envoi les cookies au serveur et le serveur lui renvoi.

On pourra récupérer l'id de session si on le souhaite via la superglobal $_COOKIE ou via la fonction
session_id(); 
Sont petit nom sera généralement "PHPSESSID"
*/
var_dump($_COOKIE, session_id());

/* 
Pour stocker ou récupérer des informations en session, on peut tout simplement utiliser la superglobal
$_SESSION tel un tableau associatif classique.
à noter qu'elle n'est évidement accessible uniquement si session_start() a été lancé.
*/
$_SESSION["food"] = "Pizza";
$_SESSION["age"] = "54";
$_SESSION["username"] = "Maurice";
// Allons maintenant à la page 2.
$title = " Session page 1";
require("../ressources/template/_header.php");
?>
<hr>
<a href="./07-b-session.php">Page 2</a>
<?php 
require_once("../ressources/template/_footer.php");
?>

