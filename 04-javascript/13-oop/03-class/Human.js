"use strict";
/* 
    Une classe est un plan de construction pour un objet.

    Certaines classes sont déjà intégré par défaut à JS:
        "Date", "FormData"...
    Mais on peut aussi créer les notres.
        Pour cela on utilisera le mot clef "class" suivi du nom de la classe
        puis d'accolades, telle que :
            * class MaSuperClass{}
    
    Quelques conventions de développement :
        - une classe par fichier,
        - Le nom de la classe commence par une majuscule,
        - Le nom du fichier est le même que celui de la classe.
    
    Losqu'on voudra créer un objet à partir d'une classe (instancier), 
    on appellera le nom de la classe précédé du mot clef "new"
        * const monSuperObjet = new MaSuperClass();
*/
export default class Human
{
    /* 
        En javascript nous allons trouver 3 types de propriétés.

        - La propriété public, on est dans une classe, donc pas besoin de "virgule" à la fin, ni de mot clef au début (let, var...)
        - La propriété privée, elle prend un "#" devant son nom.
            Elle a la particularité de n'être accessible que dans la classe elle même.
        - La propriété static, elle est précédé du mot clef "static".
            Elle n'est accessible que sur la classe, et non sur l'objet.
    */
    vivant = true;
    #name = {};
    #age;
    static categorie = "Mammifère";
    /**
     * Créer un nouvel humain
     * @param {string} prenom prenom de l'humain
     * @param {string} nom nom de l'humain
     * @param {number|string} age age de l'humain
     */
    constructor(prenom, nom, age)
    {
        this.setPrenom = prenom;
        this.setNom = nom;
        this.#setAge = age;
        /* 
            Les propriétés privées doivent être déclarées à l'avance.
            Mais les propriétés publiques peuvent être déclarées directement dans une méthode.
        */
        this.createdAt = new Date();
    }
    /* 
        Les méthodes aussi peuvent être static ou private.
        On y ajoutera aussi la possibilité de faire des getter et des setters.
    */
    static description()
    {
        console.log(`Un humain est un ${this.categorie}, a généralement une tête, un buste, deux bras et deux jambes.`);
    }
    set setPrenom(p)
    {
        this.#name.prenom = p[0].toUpperCase() + p.slice(1).toLowerCase();
    }
    set setNom(n)
    {
        this.#name.nom = n.toUpperCase();
    }
    set #setAge(a)
    {
        this.#age = parseInt(a);
    }
    get getFullname()
    {
        return this.#name.prenom+ " "+ this.#name.nom;
    }
    get getAge()
    {
        return this.#age+ " ans";
    }
    salutation()
    {
        console.log(`Bonjour, je suis ${this.getFullname} et j'ai ${this.getAge}`);
    }
    anniversaire()
    {
        this.#age++;
        this.salutation();
    }
}