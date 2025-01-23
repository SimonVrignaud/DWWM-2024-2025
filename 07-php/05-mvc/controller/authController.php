<?php 
require __DIR__."/../../ressources/services/_shouldBeLogged.php";
require __DIR__."/../model/userModel.php";
// session_start();

function login()
{
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

    require __DIR__."/../view/auth/connexion.php";
}

function logout()
{
    unset($_SESSION);
    session_destroy();
    setcookie("PHPSESSID","", time()-3600);
    // puis on redirige notre utilisateur vers la page de connexion
    header("location: /05-mvc/connexion");
    exit;
}