"use strict";

const canvas = document.querySelector('canvas');

const ctx = canvas.getContext("2d");

function resize()
{
    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;
}
resize();
window.addEventListener("resize", resize);


// --------------- Canvas ---------------

ctx.fillRect(50, 50, 150, 25);
ctx.strokeRect(100, 150, 25, 150);

ctx.fillStyle = "rgba(156, 78, 94, 0.5)";
ctx.strokeStyle = "red";

ctx.fillRect(25, 59, 78, 95);
ctx.strokeRect(150, 150, 54, 245);

ctx.beginPath();
ctx.moveTo(345, 70);
ctx.lineTo(450, 200);
ctx.stroke();

ctx.beginPath();
ctx.moveTo(400, 300);
ctx.lineTo(500, 40);
ctx.lineTo(160, 543);
ctx.closePath();

ctx.strokeStyle = "green";
ctx.fillStyle = "yellow";
ctx.lineWidth = 8;

ctx.stroke();
ctx.fill();

// -------------- Cercles ----------------

ctx.beginPath();
ctx.arc(89, 90, 42, 0, 2*Math.PI);
ctx.stroke();

ctx.clearRect(50, 60, 70, 80);
// ctx.clearRect(0, 0, canvas.width, canvas.height);

// -------------- animation -----------

let x = 100, y = 100, vv = 5, vh = 5, r = 80;
let snapshot = ctx.getImageData(0, 0, canvas.width, canvas.height);
function cercle()
{
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    ctx.putImageData(snapshot, 0,0);
    ctx.beginPath();
    ctx.arc(x, y, r, 0, 2*Math.PI);
    ctx.fill();
    ctx.stroke();
    // ctx.drawImage(img, x, y, 100, 100);
    if(x+r > canvas.width || x-r < 0)
    {
        vh = -vh;
    }
    if(y+r > canvas.height || y-r < 0)
    {
        vv = -vv;
    }
    x += vh;
    y += vv;    
    requestAnimationFrame(cercle);
}
// cercle();

//  ---------------- images ---------------

const img = new Image();

img.src = "./logo_html.png";

img.onload = ()=>{
    ctx.drawImage(img, 50, 250, 100, 100);
    snapshot = ctx.getImageData(0, 0, canvas.width, canvas.height);
    cercle();
}

// img.onload = cercle;

// ----------------- texte -------------------

ctx.lineWidth = 1;
ctx.font = "82px serif";

ctx.strokeText("Coucou", 500, 300);
ctx.fillText("Coucou", 500, 400);

ctx.textAlign = "center";
ctx.fillStyle = "purple";

ctx.fillText("Salut tout le monde !", 500, 100, 200);

//  --------------- forme des traits -------------

ctx.lineWidth = 16;

ctx.beginPath();
ctx.lineCap = "round";
ctx.moveTo(700, 40);
ctx.lineTo(700, 400);
ctx.stroke();

ctx.beginPath();
ctx.lineCap = "square";
ctx.moveTo(750, 40);
ctx.lineTo(750, 400);
ctx.stroke();

ctx.beginPath();
ctx.lineCap = "butt";
ctx.moveTo(800, 40);
ctx.lineTo(800, 400);
ctx.stroke();

snapshot = ctx.getImageData(0, 0, canvas.width, canvas.height);