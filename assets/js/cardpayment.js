window.onload = () => {
    // Variables
    let stripe = Stripe('pk_test_51H02NpD7y6oTPe9NvLSYF5bUs6byXcjWEQHhYjhMKxegGDQma8k5glBZz7ECrBZfYYADelw94Aqn4qO92lNIYQqk00MlGYRpHy')
    let elements = stripe.elements()
    let redirect = "/customer/store/transaction/"

    // Objets de la page
    let cardHolderName = document.getElementById("cardholder-name")
    let cardButton = document.getElementById("card-button")
    let clientSecret = cardButton.dataset.secret;

    // Crée les éléments du formulaire de carte bancaire
    let card = elements.create("card")
    card.mount("#card-elements")

    // On gère la saisie
    card.addEventListener("change", (event) => {
        let displayError = document.getElementById("card-errors")
        if(event.error){
            displayError.textContent = event.error.message;
        }else{
            displayError.textContent = "";
        }
    })

    // On gère le paiement
    cardButton.addEventListener("click", () => {
        stripe.handleCardPayment(
            clientSecret, card, {
                payment_method_data: {
                    billing_details: { name: cardHolderName.value }
                }
            }
        ).then((result) => {
            if(result.error){
                document.getElementById("errors").innerText = result.error.message
            }else{
                document.location.href = redirect
            }
        })
    })
}