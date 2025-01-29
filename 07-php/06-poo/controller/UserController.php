<?php 

use Model\UserModel;
use Classes\AbstractController;
use Classes\Interface\CrudInterface;

require __DIR__."/../../ressources/services/_shouldBeLogged.php";
require __DIR__."/../../ressources/services/_csrf.php";

class UserController extends AbstractController implements CrudInterface
{
    use Classes\Trait\Debug;

    private UserModel $db;
    private string $regexPass = "/^(?=.*[!?@#$%^&*.,+-])(?=.*[0-9])(?=.*[A-Z])(?=.*[a-z]).{6,}$/";

    function __construct()
    {
        $this->db = new UserModel();
        // $this->dump($this->db);
    }
    /**
     * Gère la page d'inscription
     *
     * @return void
     */
    function create()
    {
        shouldBeLogged(false, "/");
        
        $username = $email = $password = "";
        $error = [];
    
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
                
                $resultat = $this->db->getOneUserByEmail($email);
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
                if(!preg_match($this->regexPass, $password))
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
                $this->db->addUser($username, $email, $password);
    
                $this->setFlash("Inscription prise en compte, veuillez vous connecter");
    
                // die;
                header("Location: /");
                exit;
            }
        }
        $this->render("user/inscription.php", [
            "error"=>$error,
            "title"=>"POO - Inscription",
            "isRegister"=>true
        ]);
    }
    /**
     * Gère la page d'affichage des utilisateurs
     *
     * @return void
     */
    function read()
    {
        $users = $this->db->getAllUsers();

        $this->render("user/list.php", [
            "users" => $users,
            "title"=>"POO - Liste Utilisateur"
        ]);
    }
    /**
     * Gère la page de mise à jour du profil
     *
     * @return void
     */
    function update()
    {
        shouldBeLogged(true, "/06-poo/connexion");
    
        if(empty($_GET["id"]) || $_SESSION["idUser"] != $_GET["id"])
        {
            $this->setFlash("Accès Interdit !");
            header("Location: /06-poo");
            exit;
        }
    
        // Je récupère mon utilisateur
        $user = $this->db->getOneUserById($_GET["id"]);
    
        $username = $password = $email = "";
        $error = [];
        
    
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
                    $resultat = $this->db->getOneUserByEmail($email);
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
                if(!preg_match($this->regexPass, $password))
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
                $this->db->updateUserById($username, $email, $password, $_GET["id"]);
                $_SESSION["username"] = $username;
                $this->setFlash("Votre profil a bien été mis à jour");
                header("Location: /");
                exit;
            }
        }
        $this->render("user/updateUser.php", [
            "user" => $user,
            "error" => $error,
            "title"=> "POO - mise à jour du profil",
            "isRegister"=>false
        ]);
    }
    function delete()
    {
        shouldBeLogged(true, "/06-poo/connexion");
    
        if(empty($_GET["id"]) || $_SESSION["idUser"] != $_GET["id"])
        {
            $this->setFlash("Accès Interdit !");
            header("Location: /06-poo/");
            exit;
        }
    
        // On supprime l'utilisateur
        $this->db->deleteUserById($_GET["id"]);
    
        // Je déconnecte mon utilisateur.
        unset($_SESSION);
        session_destroy();
        setcookie("PHPSESSID","", time()-3600);
    
        // Je redirige mon utilisateur après quelques secondes
        header("refresh: 5; url=/");
        $this->render("user/deleteUser.php");
    }
}