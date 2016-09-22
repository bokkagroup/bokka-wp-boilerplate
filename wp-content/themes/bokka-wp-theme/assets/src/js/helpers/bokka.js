// Setup global bokka object
var bokka = {};

// Attach helpers
bokka.eventTrack = require('./eventTracking');
bokka.breakpoint = require('./breakpoint');

// Initalize helpers as necessary
bokka.init = function () {
    bokka.breakpoint.init();
};

module.exports = bokka;
