<?php 
/* 
	Include et require permettent d'inclure d'autres fichiers dans notre code.
    Dans le dossier ressources nous avons:
        un dossier template avec les fichiers :
        "_header.php"
        "_footer.php"
        "_nav.php"
        puis préparons dans un dossier style le fichier style.css 

    Cette façon de nommer avec un "_" en début de nom est une convention
    souvent utilisé pour indiquer que c'est un fichier qui ne doit 
    pas être chargé seul, c'est juste un composant, une partie de 
    quelque chose d'autre.

    Ensuite nous allons dans ce fichier préparer la balise 
    main avec son contenu.
*/
$title = " - Include";
$mainClass = "includeNav";
require("../ressources/template/_header.php");
/* 
    "require" et "include" ont le même rôle, ils servent à inclure 
    un autre fichier dans celui qu'on utilise actuellement.
    La principale différence entre les deux est la suivante :
        si le fichier n'est pas trouvé :
            require va provoquer une erreur et stoper le code
            include va provoquer un warning et continuer le code
*/
include("../ressources/template/_nav.php");
/* 
    Ajoutons :
        $title = " - Include";
    avant notre premier require;
    la variable que nous avons défini ici sera utilisé dans notre header.

    Il faut imaginer que include et require fusionnent nos fichiers.
    le code écrit ici et celui inclu ne font plus qu'un.s
*/
?>
<div>
    <p id="para1">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Assumenda adipisci repudiandae autem eveniet eligendi distinctio. Soluta error quaerat veniam pariatur, rem quidem commodi ipsa at reiciendis ut adipisci et corrupti!</p>
    <p id="para2">Qui nisi, accusamus possimus ratione pariatur veniam corrupti facilis nam dignissimos nulla reprehenderit ipsum eum placeat ad. Perferendis suscipit consequuntur, ad iusto dolorem distinctio numquam, voluptas, vitae iure eaque voluptate.</p>
    <p id="para3">Quam rerum, iste veniam culpa commodi necessitatibus recusandae magni ut blanditiis modi unde! Molestiae dignissimos, neque esse, in architecto delectus dolores animi quasi suscipit officia ab sit illo, ullam exercitationem.</p>
    <p id="para4">Ad molestias quasi asperiores soluta dolore nam, magnam fugiat alias magni! Nihil odit quos optio quasi, incidunt quisquam ad consectetur fuga nam minima id placeat ratione vitae molestias? Doloremque, repellat?</p>
    <p id="para5">Ratione ipsa autem deserunt, vero dolorem nesciunt delectus fugit quis eligendi ex in magni distinctio quibusdam nulla qui explicabo consectetur? Velit, sed repellat odit quas voluptates saepe illo molestias rerum.</p> 
</div>

<?php 
/*
    Il existe aussi include_once et require_once
    un peu plus gourmant et lent, ils sont particulièrement utile dans 
    une application complexe, car ils vérifieront si le fichier
    n'a pas déjà été inclu avant de tenter de l'inclure.

*/
// require("./ressources/template/_footer.php");
// require("./ressources/template/_footer.php");

require_once("../ressources/template/_footer.php");
?>