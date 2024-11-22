"use strict";
import H from "./Human.js";

/* 
    Avec le mot clef "extends" on permet l'héritage d'une classe.
    Ici notre classe "Dev" hérite de notre classe "Human"

    l'héritage permet de donner toute les propriétés et méthodes d'une classe, à une autre.
    
    Toute, pas exactement, les propriétés et méthodes "private" ne sont pas transmise.
*/
export default class Dev extends H
{
    /**
     * Crée un nouveau développeur.
     * @param {string} prenom 
     * @param {string} nom 
     * @param {number|string} age 
     * @param {string|Array} tech 
     */
    constructor(prenom, nom, age, tech)
    {
        /* 
            Lors d'un héritage en Javascript, 
            Il est important dans le constructor d'appeler la fonction "super()"
            Celle ci appellera le constructor du parent.
            On lui donnera alors les paramètres attendu par le parent
        */
        super(prenom, nom, age);
        this.techniques = tech;
    }
    set techniques(t)
    {
        if(Array.isArray(t))
        {
            this.tech = t;
        }else
        {
            this.tech = [t];
        }
    }

    /* 
        Bien que la classe hérite de toute les méthodes et propriétés publiques.
        On pourrait vouloir qu'une méthode fonctionne différement avec cette classe.

        Pour cela il suffit de la redéclarer avec le même nom, elle remplacera celle de son parent
    */
    salutation()
    {
        console.log(`Bonjour, je suis ${this.getFullname} et j'ai ${this.getAge} et je maîtrise ${this.tech.join(", ")}`);
    }
}