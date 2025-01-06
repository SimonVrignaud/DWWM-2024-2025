<h1>Introduction </h1> <hr>
<!-- Le code PHP commence par <?php  ?> et ceci est sa balise de fin 
Il est commun de voir HTML et PHP se mélanger. -->
<?php
// PHP signifie "PHP Hypertext Preprocessor"
// Et ce second PHP est le nom d'origine de PHP, "Personnal Home Page"
// Un commentaire sur une seule ligne.
# Un autre commentaire sur une seule ligne.
/* 
    Un commentaire sur
    plusieurs lignes.
*/
// ! Chaque instruction de PHP se termine par ";"
# Les données écrite en PHP ne sont pas visible sur notre page.
# Pour afficher des données on utilisera une des fonctions suivantes.
echo "Coucou";
# echo n'a pas besoin de parenthèse et peut prendre plusieurs valeurs.
echo "Hello", "world";
/* Une fois le code php interprété, les informations affiché seront traité comme 
du HTML par les navigateurs.*/
print "<br> PHP !!!!";
/* print retournera la valeur 1 et sera un peu plus lent à l'execution. 
Il ne prend aussi qu'une valeur. 
print et echo sont des exceptions, les autres fonctions auront besoin de parenthèse. */
var_dump("Bonjour", "Le Monde");
// var_dump sera le meilleur ami du debug, il affichera des informations supplémentaires.
var_export("banane");
// var_export affichera sont contenu en tant que code php utilisable
# Cette fonction permet la récupération de toute les informations sur votre configuration PHP actuelle.
// phpinfo();
# getenv() permet de récupérer la donnée d'une des variables d'environnement visible avec PHP info.
echo getenv("SERVER_PORT");
#------------------------------------------------------------------------------------
echo "<h1>Déclaration des variables </h1> <hr>";
$banane; 
/* 
On déclare une variable en php avec un "$" puis une lettre ou un 
"_" puis ensuite les chiffres sont accepté. 
Essayez de nommer vos variable avec des noms logiques. 
Les variables sont sensible à la casse, $banane et $Banane sont différentes 
*/
// echo "banane :", $banane; 
#Ceci provoque une erreur car si notre variable est déclaré elle n'est pas défini.
$banane = "Jaune";
echo "banane :", $banane, "<br>"; 

#Pour définir une Constante en PHP, on utilisera define(nom, valeur) ou le mot clef const
define("AVOCAT", "vert");
const AVOCATS = "verreux";
// Par convention on mettra souvent les constantes PHP en majuscule.
echo "avocat :",AVOCAT, "<br>";
//On verra la portée des variables sur le chapitre parlant des fonctions.
#get_defined_vars() affiche toute les variables actuellement défini. certaines sont présente de base.
var_dump(get_defined_vars());

// Variable dynamique :
$chaussette = "rouge";
// chaussette est une variable qui vaut rouge.
$$chaussette = "bleu";
// Ici on crée une variable dont le nom est la valeur de chaussette
echo $rouge, "<br>";
// rouge vaut bleu.
//On peut détruire une variable avec unset();
unset($banane);
var_dump(get_defined_vars());
#------------------------------------------------------------------------------------
echo "<h1>Types des variables.</h1> <hr>";
$num = 5;
$dec = 0.5;
$str = "coucou";
$arr = []; // ou $arr = array();
$boo = true;
$nul = NULL;
$obj = (object)[];
// Integer (nombre entier)
echo gettype($num), "<br>";
// double ou float (nombre à virgule) 
echo gettype($dec), "<br>";
// string
echo gettype($str), "<br>";
// array
echo gettype($arr), "<br>";
// boolean
echo gettype($boo), "<br>";
// NULL
echo gettype($nul), "<br>";
// object
echo gettype($obj), "<br>";
// et les ressources dont on ne parlera pas ici.
// Les objets seront abbordé lorsque l'on verra la POO.
#------------------------------------------------------------------------------------
echo "<h1>Chaîne de caractères.</h1> <hr>";
// Un string peut être représenté par ces 2 caractères.
echo "bonjour ", 'coucou ', "<br>";
// les backticks `` auront un tout autre rôle dans php.
// en PHP, on peut faire des sauts à la ligne dans un string. mais ils ne seront pas prit en compte à l'affichage.
echo "Ceci est un 
    message si long 
    qu'il est sur plusieurs lignes. <br>";
