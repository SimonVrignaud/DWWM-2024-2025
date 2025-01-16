<?php 
require "../ressources/services/_shouldBeLogged.php";
shouldBeLogged(true, "./exercice/formateur/connexion.php");

/* 
    On a vérifié que l'utilisateur est connecté.
    Puis on vérifie que l'utilisateur qu'on tente de supprimer, est bien l'utilisateur connecté.
*/
if(empty($_GET["id"]) || $_SESSION["idUser"] != $_GET["id"])
{
    $_SESSION["flash"] = "Accès Interdit !";
    header("Location: ./02-read.php");
    exit;
}

require "../ressources/services/_csrf.php";
require "../ressources/services/_pdo.php";

// Je récupère mon utilisateur
$pdo = connexionPDO();
$sql = $pdo->prepare("SELECT * FROM users WHERE idUser = :id");
$sql->bindParam(":id", $_GET["id"]);
$sql->execute();
$user = $sql->fetch();

$username = $password = $email = "";
$error = [];
$regexPass = "/^(?=.*[!?@#$%^&*.,+-])(?=.*[0-9])(?=.*[A-Z])(?=.*[a-z]).{6,}$/";

if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['update']))
{
    // Vérification username
    if(empty($_POST["username"]))
    {
        $username = $user["username"];
    }
    else
    {
        $username = cleanData($_POST["username"]);
        /*
            En PHP on utilisera "preg_match" pour tester si un string 
            correspond à une "REGEX"
        */
        if(!preg_match("/^[a-zA-Z' -]{2,25}$/", $username))
        {
            $error["username"] = "Veuillez saisir un nom d'utilisateur valide";
        }
    }
    //vérification email
    if(empty($_POST["email"]))
    {
        $email = $user["email"];
    }
    else
    {
        $email = cleanData($_POST["email"]);
        if(!filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            $error["email"] = "Veuillez saisir une adresse email valide";
        }
        // je vérifie si l'utilisateur veut changer d'email. (si c'est le même, pas besoin de vérifier qu'il existe déjà)
        if($email != $user["email"])
        {
            $sql = $pdo->prepare("SELECT * FROM users WHERE email=:em");
            
            $sql->bindParam(':em', $email);
            $sql->execute();
            
            $resultat = $sql->fetch();
            if($resultat)
            {
                $error["email"] = "Cette adresse email est déjà utilisée";
            }
        }
    }
    // vérification password
    if(empty($_POST["password"]))
    {
        $password = $user["password"];
    }
    else
    {
        $password = trim($_POST["password"]);
        if(empty($_POST["passwordBis"]))
        {
            $error["passwordBis"] = "Veuillez confirmer votre mot de passe";
        }
        elseif($_POST["password"] != $_POST["passwordBis"])
        {
            $error["passwordBis"] = "Les mots de passe ne correspondent pas";
        }
        if(!preg_match($regexPass, $password))
        {
            $error["password"] = "Veuillez saisir un mot de passe valide.";
        }
        else
        {
            $password = password_hash($password, PASSWORD_DEFAULT);
        }
    }
    // Si je n'ai aucune erreur
    if(empty($error))
    {
        $sql = $pdo->prepare("UPDATE users SET username = :us, email = :em, password = :mdp WHERE idUser = :id");
        $sql->execute([
            "us" => $username,
            "em" => $email,
            "mdp" => $password,
            "id" => $user["idUser"]
        ]);
        $_SESSION["username"] = $username;
        $_SESSION["flash"] = "Votre profil a bien été mis à jour";
        header("Location: /");
        exit;
    }
}

$title = " CRUD - Update ";
require("../ressources/template/_header.php");
if($user):
?>
<form action="" method="post">
    <!-- username -->
    <label for="username">Nom d'Utilisateur :</label>
    <input type="text" name="username" id="username" value="<?php echo $user["username"] ?>">
    <span class="erreur"><?php echo $error["username"]??""; ?></span>
    <br>
    <!-- Email -->
    <label for="email">Adresse Email :</label>
    <input type="email" name="email" id="email" value="<?php echo $user["email"] ?>">
    <span class="erreur"><?php echo $error["email"]??""; ?></span> 
    <br>
    <!-- Password -->
    <label for="password">Mot de passe :</label>
    <input type="password" name="password" id="password">
    <span class="erreur"><?php echo $error["password"]??""; ?></span> 
    <br>
    <!-- password verify -->
    <label for="passwordBis">Confirmation du mot de passe :</label>
    <input type="password" name="passwordBis" id="passwordBis">
    <span class="erreur"><?php echo $error["passwordBis"]??""; ?></span> 
    <br>

    <input type="submit" value="Mettre à jour" name="update">
</form>
<?php else: ?>
    <p>Aucun Utilisateur trouvé</p>
<?php 
endif;
require("../ressources/template/_footer.php");
?>