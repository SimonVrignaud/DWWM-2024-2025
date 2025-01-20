<?php 
    require "../../../ressources/services/_shouldBeLogged.php";
    require "../../../ressources/services/_pdo.php";
    require "../../../ressources/services/_csrf.php";

    shouldBeLogged(true, "../formateur/connexion.php");

    if(empty($_GET["id"]))goToListe();

    $pdo = connexionPDO();

    $sql = $pdo->prepare("SELECT * FROM messages WHERE idMessage = :id");
    // $sql->bindParam(":id", $_GET["id"]);
    // $sql->execute();
    $sql->execute(["id"=>$_GET["id"]]);
    $message = $sql->fetch();

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

        $sql = $pdo->prepare("UPDATE messages SET message = :message, editedAt = current_timestamp() WHERE idMessage = :id");
        $sql->bindParam(":message", $content);
        $sql->bindParam(":id", $_GET["id"]);
        $sql->execute();
        $_SESSION["flash"] = "Message Modifié";
        goToListe();
    }


    function goToListe()
    {
        header("Location: ./read.php?id=".$_SESSION["idUser"]);
        exit;
    }
    $title = "Mis à jour de message";
    require "../../../ressources/template/_header.php";
?>
<form action="" method="post">
    <label for="message">Message</label>
    <textarea name="message" id="message"><?= $message["message"] ?></textarea>
    <input type="submit" value="Envoyer">
</form>
<?php 
    require "../../../ressources/template/_footer.php";?>