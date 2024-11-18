"use strict";
const TG = document.querySelector(".p1");
const TD = document.querySelector(".p2");

function appa() {
    TG.textContent = "Aurélien";
    TD.textContent = "Dillies";

    const keyframes = [
        { transform: "translate(100%, 0%)" },
        { transform: "translate(0%, 0%)" }
    ];
    const keyframes2 = [
        { transform: "translate(-50%, 0%)" },
        { transform: "translate(0%, 0%)" }
    ];
    const options = {
        duration: 2000
    };
    TG.animate(keyframes, options);
    TD.animate(keyframes2, options);

    setTimeout(() => {
        TG.textContent = "Développeur";
        TD.textContent = "Web";

        TG.animate(keyframes, options);
        TD.animate(keyframes2, options);
    }, 4000);
}

setInterval(appa, 8000);

appa();