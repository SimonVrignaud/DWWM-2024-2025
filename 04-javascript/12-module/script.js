"use strict";
/* 
    Ecmascript permet l'export et import de fonctions et objets (entre autre choses).
    Cela va permettre de pouvoir diviser nos projets en plusieurs fichiers réutilisable.
    De plus cela nous évite d'avoir plusieurs balises script dans notre HTML que l'on doit placer dans un ordre précis pour que cela fonctionne.

    Pour utiliser cet export import, notre fichier principal doit être lié au HTML avec l'attribut :
        * type="module"

    ? -------------- EXPORT -----------------------
    Pour importer du code, il faut avant tout l'exporter.
    Rendons nous dans le fichier contenant ce que l'on souhaite exporter. (objets, classes, fonctions...)

    On va ajouter l'un des mots clefs suivant devant l'élément à exporter :
        * export
        * export default
    On peut exporter autant d'élément que l'on souhaite, mais seul l'un d'entre eux peut profiter de "export default"

    ? -------------- IMPORT -----------------------
    Par défaut, l'import ne peut se trouver qu'au niveau le plus haut du code.
    C'est à dire, pas dans une fonction, une condition, une boucle ou autre.

    Sans "export default" dans le fichier d'export, il suffit d'utiliser le mot clef "import", suivi d'entre accolade, séparés de virgule, les éléments que l'on veut importer.
    Enfin on met le mot clef "from" et en string le chemin vers le fichier.
        * import {salut, coucou} from "./salutation.js";

    Si un "export default" est présent, avant les accolades, on peut donner un nom (peu importe lequel) et il sera utilisé pour importer l'élément par défaut.
        * import b, {salut, coucou} from "./salutation.js";

    On peut aussi renommer les éléments importés avec le mot clef "as"
        * import {salut as s, coucou} from "./salutation.js";

    On peut aussi tout importer dans un seul objet qui contiendra tous nos éléments :
        * import * as salutations from "./salutation.js";

    Si du code est présent dans le fichier export, il sera executé lors de l'export.
*/
import b, {salut, coucou as c} from "./salutation.js";

salut();
b();
// Un élément importé peut faire appel à des éléments non importé
c("Maurice");

/* 
    Pour faire un import sans être au top level du script,
    il ne faudra pas utiliser le mot clef "import"
    mais la fonction "import()"

    Celle ci retournera une promesse et cette promesse retournera un objet contenant tout ce qui a été exporté.
*/
window.addEventListener("click", hello);

async function hello()
{
    const salutation = await import("./salutation.js");
    console.log(salutation);
    /* 
        "salutation importé" n'apparaît pas une nouvelle fois, 
        car le script d'un fichier importé n'est évalué qu'une seule fois,
        peu importe le nombre de fois qu'on l'importe

        On notera que "export default" est rangé dans la propriété "default"
    */
    salutation.default();
    salutation.coucou("Pierre")
    salutation.salut();
}