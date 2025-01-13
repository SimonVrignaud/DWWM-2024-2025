<?php 
/* 
    Pour le cours ici présent, j'utiliserais le site : 
        https://mailtrap.io/
        Pour envoyer et intercepter les mails.
        
    Ici l'installation de PHPMailer est faite avec composer.
        * composer require phpmailer/phpmailer
    Mais elle peut aussi être faite manuellement.
    Ensuite j'appel les namespaces utilisé par phpMailer
        ! Attention, PHP Intelephense peut mal gérer cela. et déclarer des erreurs.
        (dans ce cas -> "intelephense.diagnostics.undefinedTypes": false)    
*/
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
/*
    On require l'autoload de composer, il permet de ne pas devoir 
    faire les require de chaque classe utilisée. 
*/
require __DIR__.'/../vendor/autoload.php';
/**
 * Envoi un mail.
 *
 * @param string $from
 * @param string $to
 * @param string $subject
 * @param string $body
 * @return string
 */
function sendMail(string $from, string $to, string $subject, string $body):string
{
    /* 
        On crée un nouvel objet PHPMailer,
        L'argument à true active les exceptions (type d'erreur). 
    */
    $mail = new PHPMailer(true);

    try {
        /* 
            * paramètre du serveur :
            Toute ces informations sont disponible sur votre serveur de mail. 
            On active l'utilisation de SMTP
            (Simple Mail Transfer Protocol)
        */
        $mail->isSMTP();
        /*
            On indique où est hebergé le serveur de mail qui enverra les mails.
        */
        $mail->Host = 'smtp.mailtrap.io'; 
        /* 
            On active l'authentification par SMTP
        */
        $mail->SMTPAuth = true; 
        /* 
            On indique par quel port du serveur de mail passer.
        */
        $mail->Port = 2525;
        /*
            On met l'username et le password.
        */
        $mail->Username = '1e77adf0ab1df0';
        $mail->Password = '5779de4e98bd51'; 
        /* 
            Donnera de nombreux détails sur comment 
            s'est déroulé la requête.
        */
        // $mail->SMTPDebug = SMTP::DEBUG_SERVER;
        /*
            Quel type de chiffrement sera utilisé pour envoyer les mails.
            Ici je ne l'active pas car cela peut poser problème avec mailtrap.
        */ 
        // $mail->SMTPSecure=PHPMailer::ENCRYPTION_SMTPS;
    
        /* 
            * Expediteur et Destinataire.

            "setFrom" prendra l'adresse de l'expediteur
            (et optionnellement un nom)
        */
        $mail->setFrom($from);
        /*
            "addAddress" permet d'ajouter les adresses à qui 
            envoyer nos mails.
            (optionnellement on peut ajouter un nom)
        */
        $mail->addAddress($to); 
        /*
            "addReplyTo" permet d'indiquer à qui on répond.
            "addCC" permet d'ajouter une adresse en copie.
            "addBCC" permet d'ajouter une adresse en copie caché. 

            * Pièce jointe.
            addAttachment($path, $name)
            addAttachment permet d'ajouter une piece jointe dont le chemin
            du fichier est en premier argument et optionnellement un nom est donné en second.

            * Contenu.
            isHTML permet d'indiquer si le format de l'email est du HTML
        */
        $mail->isHTML(true);
        # Le sujet de l'email
        $mail->Subject = $subject;
        # Le corps de l'email
        $mail->Body    = "<style>p{color: red;}</style><p>$body</p>";
        /* 
            On peut ajouter un corps différent dans le cas où le client 
            mail de l'utilisateur ne gère pas le html avec :
            "AltBody"

            On envoi l'email.
        */
        $mail->send();
        return 'Message envoyé';
    } catch (Exception $e) {
        return "Le message n'a pas pu être envoyé. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>