// Le point pour la selection de classe.
const slider = document.querySelector('.slider');
const bckg = document.querySelector(".bckg-close");
const modal = document.querySelector(".modal");
const left = document.querySelector(".left-part");
const right = document.querySelector(".right-part");
// La variable devant changer, un let, pas un const
let readmore = null;

function activate(e) {
    // Il faut un querySelectorAll
    const items = document.querySelectorAll('.item');
    // Le point pour la selection de classe.
    e.target.matches('.next') && slider.append(items[0])
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
        // On n'appelle pas la fonction pour un callback
        setTimeout(openModal, 400, h.textContent, p.textContent);
    }
    if(e.target.matches(".bckg-close") && readmore)
    {
        // La majuscule au nom de la fonction.
        closeModal();
        setTimeout(()=>{
            readmore.style.top = "";
            readmore = undefined;
        }, 400);
    }
}

// Les nom d'évènement sont en anglais et en minuscule
document.addEventListener('click',activate,false);

function openModal(h, p)
{
    left.style.height = modal.scrollHeight +"px";
    // L'ajout de "px" aux valeurs chiffrés.
    right.style.height = modal.scrollHeight +"px";
    modal.querySelector("h2").textContent = h;
    modal.querySelector("p").textContent = p;

    // le symbole "%" aux données chiffrés.
    left.style.left = "50%";
    right.style.right = "50%";
    modal.style.top = "0";
    bckg.style.display = "block";
}
// Il manque le mot clef "function"
function closeModal()
{
    left.style.height = modal.scrollHeight +"px";
    right.style.height = modal.scrollHeight +"px";

    left.style.left = "";
    right.style.right = "";
    modal.style.top = "";
    bckg.style.display = "";
}