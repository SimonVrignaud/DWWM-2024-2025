<?php 
// Quels sont les méthodes authorisées pour accèder à la page.
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE");
require __DIR__."/../model/userModel.php";
require __DIR__."/../../ressources/services/_csrf.php";

switch($_SERVER["REQUEST_METHOD"])
{
    case "POST": create(); break;
    case "GET": read(); break;
    case "PUT": update(); break;
    case "DELETE": delete(); break;
}

function create()
{
    // Récupération de données envoyées en JSON
    $json = file_get_contents("php://input");
    $data = json_decode($json, true);

    $username = $email = $password = "";
    $error = setError();
    $regexPass = "/^(?=.*[!?@#$%^&*+-])(?=.*[0-9])(?=.*[A-Z])(?=.*[a-z]).{6,}$/";

    if($data && isset($data["userForm"]))
    {
        // Vérification username :
        if(empty($data["username"]))
        {
            setError("username","Veuillez saisir un nom d'utilisateur");
        }else
        {
            $username = cleanData($data["username"]);
            if(!preg_match("/^[a-zA-Z' -]{2,25}$/", $username))
            {
                setError("username","Veuillez saisir un nom d'utilisateur valide");
            }
        }
        // Vérification email 
        if(empty($data["email"]))
        {
            setError("email","Veuillez saisir une adresse email");
        }else
        {
            $email = cleanData($data["email"]);
            if(!filter_var($email, FILTER_VALIDATE_EMAIL))
            {
                setError("email","Veuillez saisir une adresse email valide");
            }
            
            $resultat = getOneUserByEmail($email);
            if($resultat)
            {
                setError("email","Cette adresse email est déjà utilisée");
            }
        }
        // Vérification password :
        if(empty($data["password"]))
        {
            setError("password","Veuillez saisir un mot de passe");
        }else
        {
            $password = trim($data["password"]);
            if(!preg_match($regexPass, $password))
            {
                setError("password","Veuillez saisir un mot de passe valide");
            }
            else
            {
                $password = password_hash($password, PASSWORD_DEFAULT);
            }
        }
        // Vérification passwordBis
        if(empty($data["passwordBis"]))
        {
            setError("passwordBis","Veuillez saisir à nouveau votre mot de passe");
        }
        else if($data["passwordBis"] !== $data["password"])
        {
            setError("passwordBis","Les mots de passe ne correspondent pas");
        }
        /* 
            Pour simplifier le cours, il manque deux éléments à ce formulaire, 
            lesquels sont-ils?
                - CSRF
                - Captcha
        */
        $error = setError();
        if(empty($error))
        {
            addUser($username, $email, $password);

            sendResponse([], 200, "Inscription Validé");
        }
    }

    sendResponse([$error], 400, "Formulaire Incorrecte");
    // sendResponse([$_POST], 200, "POST");
}
function read()
{
    if(!empty($_GET["id"]))
    {
        $users = getOneUserById($_GET["id"]);
    }
    else
    {
        $users = getAllUsers();
    }
    sendResponse($users, 200, "Utilisateur(s) récupéré(s)");
}
function update()
{
    if(!isset($_GET["id"], $_SESSION["idUser"] ) || $_SESSION["idUser"] != $_GET["id"])
    {
        sendResponse([], 400, "Accès Interdit");
    }

    // Je récupère mon utilisateur
    $user = getOneUserById($_GET["id"]);
    // Récupération de données envoyées en JSON
    $json = file_get_contents("php://input");
    $data = json_decode($json, true);

    $username = $password = $email = "";
    $error = setError();
    $regexPass = "/^(?=.*[!?@#$%^&*.,+-])(?=.*[0-9])(?=.*[A-Z])(?=.*[a-z]).{6,}$/";

    if($data && isset($data["userForm"]))
    {
        // Vérification username
        if(empty($data["username"]))
        {
            $username = $user["username"];
        }
        else
        {
            $username = cleanData($data["username"]);
            if(!preg_match("/^[a-zA-Z' -]{2,25}$/", $username))
            {
                setError("username","Veuillez saisir un nom d'utilisateur valide");
            }
        }
        //vérification email
        if(empty($data["email"]))
        {
            $email = $user["email"];
        }
        else
        {
            $email = cleanData($data["email"]);
            if(!filter_var($email, FILTER_VALIDATE_EMAIL))
            {
                setError("email","Veuillez saisir une adresse email valide");
            }
            
            if($email != $user["email"])
            {
                $resultat = getOneUserByEmail($email);
                if($resultat)
                {
                    setError("email","Cette adresse email est déjà utilisée");
                }
            }
        }
        // vérification password
        if(empty($data["password"]))
        {
            $password = $user["password"];
        }
        else
        {
            $password = trim($data["password"]);
            if(empty($data["passwordBis"]))
            {
                setError("passwordBis","Veuillez saisir à nouveau votre mot de passe");
            }
            elseif($data["password"] != $data["passwordBis"])
            {
                setError("passwordBis","Les mots de passe ne correspondent pas");
            }
            if(!preg_match($regexPass, $password))
            {
                setError("password","Veuillez saisir un mot de passe valide");
            }
            else
            {
                $password = password_hash($password, PASSWORD_DEFAULT);
            }
        }
        $error = setError();
        // Si je n'ai aucune erreur
        if(empty($error))
        {
            updateUserById($username, $email, $password, $_GET["id"]);
            $_SESSION["username"] = $username;
            
            sendResponse([], 200, "Utilisateur mis à jour");
        }
    }
    sendResponse([$error], 400, "Formulaire Incorrecte");
}
function delete()
{
    if(!isset($_GET["id"], $_SESSION["idUser"] ) || $_SESSION["idUser"] != $_GET["id"])
    {
        sendResponse([], 400, "Accès Interdit");
    }

    // On supprime l'utilisateur
    deleteUserById($_GET["id"]);

    // Je déconnecte mon utilisateur.
    unset($_SESSION);
    session_destroy();
    setcookie("PHPSESSID","", time()-3600);

    sendResponse([], 200, "Compte supprimé et déconnecté");
}