<?php
/*
    Qu'est ce que le CRUD ?
    Le "CRUD" est un accronyme signifiant 
        Create Read Update Delete.
    Cela représente ce que la majorité des tables d'une BDD a besoin.
        Create : Créer de nouvelles lignes dans notre table.
        Read : Lire et afficher les données de notre table. 
        Update : Mettre à jour les données de notre table. 
        Delete : Supprimer les données de notre table. 

    Généralement pour chaque table que l'on crée, on aura besoin d'un 
    "CRUD" complet pour l'accompagner. Bien sûr il y a quelques exceptions
    par exemple sur twitter on peut créer de nouveau message, 
    lire les messages, les supprimer mais on ne peut pas les mettre à jour.

    Mais avant de commencer notre CRUD, on va devoir créer une BDD 
    et avoir la capacité de s'y connecter.

    Pour cet exemple, partons sur un classique, on va appeler 
    notre BDD "blog" et on va créer une table "users". 
    Notre table "users" va recevoir les colonnes suivantes :
        idUser int AI PK;
        username varchar(25);
        email varchar(255) UQ;
        password text;
        createdAt datetime DEFAULT timestamp; 
        (voir ressources/MCD/MCD-Blog-01.png)
    Rendez vous donc dans le dossier "ressources" avec les fichiers 
    "config/_blogConfig.php" puis "service/_pdo.php".

    Ensuite on va vérifier si on est connecté, et bien oui, un utilisateur connecté n'a rien à faire sur la page d'inscription.
*/
require "../ressources/services/_shouldBeLogged.php";
shouldBeLogged(false, "/");
/* 
    Une fois cela fait, attaquons nous à la construction de notre 
    formulaire. 
    On est dans la partie "Create" pour "User", donc créer un nouvel
    utilisateur, on est sur un formulaire d'inscription.

    Le formulaire construit, nous allons attaquer son traitement.
*/
$username = $email = $password = "";
$error = [];
// Une variable contenant la regex qui servira au mot de passe.
$regexPass = "/^(?=.*[!?@#$%^&*+-])(?=.*[0-9])(?=.*[A-Z])(?=.*[a-z]).{6,}$/";

if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['inscription']))
{
    // On inclu notre service de connexion.
    require "../ressources/services/_csrf.php";
    require "../ressources/services/_pdo.php";
    // On se connecte à notre BDD
    $pdo = connexionPDO();
    // Vérification username :
    if(empty($_POST["username"]))
    {
        $error["username"] = "Veuillez saisir un nom d'utilisateur";
    }else
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
    // Vérification email 
    if(empty($_POST["email"]))
    {
        $error["email"] = "Veuillez saisir une adresse email";
    }else
    {
        $email = cleanData($_POST["email"]);
        if(!filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            $error["email"] = "Veuillez saisir une adresse email valide";
        }
        /* 
            On souhaite que nos utilisateurs puissent n'avoir qu'un 
            seul compte par email, on va donc selectionner dans notre BDD tout utilisateur ayant cet email.

            Ici on a ce qu'on appelle une requête préparé.
            On détaillera la raison de son utilisation dans 
            le chapitre sécurité. 

            Pour l'instant retenons qu'on prend notre 
            instance de "PDO" et qu'on appelle 
            sa méthode "prepare" ($pdo->prepare())

            Dans celle ci on met sous forme de string notre
            requête SQL. avec un placeholder représenté par ":"
            ici ":em"
            le tout rangé dans une nouvelle variable.
        */
        $sql = $pdo->prepare("SELECT * FROM users WHERE email=:em");
        /*
            Puis on demande l'execution de la requête 
            En fournissant les informations fourni par l'utilisateur (ici l'email) soit directement dans l'execute, soit via une fonction comme bindParam
        */
        $sql->bindParam(':em', $email);
        $sql->execute();
        /* 
            Enfin on utilise fetch() pour aller chercher
            l'information récupéré par la requête.
        */
        $resultat = $sql->fetch();
        if($resultat)
        {
            $error["email"] = "Cette adresse email est déjà utilisée";
        }
    }
    // Vérification password :
    if(empty($_POST["password"]))
    {
        $error["password"] = "Veuillez saisir un mot de passe";
    }else
    {
        $password = trim($_POST["password"]);
        if(!preg_match($regexPass, $password))
        {
            $error["password"] = "Veuillez saisir un mot de passe valide";
        }
        else
        {
            /* 
                Si le mot de passe est valide, alors on le hash
            */
            $password = password_hash($password, PASSWORD_DEFAULT);
        }
    }
    // Vérification passwordBis
    if(empty($_POST["passwordBis"]))
    {
        $error["passwordBis"] = "Veuillez saisir à nouveau votre mot de passe";
    }
    /* 
        On vérifie si l'utilisateur a mit des mots de passes 
        différent, dans ce cas on affiche un message d'erreur. 
    */
    else if($_POST["passwordBis"] !== $_POST["password"])
    {
        $error["passwordBis"] = "Les mots de passe ne correspondent pas";
    }
    /* 
        Pour simplifier le cours, il manque deux éléments à ce formulaire, 
        lesquels sont-ils?

            - CSRF
            - Captcha
    */
    if(empty($error))
    {
        /* 
            Les requêtes préparés peuvent aussi avoir à la place 
            des placeholder des "?", dans ce cas là, la façon
            dont on donnera les variables changera légèrement.
        */
        $sql = $pdo->prepare("INSERT INTO users(username, password, email) VALUES(?,?,?)");
        $sql->bindParam(1, $_POST["username"], PDO::PARAM_STR);
        $sql->bindParam(2, $password, PDO::PARAM_STR);
        $sql->bindParam(3, $email, PDO::PARAM_STR);
        $sql->execute();

        $_SESSION["flash"] = "Inscription prise en compte, veuillez vous connecter";

        /* 
            Puis une fois inscrit, on redirige notre utilisateur 
            vers une autre page. souvent une page de connexion ou d'accueil.
        */
        header("Location: /");
        exit;
    }
}

$title = " CRUD - Create ";
require("../ressources/template/_header.php");
?>
<form action="" method="post">
    <!-- username -->
    <label for="username">Nom d'Utilisateur :</label>
    <input type="text" name="username" id="username" required>
    <span class="erreur"><?php echo $error["username"]??""; ?></span>
    <br>
    <!-- Email -->
    <label for="email">Adresse Email :</label>
    <input type="email" name="email" id="email" required>
    <span class="erreur"><?php echo $error["email"]??""; ?></span> 
    <br>
    <!-- Password -->
    <label for="password">Mot de passe :</label>
    <input type="password" name="password" id="password" required>
    <span class="erreur"><?php echo $error["password"]??""; ?></span> 
    <br>
    <!-- password verify -->
    <label for="passwordBis">Confirmation du mot de passe :</label>
    <input type="password" name="passwordBis" id="passwordBis" required>
    <span class="erreur"><?php echo $error["passwordBis"]??""; ?></span> 
    <br>

    <input type="submit" value="Inscription" name="inscription">
</form>
<?php 
/* 
    Pour des raisons de simplicité du cours, on n'a pas mit de securité
    sur ce formulaire, mais pensez à en ajouter sur vos projets.
*/
require("../ressources/template/_footer.php");
?>