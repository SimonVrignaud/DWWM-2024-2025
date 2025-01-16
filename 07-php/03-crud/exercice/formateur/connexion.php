<?php
session_start([
    "cookie_lifetime" => 3600
]);
if(isset($_SESSION["logged"]) && $_SESSION["logged"] === true){
    header("Location: /");
    exit;
}
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
        
        // $users = file_get_contents("../ressources/users.json");
        // $users = json_decode($users, true);
        // $user = $users[$email] ?? false;
        require "../../../ressources/services/_pdo.php";
        $pdo = connexionPDO();
        $req = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $req->bindParam(':email', $email);
        $req->execute();
        $user = $req->fetch();


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
$title = " Connexion ";
require("../../../ressources/template/_header.php");
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
require("../../../ressources/template/_footer.php");
?>