"use strict";
document.chaussette;
// Ma classe a bien besoin d'avoir x,y,z et get pour être valide.
class Point3D {
    x = 0;
    y = 0;
    z = 0;
    get() { return this.x; }
}
function show(p) { }
/**
 * Point3D implémente bien toute les règles de Point, donc qu'il soit implémenté ou non il est accepté.
 */
show(new Point3D);
