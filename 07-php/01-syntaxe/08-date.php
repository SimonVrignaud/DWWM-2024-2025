<?php 
$title = " Les dates ";
require("../ressources/template/_header.php");
// Paramètre la timezone par défaut
date_default_timezone_set("Europe/Paris");
// Si on souhaite utiliser le timestamps, on pourra utiliser la fonction:
echo time(), "<br>";


/* 
    Pour afficher une date en PHP, on utilisera la fonction Date()
    Celle ci peut prendre un ou deux arguments 
    Le premier est un string. sur lequel on reviendra juste après
    Le second, optionnel est un timestamp.
    Si on ne donne pas ce second argument, le timestamp utilisé
    pour l'affichage de la date sera l'actuel. 
    sinon on peut lui en donné un futur ou passé.
*/
// echo date("");
/* 
    Si je laisse mon string vide, rien ne s'affichera.
    Ce string doit contenir le format de la date (ou/et l'heure)
    Pour cela on utilisera certaines lettres qui ont une signification
    pour la fonction date.

    Attention, les mois et jours seront en anglais.
*/
/* 
    d = numéro du jour du mois avec le 0
    m = numéro du mois avec le 0
    Y = Année sur 4 chiffres
*/
echo date("d/m/Y"), "<br>";
/* 
    j = numéro du jour du mois sans le 0
    n = numéro du mois sans le 0
    y = Année sur 2 chiffres
*/
echo date("j/n/y"), "<br>";
/* 
    D = nom du jour sur 3 lettres.
    l = nom du jour complet.
    M = nom du mois sur 3 lettres.
    F = nom du mois complet.
*/
echo date("D = l / M = F"), "<br>";
/* 
    N = numéro du jour dans la semaine avec dimanche = 7.
    w = numéro du jour dans la semaine avec dimanche = 0.
*/
echo date("D = N = w"), "<br>";
/* 
    z = numéro du jour dans l'année avec 1er janvier = 0.
    W = numéro de la semaine dans l'année.
*/
echo date("z -> W"), "<br>";
/* 
    t = nombre de jour dans le mois.
*/
echo date("F -> t"), "<br>";
/* 
    L = si bissextile retourne 1 sinon 0;
*/
echo date("Y -> L"), "<br>";
/* 
    h = L'heure en format 12 avec 0.
    i = Les minutes avec 0.
    s = les secondes avec 0.
    a = "am" ou "pm".
*/
echo date("h:i:s a"), "<br>";
/* 
    g = L'heure en format 12 sans 0.
    A = "AM" ou "PM".
*/
echo date("g:i:s A"), "<br>";
/* 
    H = L'heure en format 24 avec 0.
    v = millisecondes avec 0.
    à noter que selon le serveur, v peut ne pas fonctionner.
*/
echo date("H:i:s:v"), "<br>";
/* 
    G = L'heure en format 24 sans 0.
*/
echo date("G:i:s:v"), "<br>";
/* 
    O = Différence d'heure avec GMT sans ":".
    P = Différence d'heure avec GMT avec ":".
*/
echo date("O = P"), "<br>";
/* 
    I = Retourne 1 si heure d'été sinon 0.
    Z = Différence d'heure avec GMT en seconde.
*/
echo date("I -> Z"), "<br>";
// Date complète au format ISO 8601
echo date("c"), "<br>";
// Date complète au format RFC 2822
echo date("r"), "<br>";



require("../ressources/template/_footer.php");
?>