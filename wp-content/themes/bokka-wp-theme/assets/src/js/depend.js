/**
 * Use this file to setup extra dependencies that aren't loaded via webpack config.
 */

//Parent theme initialize
try {
    require('../../../../bokka-wp-theme/assets/src/js/initialize.js')
} catch (e) {
    console.log("Cannot include parent theme")
}