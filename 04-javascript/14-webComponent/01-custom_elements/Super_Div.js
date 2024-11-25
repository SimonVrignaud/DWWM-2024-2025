export default class SuperDiv extends HTMLDivElement
{
    constructor()
    {
        super();
        this.#setStyle();
        this.addEventListener("click", this.hide);
    }
    #setStyle()
    {
        this.style.display = "block";
        this.style.overflow = "hidden";
        this.style.backgroundColor = this.getAttribute("bg")?? "red";
        this.style.transition = "height 0.3s linear";
        this.sizes = this.getBoundingClientRect();
        // console.log(this.sizes);
        this.style.height = this.sizes.height+"px";        
    }
    hide()
    {
        if(this.style.height == "1em")
            this.style.height = this.sizes.height+"px"; 
        else
            this.style.height = "1em";
    }
    connectedCallback()
    {
        console.log("log depuis le connected callback");
    }
    disconnectedCallback()
    {
        console.log("log depuis le disconnected callback");
    }
    adoptedCallback()
    {
        console.log("log depuis le adoptedcallback");
    }
    attributeChangedCallback(name, old, now)
    {
        console.log(`L'attribut "${name}" est passé de :
            "${old}" 
            à
            "${now}"`);  
        if(this.style.height == "1em")
            this.style.backgroundColor = "purple";
        else
            this.style.backgroundColor = this.getAttribute("bg")?? "red";
    }
    static get observedAttributes()
    {
        return ["style"];
    }
}
customElements.define("super-div", SuperDiv, {extends: "div"});