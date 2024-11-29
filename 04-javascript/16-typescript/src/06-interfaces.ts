"use strict";
/**
 * Les interfaces sont à la jonction entre les types et les classes abstraite.
 * à la différence des classes abstraite, l'interface ne contiendra que des déclarations,
 * aucune définition.
 * à la différence des types, l'interface sera réservé aux objets et pourra être redéfini (fusionné)
 */
type Chaussette = string;
// erreur car redéclaré
// type chaussette = number;

interface Point {
    x: number;
    y: number;
    get(): number;
}
// Pas d'erreur, les deux sont fusionné
interface Point{
    z: number;
}
/**
 * Ctrl clique sur document pour afficher l'interface.
 * avant fusion, erreur,
 * après fusion, accepté.
 * L'élément document n'est pas réellement modifié, il n'y a pas de propriété chaussette
 * Mais typescript pense que oui et ne retournera pas d'erreur.
 */
interface Document{
    chaussette: number;
}
document.chaussette

// Ma classe a bien besoin d'avoir x,y,z et get pour être valide.
class Point3D implements Point{
    x=0;
    y=0;
    z=0;
    get(){ return this.x}
}
function show(p: Point){}
/**
 * Point3D implémente bien toute les règles de Point, donc qu'il soit implémenté ou non il est accepté.
 */
show(new Point3D);