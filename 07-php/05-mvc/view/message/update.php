<?php
$title = "Mis à jour de message";
require __DIR__."/../../../ressources/template/_header.php";
?>
<form action="" method="post">
<label for="message">Message</label>
<textarea name="message" id="message"><?= $message["message"] ?></textarea>
<input type="submit" value="Envoyer">
</form>
<?php 
require __DIR__."/../../../ressources/template/_footer.php";?>