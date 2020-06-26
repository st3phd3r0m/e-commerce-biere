// Variables globales
let images = ["./public/Css/images/Coucher_de_soleil.jpg", "./public/Css/images/Table_presentation_biere.jpg", "./public/Css/images/Sante_table.jpg"];
let compteur = 0; // Compteur qui servira d'index pour le tableau images
let intervalle; // Variable destinée à stocker l'intervalle du diaporama

// On s'assure que le document est chargé
window.onload =  () => {
    // On exécute changeImage toutes les 5 secondes
    intervalle = setInterval(changeImage, 5000);

    // On sélectionne la flèche droite
    let flecheDroite = document.querySelector(".la-chevron-circle-right");

    // On écoute l'évènement "click" sur la flèche droite
    flecheDroite.addEventListener("click", changeImage);

    // On sélectionne la flèche gauche
    let flecheGauche = document.querySelector(".la-chevron-circle-left");

    // On écoute l'évènement "click" sur la flèche gauche
    //flecheGauche.addEventListener("click", reculeImage);

    // On sélectionne l'image du diaporama
    let diaporama = document.querySelector(".slider");

    // On sélectionne la div contenant les points
    let points = document.querySelector(".circle");

    // On efface le contenu de la div
    points.innerHTML = "";

    // On ajoute les points en fonction du nombre d'images dans le tableau
    // On boucle sur toutes les images du tableau
    for(let image = 0; image < images.length; image++){
        // On ajoute un cercle dans la div
        // On crée la balise i
        let baliseI = document.createElement("i");

        if(image == 0){
            // Ici on crée le premier rond
            // On ajoute les classes à la balise i
            baliseI.classList.add("las", "la-dot-circle");
        }else{
            // Ici on crée tous les autres ronds
            // On ajoute les classes à la balise i
            baliseI.classList.add("las", "la-circle");
        }

        // On insère la balise i dans la div (en dernière position)
        points.appendChild(baliseI);
    }

} // fin window.onload

/**
 * Fait avancer le diaporama d'une image
 */
function changeImage(){

    // On va chercher tous les i
    let balisesI = document.querySelector(".circle i");
    
    console.log(baliseI[compteur]);

    // On efface le point de la balise i pointée actuellement (en changeant la classe)
    balisesI[compteur].classList.replace("la-dot-circle", "la-circle");

    // On incrémente le compteur
    compteur++;
    
    // Si le compteur dépasse le bout du tableau
    if(compteur == images.length){
        // On réinitialise le compteur
        compteur = 0;
    }

    // On va chercher l'image
    let diaporama = document.querySelector(".slider");

    // On change l'image
    diaporama.src = images[compteur];

    // On ajoute le point dans la nouvelle balise i pointée
    balisesI[compteur].classList.replace("la-circle", "la-dot-circle");
}

