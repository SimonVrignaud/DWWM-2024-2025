"use strict";

/*
    par défaut, "this" représente l'objet dans lequel il se trouve.
    au plus haut niveau de notre script, il est donc égale à l'objet window.
 */
console.log(this);
console.log(this === window);

function test(){
    /*
        Dans une fonction il sera soit indéfini si on utilise "use strict"
        soit il prendra l'objet parent, ici window.
     */
    console.log(this);
}
test();
/*
    dans un écouteur d'évènement "this" représente l'élément sur lequel l'écouteur a été placé.
 */
document.body.addEventListener("click", test);
/*
    à la différence de e.target qui renvera l'élément sur lequel l'évènement à lieu .
        (ici il peut soit renvoyer body soit span)
    this renverra toujours body (dans notre cas présent)
 */
document.body.addEventListener("click", function(e){
    console.log(this, e.target);
});
/*
    Dans une fonction fléché, this ne représentera plus l'élément de l'évènement 
    mais l'objet englobant, ici "window";
 */
document.body.addEventListener("click", ()=>{
    console.log(this);
})
document.body.click();
/*
    .bind() permet de créer une nouvelle fonction avec un this qui retournera 
    le paramètre de .bind().
    mon test() donne un "this" indéfini
    mon test2() donne un "this" égale à {text:"salut tout le monde"}
 */
let test2 = test.bind({text:"salut tout le monde"});
test();
test2();

let article = document.createElement("article");
// Ici au clique le "this" me retournera mon article.
document.body.addEventListener("click", test.bind(article));
document.body.click();

// Par la suite, nous allons utiliser "this" dans nos objets.

document.querySelectorAll("li").forEach(
    li=>li.addEventListener("click", function(e){
        // e.target.style.backgroundColor = "red";
        this.style.backgroundColor = "red";
}));