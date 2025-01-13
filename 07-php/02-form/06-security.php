<?php 
/* 
    - XSS
    - CSRF
    - Injection SQL
    - BRUTE FORCE
    - BOT

    Une attaque connu est l'attaque XSS (Cross-Site Scripting) Le principe de ce genre 
    d'attaque est d'insérer des scripts étrangers dans votre page.
    La meilleur solution pour s'en protéger est 
    ! "Don't trust users"

    Et oui, le plus important pour s'en protéger est de ne jamais afficher une information rentrée
    par l'utilisateur sans l'avoir désinfecté (sanitize) au préalable.

    Ce que nous avons déjà fait avec "htmlspecialchars()".
*/
// Si on n'est pas connecté, on ne peut pas venir ici
// require("../ressources/services/_shouldBeLogged.php");
// shouldBeLogged();

// à ajouter quand on parle de csrf
require("../ressources/services/_csrf.php");
/* 
    Dans un chapitre précédent j'ai parlé de mots de passe haché, 
    ce terme peut vous sembler obscure et vous pensiez peut être voir le mot "chiffré"
        (et non pas crypté qui est un anglicisme de "to crypt")
    la principale différence à retenir est qu'un texte chiffré peut être déchiffré. 
        plus ou moins difficilement selon le chiffrage.
    Alors qu'un texte haché est perdu à jamais, on ne peut pas récupérer le texte d'origine.

    Donc une messagerie sécurisé chiffrera les messages.
    Mais un site sécurisé hachera les mots de passe.

    On a vu comment vérifier si un mot de passe correspond à sa version haché, mais on n'a pas
    encore vu comment hacher un mot de passe.
*/
$error = $password = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST' 
    && isset($_POST["hash"])){

    if(empty($_POST["password"]))
        $error = "Veuillez entrer un mot de passe";
    else
    {
        /* 
            Le mot de passe n'ayant pas à être affiché et encore moins en clair, 
            on n'a pas besoin de l'assainir mais on fera quand même le trim 
            pour être sûr qu'un espace en trop ne s'est pas faufilé. 
        */
        $password = trim($_POST["password"]);
        /* 
            password_hash() permet de hacher le mot de passe donné en premier argument.

            En second argument on va donner une constante prédéfini dans PHP entre :
                PASSWORD_DEFAULT
                PASSWORD_BCRYPT
                PASSWORD_ARGON2I 
                PASSWORD_ARGON2ID 
            Ce sont des constantes qui représente différents algorythmes de hachage.
            Actuellement (PHP 8.1) "PASSWORD_DEFAULT" représente "PASSWORD_BCRYPT" 
            mais si des algorythmes plus récents et plus fort sont ajouté à PHP, 
            cela pourrait changer.
            Il est donc conseiller d'utiliser celui ci, mais si vous voulez un hachage fixe qui
            ne changera pas au fil des mises à jours, vous pouvez choisir l'un des autres.

            En troisième argument (optionnel), on peut ajouter des options qui peuvent différer
            d'un algo à l'autre.
                par exemple avec BCRYPT on peut ajouter "['cost'=>10]" le cost est la puissance
                du hachage, par défaut il est à 10 ce qui est un bon compromis entre force 
                et temps d'execution. car si vous augmentez trop le hachage sera trop long.
        */
        $password = password_hash($password, PASSWORD_DEFAULT);
    }
    /*
        Nos formulaires ont encore plusieurs faiblesses, par exemple des bots 
        peuvent facilement envoyer des tas de requête et tenter de se connecter 
        par "BRUTE FORCE"
        C'est à dire que le bot peut tenter de se connecter des dizaines de fois par seconde 
        essayant à chaque fois un mot de passe différent.

        Pour se protéger de ce genre d'attaque, plusieurs solutions :
            1. Bloquer l'utilisateur après 3 essais jusqu'à ce qu'il reset son mot de passe.
                ! Problèmes : 
                    1. Si un utilisateur se trompe cela l'agacera.
                    2. Un bot malveillant pourrait bloquer tous vos utilisateurs.
            2. Ajout d'un "CAPTCHA", le principe d'un CAPTCHA est de forcer l'utilisateur 
                à réaliser une action qu'un bot ne pourrait que difficilement réaliser.
                Ici il faut faire la part des choses entre un CAPTCHA demandant une action très complexe
                pratiquement impossible à réaliser pour les bots mais qui ennuyerons vos utilisateurs et 
                un CAPTCHA trop simple qui ne dérangera pas vos utilisateurs mais pourrait laisser
                passer quelques bots.
            3. L'ajout d'une authentification à deux facteurs.
                Cela consiste à ne pas se reposer seulement sur le mot de passe mais aussi 
                ajouter une seconde source comme un code temporaire envoyé par sms ou email.
                ou alors un code temporaire généré par une application comme "google authenticator".

        Ici nous allons implémenter un petit CAPTCHA fait main, mais pour ceux qui veulent 
        pousser plus loins, voici le lien vers la documentation du CAPTCHA de google :
        *    https://developers.google.com/recaptcha/docs/v3
    */
    if(!isset($_POST['captcha'], $_SESSION['captchaStr']) 
    || $_POST['captcha'] != $_SESSION['captchaStr'])
        $error = "CAPTCHA incorrecte !";
        /* 
            Si le captcha n'est pas fourni ou si il ne correspond pas, on retourne une erreur.
            suite dans le formulaire.
        */
    /* 
        Un dernier point de sécurité à voir ici, ce sont les attaques CSRF ou XSRF (Cross-Site Request Forgery).
        Ce genre d'attaque à pour principe de faire cliquer sur un lien, ou valider un formulaire sur 
        un site malveillant par quelqu'un qui est connecté sur un site tout ce qu'il y a de plus normal.
        
        Imaginons que vous êtes administrateur sur un site. 
            Vous visitez un site quelconque que vous n'imaginez pas malveillant et validez 
            un formulaire d'inscription. Quand vous vous retrouvez étonnament sur le site 
            que vous administrez.
            Le formulaire avait des champs de type "hidden" contenant des informations à transmettre 
            aux formulaires réservés aux administrateur. Vous venez peut être de donner des droits
            d'administrateur à un compte malveillant, ou alors de supprimer un compte client très 
            important.
        (Cross-Site (d'un site à l'autre) request forgery (forgeage d'une requête ))

        Pour ce protéger de ce genre d'attaque, les captcha peuvent généralement fonctionner,
        Mais vos utilisateur vont vite quitter votre site si ils doivent remplir un captcha 
        à absolument tous les formulaires de votre site.

        La meilleur solution pour s'en protéger, ce sont les jetons CSRF (CSRF token).
        le principe est de générer un jeton sauvegardé en session et donnée en input hidden 
        à notre formulaire,  puis de vérifier si les deux correspondent.
        Un site exterieur n'aura pas de jeton correspondant. 

        Pour cela allons voir notre fichier _csrf.php dans nos services.
        puis incluont le en haut de page.

        On aura juste ici à appeler notre fonction isCsrfValid()
        et dans notre formulaire appeler la fonction setCsrf()
    */
    if(!isCSRFValid())
        $error = "La méthode utilisée n'est pas permise !";
}
$title = " Sécurité ";
require("../ressources/template/_header.php");
?>
<h1>Bienvenue <?php //echo $_SESSION["username"]; ?></h1>
<form action="" method="post">
    <input type="text" name="password" placeholder="mot de passe à hacher" required>
    <!-- Début partie sur les captcha -->
    <div>
        <label for="captcha">Veuillez recopier le texte ci-dessous pour valider :</label>
        <br>
        <!-- On place en source de notre balise img, notre fichier générant le captcha. -->
        <img src="../ressources/services/_captcha.php" alt="CAPTCHA">
        <br>
        <input type="text" id="captcha" name="captcha" pattern="[A-Z0-9]{6}">
    </div>
    <!-- fin partie sur les captcha -->
    <!-- début partie csrf -->
    <?php setCsrf(15) ?>
    <!-- Fin partie csrf -->
    <input type="submit" value="Hacher" name="hash">
    <span class="error"><?php echo $error??""; ?></span>
</form>
<?php if(empty($error) && !empty($password)): ?>
<div>
    Votre mot de passe haché est : 
    <?php echo $password ?>
</div>
<?php
endif;
require("../ressources/template/_footer.php");
?>