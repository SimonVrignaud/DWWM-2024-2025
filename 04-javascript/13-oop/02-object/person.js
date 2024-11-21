/* 
    La programmation orienté objet consiste à développer notre logique et nos fonctions à l'interieur d'objets.
    
    La plupart des langages demandent à passer par des classes et des constructeurs, mais en JS, on peut créer nos objets manuellement.

    Ce qui ne rend pas les classes inutile, mais on verra cela plus tard.
*/

const Person = {
    name: {
        prenom: "Maurice",
        nom: "Dupont"
    },
    age: 54,
    /* 
        Les setters servent à paramétrer une propriété d'un objet en la filtrant par quelconques fonctions.
        Cela peut permettre d'obtenir seulement un nombre, ou une valeur précise.

        Le setter se déclare tel une fonction mais avec le mot clef "set" devant.
        Par contre son utilisation se fera telle une propriété :
        Person.age = 30; // OK
        Person.age(30); // Error
    */
    set setAge(a)
    {
        this.age = parseInt(a);
    },
    /* 
        Le mot clef "this" dans un objet fait référence à lui même.
        Ici this.age correspond donc à la propriété "age" de notre objet "Person"
    */
    set nom(n)
    {
        this.name.nom = n.toUpperCase();
    },
    set prenom(p)
    {
        this.name.prenom = p[0].toUpperCase()+p.slice(1).toLowerCase();
    },
    /* 
        Comme pour le setter, nous avons les getters qui permettent de récupérer une information filtré par quelconque fonctions.

        Précédé du mot clef "get", le getter "return" forcément une information.
        Et son utilisation se fait telle une propriété :
        Person.fullname; // OK
        Person.fullname(); // Error
    */
    get fullname()
    {
        return `${this.name.prenom} ${this.name.nom}`;
    },
    /* 
        Nos objets peuvent aussi contenir des fonctions,
        pour les déclarer, aucun besoin de mot clef.
        ! ATTENTION !
        En POO on ne parle pas de variable et de fonction d'objet mais
        de propriétés et de méthodes d'objet.
    */
    salutation()
    {
        console.log(`Bonjour, je suis ${this.fullname} et j'ai ${this.age} ans.`);
    },
    anniversaire()
    {
        this.age++;
        this.salutation();
    }
};
export default Person;