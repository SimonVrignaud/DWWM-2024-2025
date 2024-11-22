"use strict";
// J'importe ma classe "Human"
import H from "./Human.js";

// J'instancie mon objet à partir de ma classe "Human"
const humain = new H("Maurice", "Dupont", 54);

console.log(humain, H);

// -------------- Static ----------------
// Je peux appeler ma propriété static sur ma classe
console.log(H.categorie);
H.description();
// Je ne peux pas le faire sur mon objet
console.log(humain.categorie);
// humain.description();

// -------------- Private ---------------
// Je peux accèder à ma propriété public
console.log(humain.vivant);
// Je ne peux pas accèder à ma propriété privée
// console.log(humain.#name);

const humain2 = new H("Pierre", "fontaine", 39);

humain2.salutation();
humain.salutation();

// ----------------- héritage ---------------
import D from "./Dev.js";

const dev = new D("bruno", "dubois", 19, "Javascript");
console.log(dev);

dev.salutation();
dev.anniversaire();

console.log(dev instanceof H, dev instanceof D);
