<?php 
/* si on a besoin de la session que sur de rare pages, autant ne pas l'activer pour rien. 
Mais si on l'utilise sur toute les pages de notre site, on a la possibilité de l'ouvrir dans un fichier 
header inclu dans toute nos pages.

Si jamais on ne l'inclu pas sur toute nos pages, mais seulement dans certaines et qu'en plus on fait 
quelques inclusions, c'est une bonne pratique avant de faire le session_start() de vérifier
si la session n'est pas déjà démarrée.
Pour cela on utilisera session_status() qui nous rendra un int que l'on retrouvera 
dans les constantes suivantes :
    PHP_SESSION_NONE (il n'y a pas de session)
    PHP_SESSION_DISABLED (Les sessions sont désactivé)
    PHP_SESSION_ACTIVE (il y a une session démarrée)

Généralement la session prend fin quand le navigateur est fermé, mais si on souhaite la faire perdurer
plus longtemps, on peut lui passer une durée de vie en option :
*/
if(session_status() === PHP_SESSION_NONE)
session_start([
    // la durée de vie du cookie en seconde, par défaut 0;
    'cookie_lifetime' => 30
]);
$title = " Session page 2";
require("../ressources/template/_header.php");
var_dump($_SESSION);
echo "<br>";
/* attention cette durée de vie n'est pas extrèmement précise, le navigateur ne vérifie pas à chaque
instant la durée de vie des cookies.*/
// echo $_SESSION["username"] . " aime la ". $_SESSION["food"] . " et a " .$_SESSION["age"] . " ans <br>";
/*
    Quand on utilise des variables qui peuvent ne pas exister
    il est plus propre d'utiliser une condition pour vérifier
    leur existence avant de tenter de les appeler.
    isset() prend une à plusieurs variables et répond 
    true si elles existent toute. false dans le cas contraire.
*/
if(isset($_SESSION["username"], $_SESSION["food"],$_SESSION["age"])){
    echo $_SESSION["username"] . " aime la "
        .$_SESSION["food"] . " et a " 
        .$_SESSION["age"] . " ans <br>";
}
/* 
    Mais parfois on souhaite supprimer nous même une session ou des informations qu'elle contient.
    C'est un tableau associatif classique, il nous suffira alors de unset pour supprimer une donnée.
*/
unset($_SESSION["food"]);
// pour supprimer la session entière. 
session_destroy();
// cela dit si au rechargement il n'y aura certes plus rien. actuellement $_SESSION est toujours actif.
var_dump($_SESSION);
// il est donc bien vu de supprimer par la même occassion la totalité de $_SESSION.
unset($_SESSION);
// ce var_dump retourne une erreur car il n'y a plus de variable $_SESSION.
// var_dump($_SESSION);
// un dernier point est que notre cookie traine toujours sur l'ordinateur de l'utilisateur :
echo "<br>";
var_dump($_COOKIE);
/* On rentrera dans le détail des cookies plus tard mais pour supprimer un cookie, il suffit de lui donner
une durée de vie négative. ici time() récupère le timestamp actuel que l'on diminu de une heure.*/
setcookie("PHPSESSID","", time()-3600);
// Mais la plupart du temps on se contentera de supprimer la valeur dont on a plus besoin dans la session.

// Il est possible de créer plusieurs session différentes en changeant leur nom:
session_name("USERSESSION");
session_start();
$_SESSION["legume"] = "carotte";
// regardons à nouveau les cookies et nous verrons un second cookie avec un nouvel identifiant.
/* il est rare d'utiliser 2 sessions sur un même projet, renommer une session sera plus utilisé 
si on a plusieurs projets tournant sur un même HOST, pour les différenciers. */
?>
<hr>
<a href="./07-a-session.php">Page 1</a>
<?php 
require_once("../ressources/template/_footer.php");
?>

