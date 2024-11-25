import SuperBalise from "./Super_Balise.js";
import SuperDiv from "./Super_Div.js";
/* 
    Les customs elements (éléments personnalisés) permettent décréer nos propres éléments HTML.
    On va pouvoir créer de nouvelles balises avec leurs propres règles et capacités.

    Il existe deux types de customs elements:
        - les éléments personnalisés autonomes qui héritent de "HTMLElement"
        - Les éléments personnalisés intégrés qui héritent d'un élément HTML particulier (div, span, p, li...)
    
    Pour les créer, nous allons devoir définir une classe.
    Puis hors de celle ci, appeler la méthode suivante :
        * customElements.define()

    Cette méthode prendra en premier argument, un string qui sera le nom de votre balise personnalisée.
        !IMPORTANT : Les noms des balises personnalisées doivent prendre un tiret "-"

    En second argument, elle prendra la classe que vous avez créé.
    Optionnellement, et cela pour les éléments personnalisés intégré, elle prendra le nom de la classe dont elle hérite.

    Une fois la méthode précédente appelée, pour utiliser nos balises, il suffit de suivre une des façons suivante :
        - autonome : "<nom-balise></nom-balise>"
        - intégré : <baliseParent is="nom-balise"></baliseParent>
    
    Il est aussi possible d'ajouter des "cycle de vie" à nos éléments HTML.
    Les cycles de vie, sont des méthodes prédéfinie qui se déclenchent automatiquement à certains moments précis :
        - "connectedCallback" se déclenche quand l'élément HTML est ajouté à la page.
        - "disconnectedCallback" se déclenche quand l'élément HTML est supprimé
        - "adoptedCallback" Se déclenche lorsque l'élément est déplacé d'un document à autre (avec un iframe par exemple)
        - "attributeChangeCallback" se déclenche lorsque l'attribut ciblé est modifié.
            Il prendra 3 arguments,
                le premier recevra le nom de l'attribut modifié,
                le second la valeur de l'attribut avant modification,
                le troisième, la valeur de l'attribut après modification.
            Pour que cela fonctionne, on devra accompagner cela d'un "getter static" appelé 
                "observedAttributes" qui retourne un tableau contenant les attributs à observer.
*/