$nom = "Maurice";
$age = 54;
// Il suffit d'insérer une variable dans un string pour interpoller.
// L'interpolation ne fonctionne qu'entre guillemet.
echo "$nom a $age ans. <br>";
echo '$nom a $age ans. <br>';
// Pour faire de la concaténation, il faut utiliser le ".";
echo $nom . " a " . $age . " ans. <br>";
$nom .= " DUPONT";
echo $nom, "<br>";
// quelques fonctions :
# donne la longueur du string
echo strlen($nom), "<br>";
# donne le nombre de mots
echo str_word_count($nom), "<br>";
# inverse le string
echo strrev($nom), "<br>";
# donne la position dans le string du second paramètre
echo strpos($nom, "i"), "<br>";
# [] après un string permet de selectionner le caractère à la position indiqué.
echo $nom[8], "<br>";
# Je change mon 8ème caractère par "L".
$nom[8] = "L";
echo $nom, "<br>";

# remplace le premier paramètre par le second dans le troisième.
echo str_replace("ce", "cette",$nom), "<br>";
#------------------------------------------------------------------------------------
echo "<h1>Nombres.</h1> <hr>";
// Il est possible de préfixer les nombres pour indiquer leur base.
// 0b pour binaire
$bin = 0b10000;
echo "\$bin = $bin";
echo '<br>';
// 0 pour octale
$oct = 020;
echo "\$oct = $oct";
echo '<br>';
// rien pour le décimal
$dec = 16;
echo "\$dec = $dec";
echo '<br>';
// 0x pour l'hexadecimal.
$hexa = 0x10;
echo "\$hexa = $hexa";
echo '<br>';
// Les nombres sont soit des INTEGER (pas de virgule), soit des FLOAT (virgule).
var_dump("3.14 is int?", is_int(3.14));
echo '<br>';
var_dump("3.14 is float?", is_float(3.14));
#is_int() permet de vérifier si un nombre est un integer.
#is_float() permet de vérifier si un nombre est un float.
echo '<br>';
// le plus gros et le plus petit INTEGER.
echo PHP_INT_MAX, "<br>", PHP_INT_MIN, "<br>";
// le plus gros et le plus petit FLOAT.
echo PHP_FLOAT_MAX, "<br>", PHP_FLOAT_MIN, "<br>";
// is_nan() permet de vérifier si une variable vaut NAN.
# is_numeric() vérifie si un string ne contient que des chiffres.
var_dump(is_numeric("0123456"));
// On peut transformer un string ou un float en int comme ceci :
echo "<br>", (int)"42", "<br>", (int)3.14, "<br>";

// Evidement l'utilisation d'opérateur mathématique est possible :
echo "1+1=", 1+1, "<br>";
echo "1-1=", 1-1, "<br>";
echo "2*2=", 2*2, "<br>";
echo "8/2=", 8/2, "<br>";
// modulo
echo "11%3=", 11%3, "<br>";
//puissances
echo "2**4=", 2**4, "<br>";
// les opérateur d'assignement sont aussi disponible.
$x = 5;
$x += 2;
$x -= 3;
$x *= 4;
$x /= 2;
$x %= 3;
echo $x, "<br>";
// l'incrémentation est la décrémentation sont aussi de retour :
echo $x++, "-->", $x, "<br>";
echo ++$x, "-->", $x, "<br>";
echo $x--, "-->", $x, "<br>";
echo --$x, "-->", $x, "<br>";

