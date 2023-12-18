

window.addEventListener('load', function () {
    // Sélectionnez le bouton par son ID
    var boutonPayement = document.getElementById('mettreEnPaiement');
    // Ajoutez un écouteur d'événements au clic sur le bouton
    boutonPayement.addEventListener('click', function () {
        // Code à exécuter lorsque le bouton est cliqué
        alert('Le bouton a été cliqué !');
    });
});
