<?php
/*
	TODO : checkbox "Voulez vous rester connecté ?"
    On crée un cookie avec une durée de vie.
*/
session_start([
    "cookie_lifetime" => 3600
]);
/* 
    On va vérifier si il existe un clef "logged" dans notre SESSION et 
    si elle existe, est ce qu'elle est égale à true.
    Si c'est le cas, on va considérer notre utilisateur comme connecté, 
    Et un utilisateur connecté n'a rien à faire sur la page de connexion
    On va donc le rediriger vers une autre page (ici la page d'accueil)
*/
if(isset($_SESSION["logged"]) && $_SESSION["logged"] === true){
    header("Location: /");
    exit;
}
$email = $pass = "";
$error = [];
// echo "<pre>" . print_r($users, 1) . "</pre>";
// Je vérifie si on arrive ici en méthode post.
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["login"])){
    // Je vérifie l'email
    if(empty($_POST["email"]))
        $error["email"] = "Veuillez entrer un email";
    else
        $email = trim($_POST["email"]);
    // Je vérifie le mot de passe.
    if(empty($_POST["password"]))
        $error["password"] = "Veuillez entrer un password";
    else
        $pass = trim($_POST["password"]);
    
    if(empty($error)){
        /* 
            Normalement on devrait aller vérifier si on a un utilisateur qui correspond 
            à cette adresse email dans notre BDD, mais on n'a pas encore vu comment gérer 
            notre BDD, donc on va se contenter d'un fichier JSON.

            file_get_contents() permet de récupérer le contenu d'un fichier.
            
            ici on a un fichier json, donc on va convertir le string json en données 
            lisible par PHP. pour cela on utilise :
                json_decode()
                    On pourrait se contenter de lui donner notre string, mais il nous rendrait
                    les informations sous la forme d'un objet, on a pas vu la POO donc on 
                    va lui ajouter le second paramètre à true pour qu'il nous rende 
                    les informations sous forme de tableau associatif qu'on sait manipuler.
                json_encode()
                    On ne l'utilise pas ici, mais pour transformer des données PHP en donnée JSON
                    on utilisera json_encode();
        */
        $users = file_get_contents("../ressources/users.json");
        $users = json_decode($users, true);
        // On vérifie si on a un utilisateur avec l'adresse email fourni par le formulaire de connexion.
        $user = $users[$email] ?? false;
        // Si on a trouvé un utilisateur.
        if($user)
        {
            /* 
                Si on regarde les différents mots de passes de nos utilisateurs, on remarquera 
                qu'ils commencent tous de la même manière. 
                Cela est dû au fait qu'ils ont tous été hashé avec le même algorythme.

                Ces infos vont suffire à la fonction "password_verify()" pour 
                comparer le mot de passe en clair avec le mot de passe hashé 
                et elle nous rendra un boolean indiquant si le mot de passe correspond ou non.
            */
            if(password_verify($pass, $user["password"])){
                /* 
                    Si le mot de passe est bon, on va créer en session la clef "logged" à true.
                    Puis on en profitera pour sauvegarder les informations de l'utilisateur 
                        qu'on souhaite réutiliser ailleurs.
                    On pourra ajouter à cela une clef "expire" avec le timestamp actuel additionné
                        au nombre de secondes qu'on veut attendre avant de déconnecter notre utilisateur.
                */
                $_SESSION["logged"] = true; 
                $_SESSION["username"] = $user["username"];
                $_SESSION["expire"] = time()+ (60*60);
                // Enfin nous redirigeons notre utilisateur vers une autre page.
                header("location: /");
				exit;
            }
            /* 
                ! Parlons sécurité :
                On remarquera que je met le même message d'erreur pour l'email 
                qui ne correspond pas ainsi que pour le mot de passe.
                Certes pour l'utilisateur cela ne l'aide pas à trouver où il s'est trompé
                mais cela augmente largement la sécurité en empêchant un pirate de 
                savoir ce qu'il doit chercher.
            */
            else
                $error["login"] = "Email ou Mot de passe incorrecte.";
        }
        else
            $error["login"] = "Email ou Mot de passe incorrecte.";
    }
}
$title = " Connexion ";
require("../ressources/template/_header.php");
?>

<form action="" method="post">
    <label for="email">Email</label>
    <input type="email" name="email" id="email">
    <br>
    <span class="error"><?php echo $error["email"]??""; ?></span>
    <br>
    <label for="password">Mot de passe</label>
    <input type="password" name="password" id="password">
    <br>
    <span class="error"><?php echo $error["pass"]??""; ?></span>
    <br>
    <input type="submit" value="Connexion" name="login">
    <br>
    <span class="error"><?php echo $error["login"]??""; ?></span>
</form>
<?php
require("../ressources/template/_footer.php");
?>