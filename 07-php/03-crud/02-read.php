<?php 
$user = null;

$title = " CRUD - Read ";
require("../ressources/template/_header.php");
// Si on a trouvé des utilisateurs on affiche un tableau
// TODO flash message
?>

<h3>Liste des utilisateurs</h3>

<?php if($users): ?>
<table>
    <thead>
        <tr>
            <th>id</th>
            <th>username</th>
            <th>action</th>
        </tr>
    </thead>
    <!-- Pour chacun des utilisateurs trouvé, on ajoute une ligne -->
    <!-- todo foreach -->
</table>
<!-- Sinon on affiche un message -->
<?php else: ?>
    <p>Aucun utilisateur trouvé</p>
<?php 
endif;
require("../ressources/template/_footer.php");
?>