import { Component , OnInit } from '@angular/core';
import { RECETTES } from '../RecetteList';
import { Recette } from '../Recette';
import { BorderCardDirective } from '../border-card.directive';
import { DatePipe, UpperCasePipe } from '@angular/common';
import { TypeColorPipe } from '../type-color.pipe';
import { Router } from '@angular/router';

@Component({
  selector: 'app-liste-recette',
  imports: [BorderCardDirective, DatePipe, UpperCasePipe, TypeColorPipe],
  templateUrl: './liste-recette.component.html',
  styleUrl: './liste-recette.component.css'
})
export class ListeRecetteComponent implements OnInit {
 // recetteList = ["Carbonade", "Okonomiyaki", "Cannelé"];
  recetteList = RECETTES;
  recetteSelected: Recette|undefined;

  constructor(private router: Router){}

  ngOnInit(): void {
    console.table(this.recetteList);
    // this.selectRecette(this.recetteList[2])
  }
  selectRecette(recetteId: string):void
  {
    // const index: number = parseInt((event.target as HTMLInputElement).value);
    // if(!(event.target instanceof HTMLInputElement))return;
    // const index: number = parseInt(event.target.value);
    const index: number = parseInt(recetteId);
    const recette: Recette|undefined = this.recetteList.find(rec=>rec.id === index);
    if(recette)
    {
      console.log(`Vous avez selectionné ${recette.name}`, recette);    
      // this.recetteSelected = recette;
    }
    else
    {
      console.log("Aucune recette correspondante");      
      // this.recetteSelected = recette;
    }
    this.recetteSelected = recette;
  }
  goToRecette(recette: Recette)
  {
    this.router.navigate(["/recettes", recette.id]);
  }
}
