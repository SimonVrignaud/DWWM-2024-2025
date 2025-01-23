<?php 
require __DIR__."/../model/messageModel.php";
require __DIR__."/../../ressources/services/_shouldBeLogged.php";
require __DIR__."/../../ressources/services/_csrf.php";

/**
 * Gère la page de blog
 *
 * @return void
 */
function readMessage()
{
    if(empty($_GET["id"]))
    {
        header("Location: /05-mvc/");
        exit;
    }

    $messages = getMessagesByUser($_GET["id"]);

    $logged = isset($_SESSION["idUser"]) && $_GET["id"] == $_SESSION["idUser"];
    require __DIR__."/../view/message/list.php";
}
/**
 * Gère la page d'ajout de message
 *
 * @return void
 */
function createMessage()
{
    shouldBeLogged(true, "/05-mvc/connexion");

    if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['message']))
    {
        if(empty($_POST["message"]))
        {
            $_SESSION["flash"] = "Veuillez entrer un message";
        }else
        {
            $message = cleanData($_POST["message"]);
            addMessage(["id"=>$_SESSION["idUser"], "m"=>$message]);
            $_SESSION["flash"] = "Message envoyé";
        }
    }
    header("Location: /05-mvc/message/list?id=".$_SESSION["idUser"]);
    exit;
}
/**
 * Gère la page de mis à jour des messages
 *
 * @return void
 */
function updateMessage()
{
    shouldBeLogged(true, "/05-mvc/connexion");

    if(empty($_GET["id"]))goToListe();

    $message = getMessageById($_GET["id"]);

    if(!$message || $message["idUser"] != $_SESSION["idUser"])goToListe();

    if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['message']))
    {
        if(empty($_POST["message"]))
        {
            $content = $message["message"];
        }else
        {
            $content = cleanData($_POST["message"]);
        }
        updateMessageById($_GET["id"], $content);
        $_SESSION["flash"] = "Message Modifié";
        goToListe();
    }
    require __DIR__."/../view/message/update.php";
}
/**
 * Gère la page de supression des messages
 *
 * @return void
 */
function deleteMessage()
{
    shouldBeLogged(true, "/05-mvc/connexion");

    if(empty($_GET["id"]))goToListe();

    $message = getMessageById($_GET["id"]);

    if(!$message || $message["idUser"] != $_SESSION["idUser"])goToListe();

    deleteMessageById($_GET["id"]);

    $_SESSION["flash"] = "Message Supprimé avec succès";
    goToListe();
}
/**
 * Redirige vers le blog de l'utilisateur connecté
 *
 * @return void
 */
function goToListe()
{
    header("Location: /05-mvc/message/list?id=".$_SESSION["idUser"]);
    exit;
}