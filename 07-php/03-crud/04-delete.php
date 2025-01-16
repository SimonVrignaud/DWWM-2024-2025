<?php
require "../ressources/services/_shouldBeLogged.php";
shouldBeLogged(true, "./exercice/formateur/connexion.php");

/* 
    On a vérifié que l'utilisateur est connecté.
    Puis on vérifie que l'utilisateur qu'on tente de supprimer, est bien l'utilisateur connecté.
*/
if(empty($_GET["id"]) || $_SESSION["idUser"] != $_GET["id"])
{
    $_SESSION["flash"] = "Accès Interdit !";
    header("Location: ./02-read.php");
    exit;
}

// On supprime l'utilisateur
require "../ressources/services/_pdo.php";
$pdo = connexionPDO();
$sql = $pdo->prepare("DELETE FROM users WHERE idUser = ?");
$sql->execute([(int)$_GET["id"]]);

// Je déconnecte mon utilisateur.
unset($_SESSION);
session_destroy();
setcookie("PHPSESSID","", time()-3600);

// Je redirige mon utilisateur après quelques secondes
header("refresh: 5; url=/");

$title = " CRUD - Delete ";
require("../ressources/template/_header.php");
?>
<p>
    Vous avez bien <strong>supprimé</strong> votre compte. <br>
    Vous allez être redirigé d'ici peu.
</p>
<?php
require("../ressources/template/_footer.php");
?>