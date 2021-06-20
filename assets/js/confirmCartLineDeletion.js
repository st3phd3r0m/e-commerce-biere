// Variables globales

// On s'assure que le document est chargé
window.onload = () => {
    var removeButton = document.querySelectorAll('.removeCartLine');

    //On place un écouteur d'évenement pour tous les boutton poubelle
    removeButton.forEach(element => {
        element.addEventListener("click",askUserToConfirm);
    });

} // fin window.onload

function askUserToConfirm(e){
    e.preventDefault();

    if(confirm('Voulez-vous vraiment retirer cet article de votre panier ?')){

        console.log(this.getAttribute("href"));
        window.location.replace(this.getAttribute("href"));
    }

}
