<?php 

require "../../../ressources/services/_shouldBeLogged.php";
require "../../../ressources/services/_csrf.php";
require "../../../ressources/services/_pdo.php";

shouldBeLogged(true, "../formateur/connexion.php");

if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['message']))
{
    if(empty($_POST["message"]))
    {
        $_SESSION["flash"] = "Veuillez entrer un message";
    }else
    {
        $message = cleanData($_POST["message"]);
        $pdo = connexionPDO();

        $sql = $pdo->prepare("INSERT INTO messages(message, idUser) VALUES(?, ?)");
        $sql->execute([$message, $_SESSION["idUser"]]);
        // $sql->prepare("INSERT INTO message(message, idUser) VALUES(:m, :id)");
        // $sql->execute(["id"=>$_SESSION["idUser"], "m"=>$message]);
        $_SESSION["flash"] = "Message envoyé";
    }
}
header("Location: ./read.php?id=".$_SESSION["idUser"]);
exit;