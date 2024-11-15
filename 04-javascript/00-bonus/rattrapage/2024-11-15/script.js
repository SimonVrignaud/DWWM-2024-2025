const maConstanteDeLi = document.querySelectorAll("li");

miseEnCouleurLi();





function miseEnCouleurLi()
{
    
    for(let compteur=0; compteur < maConstanteDeLi.length; compteur++)
    {
        const monLi = maConstanteDeLi[compteur];
        
        // if(/.+/.test(monLi.style.color))
        if(monLi.style.color != "")
        {
            continue;
        }
        monLi.style.color = "red";
    }
}
