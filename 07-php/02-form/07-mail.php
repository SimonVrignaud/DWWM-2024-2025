<?php 
if(session_status() === PHP_SESSION_NONE)
{
    session_start();
}
require "../ressources/services/_csrf.php";
require "../ressources/services/_mailer.php";

$email = $subject = $body = $envoi = "";
$error = [];

if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['contact']))
{
    if(empty($_POST["email"]))
    {
        $error["email"] = "Veuillez entrer votre adresse e-mail.";
    }
    else
    {
        $email = cleanData($_POST["email"]);
        if(!filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            $error["email"] = "Veuillez entrer une adresse e-mail valide.";
        }
    }
    if(empty($_POST["sujet"]))
    {
        $error["sujet"] = "Veuillez entrer un sujet pour votre message.";
    }
    else
    {
        $subject = cleanData($_POST["sujet"]);
    }

    if(empty($_POST["corps"]))
    {
        $error["corps"] = "Veuillez entrer un sujet pour votre message.";
    }
    else
    {
        $body = cleanData($_POST["corps"]);
    }

    if(!isset($_POST['captcha'], $_SESSION['captchaStr']) || $_POST['captcha'] !== $_SESSION['captchaStr'])
    {
        $error["captcha"] = "CAPTCHA incorrecte !";
    }

    if(empty($error))
    {
        $envoi = sendMail($email, "contact@AFCIDWWM.fr", $subject, $body);
    }
}

$title = " Email ";
require("../ressources/template/_header.php");
if(!empty($envoi)):
?>
<p>
    <?php echo $envoi ?>
</p>
<?php endif; ?>
<form action="" method="post">
    <input type="email" name="email" placeholder="Votre email">
    <span class="error"><?php echo $error["email"]??"" ?></span>
    <br>
    <input type="text" name="sujet" placeholder="Sujet de votre message">
    <span class="error"><?php echo $error["sujet"]??"" ?></span>
    <br>
    <textarea name="corps" cols="30" rows="10" placeholder="Votre message"></textarea>
    <span class="error"><?php echo $error["corps"]??"" ?></span>
    <br>
    <div>
        <label for="captcha">Veuillez recoppier le texte ci-dessous pour valider :</label>
        <br>
        <img src="../ressources/services/_captcha.php" alt="CAPTCHA">
        <br>
        <input type="text" id="captcha" name="captcha" pattern="[A-Z0-9]{6}">
    <span class="error"><?php echo $error["captcha"]??"" ?></span>
    </div>
    <input type="submit" value="Envoyer" name="contact">
</form>
<?php
require("../ressources/template/_footer.php");
?>