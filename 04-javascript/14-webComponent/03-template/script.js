"use strict";
/* 
    La balise template est par défaut invisible dans votre navigateur.
    Peu importe ce que vous mettez dedans.

    Son but est d'accueillir des éléments HTML qui vont servir à être récupéré par Javascript, 
    Afin d'être cloné et réutilisé à divers endroits.

    On commencera par selectionner le template qui nous intéresse,
    Puis avec la propriété "content" on récupère le contenu du template sous la forme d'un "documentFragment".

    Enfin nous clonerons ce fragment via la méthode "cloneNode(true)" dont le boolean permet de cloner le contenu en entier et pas seulement la balise selectionné.

    Il nous restera qu'à insérer le clone dans le HTML.
*/
// Je récupère le template :
const blogTemplate = document.querySelector('#blog');
// Je récupère son contenu :
const blogArticle = blogTemplate.content;

// Je selectionne les différents éléments que je souhaite modifier :
const blogTitle = blogArticle.querySelector('h2');
const blogText = blogArticle.querySelector('p');
const blogSource = blogArticle.querySelector('a');

// Je récupère les informations de mon blog :
async function getBlog()
{
    const response = await fetch("blog.json");
    if(!response.ok) return;
    const articles = await response.json();
    articles.forEach(a => {
        blogTitle.textContent = a.title;
        blogText.textContent = a.content;
        blogSource.textContent = a.source;
        blogSource.href = a.source;
        // je clone le template:
        const clone = blogArticle.cloneNode(true);
        // J'insert le clone :
        document.body.append(clone);
    });
}
getBlog();

/* 
    Si les templates sont utilisable seul, 
    les slots eux accompagnent forcément le shadowDOM.

    On va donc tester cela sur un webcomponent.

    En insérant des balises "slot" avec des attributs "name".
    Puis en liant ce template au shadowDOM d'un customElement.

    Lorsque je vais appeler mon customElement, si je place des balises HTML ayant un attribut "slot" correspondant à un des attributs "name", alors celle ci viendra remplacer le "slot".

    Ainsi il est possible d'insérer des éléments variable à nos templates.
*/
import SuperCard from "./SuperCard.js";