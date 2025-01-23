<?php 

$title = "Blog";
require __DIR__."/../../../ressources/template/_header.php";
?>
<?php if($logged):?>
    <form action="/05-mvc/message/create" method="post">
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
                <a href="/05-mvc/message/update?id=<?= $m["idMessage"] ?>">éditer</a>
                <a href="/05-mvc/message/delete?id=<?= $m["idMessage"] ?>">supprimer</a>
            <?php endif;?>
        </div>
    </div>
<?php endforeach;
else:?>
<p>Aucun messages trouvés</p>
<?php endif;?>

<?php 

require __DIR__."/../../../ressources/template/_footer.php";
?>