#------------------------------------------------------------------------------------
echo "<h1>Tableaux.</h1> <hr>";
// Originellement un tableau se créais ainsi :
$a = array("banane", "pizza", "avocat");
// Mais maintenant on peut tout simplement faire :
$b = ["banane", "pizza", "avocat"];
// Echo n'accepte que les strings ou ce qui peut en devenir comme les chiffres.
var_dump($a, $b);
// Une jolie façon d'afficher un tableau est :
echo "<pre>" . print_r($b, 1) . "</pre>";
# le 1 sert de boolean "true" pour que print_r retourne le tableau.
// Pour selectionner un élément d'un tableau, on utilisera l'index qui commence à 0.
echo "J'aime la $a[0], la $a[1] et l'$a[2]. <br>";
// Pour connaître la taille d'un tableau:
echo count($a), "<br>";
// Pour ajouter un élément à mon tableau :
$b[] = "fraise";
var_dump($b);
echo "<br>";
// En PHP il existe ce qu'on appelle des Tableaux Associatif (Associative Array)
// Le principe est de remplacer les index numérique par des clef nominative :
$person = ["prenom"=>"Maurice", "age"=>52];
// pour afficher les données on n'utilisera donc plus les chiffres mais les noms.
echo $person["prenom"] . " a " . $person["age"] . "ans. <br>";
// biensûr les tableaux peuvent être multidimensionnel.
$person["loisir"] = ["pétanque", "bowling"];
echo "<pre>" . print_r($person, 1) . "</pre>";
// on accolera juste les différents crochets ensemble pour aller de tableau en talbeau
echo $person["loisir"][0], "<br>";
// unset peut être utilisé pour supprimer un élément d'un tableau
unset($person["age"]);
echo "<pre>" . print_r($person, 1) . "</pre>";
// sur un tableau associatif ça ne pose pas de problème mais sur un tableau classique :
unset($b[1]);
// On voit que notre tableau à les indexes 0, 2 et 3, cela peut poser problème.
var_dump($b);
echo "<br>";
// Mais on peut arranger cela de deux façons, soit après avoir fait ça :
$b = array_values($b);
// array_values nous rend un tableau contenant toute les valeurs de notre tableau.
// On fait donc un clone avec les index remit au propre.
var_dump($b);
echo "<br>";
// Soit on peut préférer :
array_splice($a, 1, 1);
//array_splice() coupe le tableau depuis l'index en second argument sur une longueur donné en 3ème argument.
var_dump($a);
echo "<br>";
//On notera qu'on peut aussi se servire de cette fonction pour remplacer des éléments.
array_splice($a, 0, 1, ["brocoli", "pamplemousse"]);
var_dump($a);
echo "<br>";
// on pourra fusionner des tableaux avec array_merge();
$ab = array_merge($a, $b);
var_dump($ab);
echo "<br>";
// On pourra trier automatiquement un tableau avec les fonctions suivantes :
sort($ab);
var_dump($ab);
echo "<br>";
// Pour le sens décroissant on utilisera rsort();
/*
trier un tableau associatif : 
asort() par ordre croissant des valeurs.
ksort() par ordre croissant des clefs.
arsort() par ordre décroissant des valeurs.
aksort() par ordre décroissant des clefs.
*/

#------------------------------------------------------------------------------------
echo "<h1>Boolean.</h1> <hr>";
// Évidement les booleans ne prennent que les valeurs true ou false;
// Mais on peut les obtenirs de bien des façons:
$t = true;
$f = false;
var_dump($t, $f);

echo "<br> 5<3 : ";
var_dump(5<3);
echo "<br> 5<=5 : ";
var_dump(5<=5);
echo "<br> 5>3 : ";
var_dump(5>3);
echo "<br> 5>=5 : ";
var_dump(5>=5);
echo "<br> 5=='5' : ";
var_dump(5=='5');
echo "<br> 5==='5' : ";
var_dump(5==='5');
echo "<br> 5!='5' : ";
var_dump(5!='5');
echo "<br> 5<>'5' : ";
var_dump(5<>'5');
echo "<br> 5!=='5' : ";
var_dump(5!=='5');
// il est aussi possible de les combiner :

echo "<br> 5>3 and 5<2 : ";
var_dump(5>3 and 5<2);
// and peut aussi s'écrire &&
echo "<br> 5>3 && 5<2 : ";
var_dump(5>3 && 5<2);

echo "<br> 5>3 or 5<2 : ";
var_dump(5>3 or 5<2);
// or peut aussi s'écrire ||
echo "<br> 5>3 || 5<2 : ";
var_dump(5>3 || 5<2);

echo "<br> 5>3 xor 5<2 : ";
var_dump(5>3 xor 5<2);
// xor répond true si l'un des deux est true mais pas si les deux sont true.
echo "<br> 5>3 xor 5<8 : ";
var_dump(5>3 xor 5<8);

// "!" permet d'inverser le résultat, true devient false et false devient true.
echo "<br> 5>3 and 5<2 : ";
var_dump(!(5>3 and 5<2));


#------------------------------------------------------------------------------------
echo "<h1>Les Variables Superglobals.</h1> <hr>";
/*
    Certaines variables que l'on nomme superglobals sont accessible n'importe où dans votre code
    php et défini par défaut par celui ci:

    $GLOBALS
    # stock toute les variables globales définie (par vous ou php)

    $_SERVER
    # contient les informations lié au server, le header, l'url et j'en passe.

    $_REQUEST
    # Contient $_POST, $_GET et $_COOKIE, il n'y a que peu d'intérêt à l'utiliser.

    $_POST
    # Contient toute les informations envoyé en POST par un formulaire par exemple.

    $_GET
    # Contient toute les informations envoyé en GET.

    $_FILES
    # Contient toute les informations des fichiers téléversé.

    $_ENV
    # Contient les variables d'environnement.

    $_COOKIE
    # Contient les cookies.

    $_SESSION
    # Contient les informations stocké en session.
*/
echo "<pre>" . print_r($GLOBALS, 1) . "</pre>";
?>