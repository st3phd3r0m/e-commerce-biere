var range = document.getElementById('range');

// noUiSlider.create(range, {

//     range: {
//         'min': 1300,
//         'max': 3250
//     },

//     step: 150,

//     // Handles start at ...
//     start: [1450, 2050, 2350, 3000],

//     // ... must be at least 300 apart
//     margin: 300,

//     // ... but no more than 600
//     limit: 600,

//     // Display colored bars between handles
//     connect: true,

//     // Put '0' at the bottom of the slider
//     direction: 'rtl',
//     orientation: 'vertical',

//     // Move handle on tap, bars are draggable
//     behaviour: 'tap-drag',
//     tooltips: true,
//     format: wNumb({
//         decimals: 0
//     }),

//     // Show a scale with the slider
//     pips: {
//         mode: 'steps',
//         stepped: true,
//         density: 4
//     }
// });
var slider = document.getElementById('slider');

noUiSlider.create(slider, {
    // Position des curseurs au chargement de la page
    start: [20, 80],
    connect: true,
    // Fourchette de prix min/max des produits
    range: {
        'min': 'minPrice',
        'max': 'maxPrice'
    },  
});