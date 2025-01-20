<?php 
    require "../../../ressources/services/_shouldBeLogged.php";
    require "../../../ressources/services/_pdo.php";

    shouldBeLogged(true, "../formateur/connexion.php");

    if(empty($_GET["id"]))goToListe();

    $pdo = connexionPDO();

    $sql = $pdo->prepare("SELECT * FROM messages WHERE idMessage = :id");
    $sql->execute(["id"=>$_GET["id"]]);
    $message = $sql->fetch();

    if(!$message || $message["idUser"] != $_SESSION["idUser"])goToListe();

    $sql = $pdo->prepare("DELETE FROM messages WHERE idMessage = :id");
    $sql->execute(["id"=>$_GET["id"]]);

    $_SESSION["flash"] = "Message Supprimé avec succès";
    goToListe();

    function goToListe()
    {
        header("Location: ./read.php?id=".$_SESSION["idUser"]);
        exit;
    }