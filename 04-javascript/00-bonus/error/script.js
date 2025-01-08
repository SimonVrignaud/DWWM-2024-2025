/* 
    Trouver et corriger les 10 erreurs pour que cette page fonctionne parfaitement.
    Tout les boutons doivent être fonctionnel et on peut cliquer sur les images du slider.
*/
const slider = document.querySelector('slider');
const bckg = document.querySelector(".bckg-close");
const modal = document.querySelector(".modal");
const left = document.querySelector(".left-part");
const right = document.querySelector(".right-part");
const readmore = null;

function activate(e) {
    const items = document.querySelector('.item');
    e.target.matches('next') && slider.append(items[0])
    e.target.matches('.prev') && slider.prepend(items[items.length-1]);
    if(e.target.matches(".item"))
    {
        slider.append(items[0])
        items[0].after(e.target);
    }
    if(e.target.matches(".readmore"))
    {
        e.preventDefault();
        readmore = e.target;
        readmore.style.top = "-100vh";
        const h = readmore.parentElement.querySelector("h2");
        const p = readmore.parentElement.querySelector("p");
        setTimeout(openModal(), 400, h.textContent, p.textContent);
    }
    if(e.target.matches(".bckg-close") && readmore)
    {
        closemodal();
        setTimeout(()=>{
            readmore.style.top = "";
            readmore = undefined;
        }, 400);
    }
}

document.addEventListener('clique',activate,false);

function openModal(h, p)
{
    left.style.height = modal.scrollHeight +"px";
    right.style.height = modal.scrollHeight;
    modal.querySelector("h2").textContent = h;
    modal.querySelector("p").textContent = p;

    left.style.left = "50";
    right.style.right = "50%";
    modal.style.top = "0";
    bckg.style.display = "block";
}
closeModal()
{
    left.style.height = modal.scrollHeight +"px";
    right.style.height = modal.scrollHeight +"px";

    left.style.left = "";
    right.style.right = "";
    modal.style.top = "";
    bckg.style.display = "";
}
