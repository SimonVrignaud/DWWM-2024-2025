import { Component, OnInit } from '@angular/core';
import { RouterOutlet } from '@angular/router';
import { RECETTES } from './RecetteList';
import { Recette } from './Recette';

@Component({
  selector: 'app-root',
  imports: [RouterOutlet],
  templateUrl: './app.component.html',
  styleUrl: './app.component.css'
})
export class AppComponent implements OnInit {
  title = 'Super Projet de Cours';
  // recetteList = ["Carbonade", "Okonomiyaki", "Cannelé"];
  recetteList = RECETTES;
  recetteSelected: Recette|undefined;

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
}
