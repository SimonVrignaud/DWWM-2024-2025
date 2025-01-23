<?php 

require __DIR__."/../../ressources/services/_shouldBeLogged.php";
require __DIR__."/../../ressources/services/_csrf.php";
require __DIR__."/../model/userModel.php";

/**
 * Gère la page d'inscription
 *
 * @return void
 */
function createUser()
{
    shouldBeLogged(false, "/");
    
    $username = $email = $password = "";
    $error = [];
    $regexPass = "/^(?=.*[!?@#$%^&*+-])(?=.*[0-9])(?=.*[A-Z])(?=.*[a-z]).{6,}$/";

    if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['inscription']))
    {
        // Vérification username :
        if(empty($_POST["username"]))
        {
            $error["username"] = "Veuillez saisir un nom d'utilisateur";
        }else
        {
            $username = cleanData($_POST["username"]);
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
            
            $resultat = getOneUserByEmail($email);
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
                $password = password_hash($password, PASSWORD_DEFAULT);
            }
        }
        // Vérification passwordBis
        if(empty($_POST["passwordBis"]))
        {
            $error["passwordBis"] = "Veuillez saisir à nouveau votre mot de passe";
        }
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
            addUser($username, $email, $password);

            $_SESSION["flash"] = "Inscription prise en compte, veuillez vous connecter";

            // die;
            header("Location: /");
            exit;
        }
    }
    require __DIR__."/../view/user/inscription.php";
}
/**
 * Gère la page "liste utilisateur"
 *
 * @return void
 */
function readUsers()
{
    $users = getAllUsers();
    require __DIR__."/../view/user/listeUtilisateur.php";
}
/**
 * Gère la page de mise à jour de profil
 *
 * @return void
 */
function updateUser()
{
    shouldBeLogged(true, "/05-mvc/connexion");

    if(empty($_GET["id"]) || $_SESSION["idUser"] != $_GET["id"])
    {
        $_SESSION["flash"] = "Accès Interdit !";
        header("Location: /05-mvc");
        exit;
    }

    // Je récupère mon utilisateur
    $user = getOneUserById($_GET["id"]);

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
            
            if($email != $user["email"])
            {
                $resultat = getOneUserByEmail($email);
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
            updateUserById($username, $email, $password, $_GET["id"]);
            $_SESSION["username"] = $username;
            $_SESSION["flash"] = "Votre profil a bien été mis à jour";
            header("Location: /");
            exit;
        }
    }
    require __DIR__."/../view/user/updateUser.php";
}
/**
 * Gère la page de suppression de compte
 *
 * @return void
 */
function deleteUser()
{
    shouldBeLogged(true, "/05-mvc/connexion");

    if(empty($_GET["id"]) || $_SESSION["idUser"] != $_GET["id"])
    {
        $_SESSION["flash"] = "Accès Interdit !";
        header("Location: /05-mvc/");
        exit;
    }

    // On supprime l'utilisateur
    deleteUserById($_GET["id"]);

    // Je déconnecte mon utilisateur.
    unset($_SESSION);
    session_destroy();
    setcookie("PHPSESSID","", time()-3600);

    // Je redirige mon utilisateur après quelques secondes
    header("refresh: 5; url=/");
    require __DIR__."/../view/user/deleteUser.php";
}

function connexion() {
    shouldBeLogged(false, "/");
    $email = $pass = "";
    $error = [];

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
            
            $user = getOneUserByEmail($email);


            // Si on a trouvé un utilisateur.
            if($user)
            {
                
                if(password_verify($pass, $user["password"])){
                
                    $_SESSION["logged"] = true; 
                    $_SESSION["idUser"] = $user["idUser"];
                    $_SESSION["username"] = $user["username"];
                    $_SESSION["expire"] = time()+ (60*60);
                    // Enfin nous redirigeons notre utilisateur vers une autre page.
                    header("location: /");
                    exit;
                }
                
                else
                    $error["login"] = "Email ou Mot de passe incorrecte. p";
            }
            else
                $error["login"] = "Email ou Mot de passe incorrecte. m";
        }
    }

    require __DIR__."/../view/user/connexionvue.php";
} 

function deconnexion() {
    unset($_SESSION);
    session_destroy();
    setcookie("PHPSESSID","", time()-3600);
    // puis on redirige notre utilisateur vers la page de connexion
    header("location: /05-mvc/connexion");
    exit;
}