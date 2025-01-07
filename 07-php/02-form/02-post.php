<?php
/* 
    La principale différence que l'on trouvera entre POST et GET est la sécurité, les informations de GET
    étant transmise via l'URL, il est très facile de les modifiers ce qui les rend peu fiable.
    Mais aussi très facile à partager, ce qui les rend très utile pour un moteur de recherche.
    Attention, bien que les données de POST soient invisible, elles ne sont pas totalement 
    sécurisé non plus, il est toujours important de nettoyer "Sanitize" les input de l'utilisateur.
    Biensûr si on doit transmettre des données secrète tel un mot de passe, on les enverra en POST.

    Au niveau du traitement de notre formulaire, les différences seront minime :
        1. l'attribut méthode du formulaire est passé à "POST"
        2. On vérifie si on arrive en méthode "POST" avant de traiter le formulaire.
        3. On récupère nos informations dans la superglobal "$_POST" et non "$_GET". 

    Comme ce cours serait déjà fini si on s'arrêtait là, améliorons un peu notre formulaire.
        1. On va transformer nos tableaux de données en tableau associatif.
        2. faire apparaître nos options et radio avec une boucle.
        3. Ajouter une classe "formError" à certaines de nos balises.
        4. Ajouter une case à cocher pour valider le formulaire.
		5. Faire que nos utilisateurs n'ai pas à remplir à nouveau les champs.
*/
// On reproduit la même chose qu'avec notre page GET avec $cgu en plus
$username = $food = $drink = $cgu = "";
$error = [];
//Amélioront un peu nos tableaux.
$foodList = [
    "welsh"=>"Welsh (car vive le fromage)", 
    "cannelloni"=>"Cannelloni (car les ravioli c'est surfait)", 
    "oyakodon"=>"Oyakodon (car j'aime l'humour noir)"
];
$drinkList = [
    "jus de tomate"=>"jus de tomate (je suis un vampire)", 
    "milkshake"=>"Milkshake (aux fruits de préférence)", 
    "limonade"=>"Limonade (J'ai besoin de sucre)"
];

/* Ici on ne vérifie plus si on est en GET mais en POST. Ce qui est majoritairement 
déclenché par un formulaire. 
Et on ne vérifie plus la présence de nos données dans $_GET mais dans $_POST */
if ($_SERVER['REQUEST_METHOD'] == 'POST' 
    && isset($_POST["meal"]))
{
    // Si le champ "username" est vide, j'affiche un message d'erreur.
    if(empty($_POST["username"]))
        $error["username"] = "Veuillez entrer un nom d'utilisateur.";
    else
    {
        $username = cleanData($_POST["username"]);
        if(strlen($username) < 3 || strlen($username)>255)
            $error["username"] = "Votre nom d'utilisateur n'a pas une taille adapté.";
    }
    // On va maintenant vérifier un second champ
    if(empty($_POST["food"]))
        $error["food"] = "Veuillez choisir un repas!";
    else
    {
        $food = cleanData($_POST["food"]);
        // Je vérifie si le plât donné est bien une clef de ma liste de plât.
        if(!array_key_exists($food, $foodList))
            $error["food"] = "Ce repas n'existe pas !";
    }
    // Et mon dernier champ
    if(empty($_POST["drink"]))
        $error["drink"] = "Veuillez choisir une Boisson!";
    else
    {
        $drink = cleanData($_POST["drink"]);
        if(!array_key_exists($drink, $drinkList))
            $error["drink"] = "Cette boisson n'existe pas !";
    }
    if(empty($_POST["cgu"]))
        $error["cgu"] = "Veuillez accepter nos conditions d'utilisation.";
    else
    {
        $cgu = $_POST["cgu"];
        if($cgu != "cgu")
            $error["cgu"] = "Ne modifiez pas notre formulaire !";
    }
    /* 
    Une fois toutes nos vérifications faites, dans le cas où il n'y a pas d'erreur on pourrait 
    sauvegarder toutes nos informations en BDD ICI.
    */
}
function cleanData($data){
    $data = trim($data);
    $data = stripslashes($data);
    return htmlspecialchars($data);
}
$title = " POST ";
require("../ressources/template/_header.php");
?>
<!-- On utilise la méthode POST. -->
<form action="" method="POST">
    <!-- 
        Améliorons légèrement notre formulaire en y ajoutant une classe dans le cas où il y a une 
        erreur.
     -->
    <input 
        class="<?php echo (empty($error["username"])?"":"formError") ?>" 
        type="text" 
        placeholder="Entrez un nom" 
        name="username"
        value="<?php echo $username ?>"
    >
    <span class="error"><?php echo $error["username"]??""?></span>
    <br>
    <fieldset class="<?php echo (empty($error["food"])?"":"formError") ?>">
        <legend>Nourriture favorite</legend>
        <!-- Je construit mes radios et mon select selon les données que j'ai en PHP
        Actuellement celles d'un tableau, mais cela pourrait devenir les données d'une BDD -->
        <?php foreach($foodList as $k => $f): ?>
        <input  
            type="radio" 
            name="food" 
            id="<?php echo $k ?>" 
            value="<?php echo $k ?>"
            <?php echo $food === $k ? "checked":"" ?>
        > 
        <label for="<?php echo $k ?>"><?php echo $f ?></label>
        <br>
        <?php endforeach; ?>
        <span class="error"><?php echo $error["food"]??""?></span>
    </fieldset>
    <label for="boisson">Boisson favorite</label>
    <br>
    <select class="<?php echo (empty($error["drink"])?"":"formError") ?>" name="drink" id="boisson">
        <?php foreach($drinkList as $k => $d): ?>
        <option 
            value="<?php echo $k ?>" 
            <?php echo $drink === $k ? "selected":""; ?>
        >
            <?php echo $d ?>
        </option>
        <?php endforeach; ?>
    </select>
    <span class="error"><?php echo $error["drink"]??""?></span>
    <br>
    <!-- On ajoute notre checkbox "cgu" -->
    <input type="checkbox" name="cgu" id="cgu" value="cgu">
    <label for="cgu">J'accepte que mes données ne m'appartiennent plus.</label>
    <span class="error"><?php echo $error["cgu"]??""?></span>
    <br>
    <input type="submit" value="Envoyer" name="meal">
</form>
<?php if(empty($error) && isset($_POST["meal"])): ?>
    <h1>Meilleurs Repas :</h1>
    <p>
        <?php echo "Pour $username, le meilleur repas est \"$food\" avec \"$drink\""; ?>
    </p>
<?php 
endif; 
require("../ressources/template/_footer.php");
?>
