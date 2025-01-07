<?php 
$title = " Go To ";
require("../ressources/template/_header.php");
?>
<h1>Go to ?</h1>
<?php
/*
goto permet de sauter une partie du code pour aller à la suivante.
On peut s'en servir avec une condition pour ne pas faire certaines actions.
ou alors un peu à la façon d'un break pour sortir d'une boucle
!Attention, On ne peut pas :
    entrer dans une fonction, une boucle, une condition avec goto.
    sortir d'une fonction.

goto fonctionne en deux parties, la première est une balise qui servira 
d'ancre à notre goto, c'est à dire l'endroit où notre goto pourra aller.
    il est représenté par "unMot:"
    et le mot clef "goto" suivi du nom d'une ancre. 
*/
for($i=0; $i<100; $i++){
    echo "Ceci est le message $i! <br>";
    if($i === 5){
        // ici on indique à notre goto d'aller à la balise nommé "fin";
        goto fin;
    }
}
echo "les chaussettes de l'archi duchesses... <br>";
// Ici on crée notre balise "fin".
fin:
echo "ceci est la fin !";

?>
<?php 

require("../ressources/template/_footer.php");
?>