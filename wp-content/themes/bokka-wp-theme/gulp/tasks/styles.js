/*
  ___ _        _
 / __| |_ _  _| |___ ___
 \__ \  _| || | / -_|_-<
 |___/\__|\_, |_\___/__/
          |__/

 */

var gulp            = require('gulp')
var autoprefixer    = require('autoprefixer')
var lost            = require('lost')
var postcss         = require('gulp-postcss')
var sourcemaps      = require('gulp-sourcemaps')
var nano            = require('gulp-cssnano')
var colorFunction   = require("postcss-color-function")
var livereload      = require("gulp-livereload")

gulp.task('css', function () {

    return gulp.src(['assets/src/css/**/*.css'])
        .pipe( sourcemaps.init() )
        .pipe( postcss([
            require("postcss-import"),
            require('postcss-mixins'),
            require('postcss-nested'),
            require('postcss-simple-vars')({ silent: true }),
            require('postcss-font-magician')({ hosted: './assets/build/fonts/' }),
            require('lost'),
            require('autoprefixer'),
            /*colorFunction(),*/
        ]))
        .pipe( nano() )
        .pipe(livereload())
        .pipe( sourcemaps.write('./assets/build/css/') )
        .pipe( gulp.dest('./assets/build/css/') )

});