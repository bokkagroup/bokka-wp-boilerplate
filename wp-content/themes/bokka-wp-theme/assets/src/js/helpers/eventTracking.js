// Setup ga event tracking function
module.exports = function (category, action, label) {
    return ga('send', 'event', category, action, label);
};
