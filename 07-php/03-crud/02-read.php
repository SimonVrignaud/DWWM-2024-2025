<?php 
require "../ressources/services/_pdo.php";
$pdo = connexionPDO();

$sql = $pdo->query("SELECT idUser, username FROM users");

$users = $sql->fetchAll();
// var_dump($users);
// $users = null;

$title = " CRUD - Read ";
require("../ressources/template/_header.php");
// Si on a trouvé des utilisateurs on affiche un tableau

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
    <tbody>
        <?php foreach($users as $user): ?>
            <tr>
                <td><?= $user['idUser'] ?></td>
                <td><?= $user['username'] ?></td>
                <td>
                    <!-- Est ce que l'id de l'utilisateur connecté existe et est-il le même que celui de la rangée -->
                    <?php if(isset($_SESSION["idUser"]) && $_SESSION["idUser"] == $user["idUser"]):?>
                        <a href="03-update.php?id=<?= $user['idUser'] ?>">Modifier</a> |
                        <a href="04-delete.php?id=<?= $user['idUser'] ?>">Supprimer</a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<!-- Sinon on affiche un message -->
<?php else: ?>
    <p>Aucun utilisateur trouvé</p>
<?php 
endif;
require("../ressources/template/_footer.php");
?>