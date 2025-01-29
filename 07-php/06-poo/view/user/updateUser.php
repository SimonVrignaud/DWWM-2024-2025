<?php 
if($user):

    require __DIR__."/_userForm.php";
else: ?>
    <p>Aucun Utilisateur trouvé</p>
<?php 
endif;
?>