"use strict";

const div = document.querySelector(".anime");

document.querySelector(".b1").addEventListener("click", animation1);
document.querySelector(".b2").addEventListener("click", animation2);
document.querySelector(".b3").addEventListener("click", animation3);
document.querySelector(".b4").addEventListener("click", animation4);
document.querySelector(".b5").addEventListener("click", animation5);

function animation1()
{
    /*
        La méthode animate de JS prend deux arguments, le premier est la liste des keyframes
        que l'élément HTML devra parcourir.
        Le second est un objet contenant les options de l'animation
     */
    // les keyframe peuvent être un tableau d'objet.
    const keyframes = [
        {left: 0, top: 0},
        {left: "80%", top: "-200px"},
        {left: "20%", top: "500px"}
    ];
    const options = {
        duration: 2000,
        iterations: 3,
        // indique à l'élément de rester sur la dernière étape de son animation une fois fini.
        fill: "forwards",
        direction: "alternate"
    };
    div.animate(keyframes, options);
}
function animation2()
{
    // Les keyframes peuvent aussi être un objet dont chaque propriété à un tableau de ses étapes.
    const keyframes = {
        backgroundColor: ["blue", "red", "green"],
        opacity: [1, 0, 0.5]
    };
    div.animate(keyframes, {
        duration: 2000,
        direction: "alternate",
        iterations: 2
    });
}
function animation3()
{
    // On peut sauvegarder l'objet animate dans une variable pour avoir accès à certaines méthodes.
    const anime = div.animate(
        {transform: ["skew(0deg, 0deg)", "skew(360deg, 180deg)", "skew(0deg, 360deg)"]},
        {duration: 1500, direction: "alternate", iterations: 1}
    );
    console.log(anime);
    // Par exemple l'évènement finish s'enclenche une fois l'animation terminé.
    anime.addEventListener("finish", ()=>{
        document.querySelector(".b4").style.opacity = 1;
    })
}
let a4 = false;
function animation4()
{
    if(a4)
    {
        // On peut aussi remettre à zero une animation avec .cancel();
        a4.cancel();
        a4 = false;
    }
    else
    {
        a4 = div.animate({borderRadius: ["0", "50%", "5px", "25%"]},{duration: 2500, fill: "forwards"});
    }
}
// La méthode animate n'est qu'un raccourci pour l'utilisation des objets suivants :
const keyframe = new KeyframeEffect(div,
    [
        {transform: "translate(0,0)"}, 
        {transform: "translate(100%, 50%)"}
    ], 
    {duration: 3000, fill: "forwards"}
);
const anime5 = new Animation(keyframe, document.timeline);
async function animation5()
{
    console.log(anime5.playState);
    // la propriété playState indique l'état de l'animation
    const b5 = document.querySelector(".b5");
    switch(anime5.playState)
    {
        case "idle":
            anime5.play();
            b5.textContent = "Pause";
            // La propriété finished est une promesse pouvant indiquer la fin de l'animation.
            await anime5.finished;
            b5.textContent = "Reverse";
            break;
        case "running":
            anime5.pause();
            b5.textContent = "Continue";
            break;
        case "paused":
            anime5.play();
            b5.textContent = "Pause";
            break;
        case "finished":
            anime5.reverse();
            b5.textContent = "Pause";
            await anime5.finished;
            b5.textContent = "Start";
            break;
    }
}
//https://codepen.io/rachelnabors/details/PNYGZQ