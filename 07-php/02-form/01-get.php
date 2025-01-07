<?php
/*
    Quelques conventions : 
        1. Quand on place toute notre logique PHP dans le même fichier que notre HTML, 
            On placera souvent toute la logique PHP en haut du fichier, avant le HTML.
        2. On aura tendance à déclarer toute les variables que l'on va utiliser en haut 
            de notre code pour s'en souvenir et pouvoir les modifier facilement sans recherche.
*/
// On va déclarer une variable pour chaque input de notre formulaire :
$username = $food = $drink = "";
// ainsi qu'un tableau qui contiendra nos messages d'erreurs.
$error = [];
//la liste des boissons et repas selectionnable.
$foodList = ["welsh", "cannelloni", "oyakodon"];
$drinkList = ["jus de tomate", "milkshake", "limonade"];
// On fera le formulaire. ainsi que la zone d'affichage.

// On trouvera dans la superglobal $_SERVER, en quel méthode on arrive sur une page.
// var_dump($_SERVER['REQUEST_METHOD']);
// par défaut on est toujours en mode GET lorsque l'on va de page en page.

/* Lorsque l'on veut regarder le contenu d'un formulaire envoyé en GET on va vérifier deux choses
Si on est bien en GET,
Et si notre formulaire a bien été soumis.
Pour cela on regarde si le "name" du bouton submit est bien présent. */
if ($_SERVER['REQUEST_METHOD'] == 'GET' 
    && isset($_GET["meal"]))
{
    /* Ensuite on va vérifier si le contenu de nos différents input sont bien présent.*/
    if(empty($_GET["username"]))
        $error["username"] = "Veuillez entrer un nom d'utilisateur.";
    else
    {
        // $username = $_GET["username"];
        /* 
            Mais ici nous avons une grosse faille de sécurité ! 
            tentons d'entrer la ligne suivante en tant qu'username :
                <script>alert("piraté !");</script>
            Pour éviter cela on va devoir nettoyer notre input
            Une règle du développeur est "Don't trust user !"
            on va utiliser cette fonction que l'on aura déclaré plus bas.
        */
        $username = cleanData($_GET["username"]);
        // Mais ce n'est pas tous, j'ai souvent besoin de limiter la taille d'un string.
        if(strlen($username) < 3 || strlen($username)>255)
            $error["username"] = "Votre nom d'utilisateur n'a pas une taille adapté.";
    }
    // Je répètes ces actions pour tous mes champs.
    // On va maintenant vérifier un second champ
    if(empty($_GET["food"]))
        $error["food"] = "Veuillez choisir un repas!";
    else
    {
        $food = cleanData($_GET["food"]);
        // Je vérifie si le plât donné est bien dans ma liste de plât existante.
        if(!in_array($food, $foodList))
            $error["food"] = "Ce repas n'existe pas !";
    }
    // Je reproduit la même chose pour ma boisson.
    if(empty($_GET["drink"]))
        $error["drink"] = "Veuillez choisir une Boisson!";
    else
    {
        $drink = cleanData($_GET["drink"]);
        if(!in_array($drink, $drinkList))
            $error["drink"] = "Cette boisson n'existe pas !";
    }
    /* 
    Une fois toutes nos vérifications faites, dans le cas où il n'y a pas d'erreur on pourrait 
    sauvegarder toutes nos informations en BDD ICI.
    */
}
/* 
pour des raisons de sécurité et de confort utilisateur, il est bien vu d'utiliser ces trois 
fonctions sur chaque input de l'utilisateur.
Alors autant en faire une fonction qu'on appellera quand on en a besoin plutôt que de répéter les 
trois à chaque fois.
*/
function cleanData($data){
    // par défaut trim retirera les espaces en trop au début ou à la fin du string.
    $data = trim($data);
    // stripslashes va retirer "\" du string
    $data = stripslashes($data);
    // htmlspecialchars, le plus important des trois va convertir les caractères spéciaux en entité HTML
    return htmlspecialchars($data);
    // exemple "<" devient "&lt;"
}
/* 
    Il existe d'autres façons de nettoyer le code, à vous de les chercher.
    Mais quoi que vous utilisiez, ne tentez pas uniquement ce qui doit fonctionner lorsque vous tester.
    tentez aussi ce qui ne doit pas fonctionner.
*/
$title = " GET ";
require("../ressources/template/_header.php");
?>
<!-- 
    l'attribut action permet d'indiquer vers quel page les informations doivent être envoyé.
    si on le laisse vide, il se contentera de recharger la même page. Ce que l'on fera ici.
    Ici on indiquera avec l'attribut method que l'on souhaite envoyer les informations en GET.
-->
<form action="" method="GET">
    <input type="text" placeholder="Entrez un nom" name="username">
    <!-- les span.error serviront à afficher les messages d'erreur. -->
    <span class="error"><?php echo $error["username"]??""?></span>
    <br>
    <fieldset>
        <legend>Nourriture favorite</legend>
        <input type="radio" name="food" id="welsh" value="welsh"> 
        <label for="welsh">Welsh (car vive le fromage)</label>
        <br>
        <input type="radio" name="food" id="cannelloni" value="cannelloni"> 
        <label for="cannelloni">Cannelloni (car les ravioli c'est surfait)</label>
        <br>
        <input type="radio" name="food" id="oyakodon" value="oyakodon"> 
        <label for="oyakodon">Oyakodon (car j'aime l'humour noir)</label>
        <span class="error"><?php echo $error["food"]??""?></span>
    </fieldset>
    <label for="boisson">Boisson favorite</label>
    <br>
    <select name="drink" id="boisson">
        <option value="jus de tomate">jus de tomate (je suis un vampire)</option>
        <option value="milkshake">Milkshake (aux fruits de préférence)</option>
        <option value="limonade">Limonade (J'ai besoin de sucre)</option>
    </select>
    <span class="error"><?php echo $error["drink"]??""?></span>
    <br>
    <!-- On ajoute un name au bouton submit pour pouvoir
    le vérifier en PHP -->
    <input type="submit" value="Envoyer" name="meal">
</form>
<!-- Cette partie du code ne s'affichera que si il n'y a rien dans le tableau $error et 
si le formulaire est soumis. -->
<?php if(empty($error) && isset($_GET["meal"])): ?>
    <h1>Meilleurs Repas :</h1>
    <p>
        <?php echo "Pour $username, le meilleur repas est \"$food\" avec \"$drink\""; ?>
    </p>
<?php 
endif; 
require("../ressources/template/_footer.php");
?>