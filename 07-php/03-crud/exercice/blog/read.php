<?php
session_start();
// var_dump(isset($_GET["id"]), !empty(($_GET["id"]))); 
if(empty($_GET["id"]))
{
    header("Location: ../../02-read.php");
    exit;
}

require "../../../ressources/services/_pdo.php";
$pdo = connexionPDO();

$sql = $pdo->prepare("SELECT * FROM messages WHERE idUser = ? ORDER BY createdAt DESC");
$sql->execute([$_GET["id"]]);
$messages = $sql->fetchAll();
// var_dump($messages);
// echo '<pre>'.print_r($messages, 1).'</pre>';
// echo $messages[0]->idUser;

$logged = isset($_SESSION["idUser"]) && $_GET["id"] == $_SESSION["idUser"];
// var_dump($logged);

$title = "Blog";
require "../../../ressources/template/_header.php";
?>
<?php if($logged):?>
    <form action="./create.php" method="post">
        <textarea name="message" placeholder="Nouveau message"></textarea>
        <button type="submit">Envoyer</button>
    </form>
<?php endif; ?>
<!-- affichage des messages -->
<?php if($messages):
    foreach($messages as $m): ?>
    <div class="message">
        <!-- Dates de créations et d'éditions -->
        <div class="date1">Ajouté le <?= $m["createdAt"] ?></div>
        <div class="date2"><?= $m["editedAt"]?"édité le ". $m["editedAt"]: "" ?></div>
        <!-- Messages -->
        <p><?= $m["message"] ?></p>
        <!-- boutons -->
        <div class="btns">
            <?php if($logged):?>
                <a href="./update.php?id=<?= $m["idMessage"] ?>">éditer</a>
                <a href="./delete.php?id=<?= $m["idMessage"] ?>">supprimer</a>
            <?php endif;?>
        </div>
    </div>
<?php endforeach;
else:?>
<p>Aucun messages trouvés</p>
<?php endif;?>

<?php 

require "../../../ressources/template/_footer.php";
?>