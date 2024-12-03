import { Directive, ElementRef, HostListener, Input } from '@angular/core';

type Shadow = [number, number, number, number];

@Directive({
  selector: '[appBorderCard]'
})
export class BorderCardDirective {

  // @Input() appBorderCard: string|undefined;
  @Input("appBorderCard") borderColor: string|undefined;

  defaultShadow: Shadow = [5,5,10,2];
  newShadow: Shadow =[5,5, 20,2];
  defaultColor: string = "black";
  newColor: string = "green";

  // test: (string|number)[]= [];

  constructor(private el: ElementRef) 
  {    
    this.setShadow(...this.defaultShadow, this.defaultColor);
    this.setBorder(2, "black");
  }

  private setShadow(x: number, y:number, blur: number, radius: number, color: string)
  {
    this.el.nativeElement.style.boxShadow = `${x}px ${y}px ${blur}px ${radius}px ${color}`;
  }
  private setBorder(size: number, color: string)
  {
    this.el.nativeElement.style.border = `${size}px solid ${color}`;
  }
  @HostListener("mouseenter") onMouseEnter()
  {
    this.setShadow(...this.newShadow, this.borderColor||this.newColor);
    this.setBorder(2, this.borderColor||"green");
  }
  @HostListener("mouseleave") onMouseLeave()
  {
    this.setShadow(...this.defaultShadow, this.defaultColor);
    this.setBorder(2, "black");
  }
}
