'use strict'


var gulp = require('gulp');
var gulpif = require('gulp-if');
var sprity = require('sprity');


/**
 * Utility
 */
var gulp            = require('gulp')
var gutil           = require('gutil')


/**
 * JS
 * */
var webpack         = require('webpack')
var webpack = require('webpack-stream');

/**
 * PHP
 * */
var phplint         = require('phplint').lint
var phpcs           = require('gulp-phpcs')

/**
 * IMAGES
 **/
const imagemin = require('gulp-imagemin');
const pngquant = require('imagemin-pngquant');

/**
 * CSS
 **/
var autoprefixer    = require('autoprefixer')
var lost            = require('lost')



gulp.task('build-webpack', [], function () {
    return gulp.src('./assets/src/js/initialize.js')
        .pipe(webpack(require('./webpack.config.js') ))
        .pipe(gulp.dest('./assets/build/js/'))
})

gulp.task('phplint', function (cb) {
    var opt = {
        limit: 10,
        stdout: false,
        stderr: false
    }
    phplint(['./**/*.php', '!./node_modules/**/*', '!lib/**/*'], opt, function (err, stdout, stderr) {
        if (err) {
            cb(err)
            process.exit(1)
        }
        cb()
    })
})

gulp.task('phpcs', function () {
      return gulp.src(['./**/*.php', '!node_modules/', '!lib/**/*',  '!./css/**/*', '!**/*.css.map'])
            .pipe(phpcs({
                  standard: 'PSR2',
                  warningSeverity: 0
            }))
            .pipe(phpcs.reporter('log'))
})

gulp.task('copyfonts', function() {
    gulp.src('./assets/src/fonts/**/*.{ttf,woff,woff2,eof,svg}')
        .pipe(gulp.dest('./assets/build/fonts'));
});

gulp.task('css', function () {
    var postcss         = require('gulp-postcss')
    var sourcemaps      = require('gulp-sourcemaps')
    var nano            = require('gulp-cssnano')
    var colorFunction   = require("postcss-color-function")

    return gulp.src(['./assets/src/css/**/*.css', './assets/src/scss/**/*.scss'])
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
        .pipe( sourcemaps.write('./assets/build/css/') )
        .pipe( gulp.dest('./assets/build/css/') );
});

gulp.task('image', () => {
    return gulp.src('assets/src/images/**/*')
        .pipe(imagemin({
            progressive: true,
            svgoPlugins: [{removeViewBox: false}],
            use: [pngquant()]
        }))
        .pipe(gulp.dest('assets/build/images'))
})

// generate sprite.png and _sprite.scss
gulp.task('sprites', function () {
    return sprity.src({
            src: './assets/src/images/icons/**/*.{png,jpg}',
            style: './assets/src/css/base/sprite.css',
            prefix: 'sprite',
            'dimension': [{
                ratio: 1, dpi: 72
            }, {
                ratio: 2, dpi: 192
            }],

        })
        .pipe(gulpif('*.png', gulp.dest('./assets/build/images/'), gulp.dest('./assets/src/css/base/')))
});


gulp.task('watch', ['build-webpack', 'css', 'phpcs', 'copyfonts', 'image'], function () {
    gulp.watch(['assets/src/js/**/*.js', 'assets/src/js/**/*.html'], ['build-webpack'])
    gulp.watch(['assets/src/css/**/*.css'], ['css'])
    gulp.watch(['assets/src/images/**/*'], ['image'])
    //gulp.watch(['assets/src/images/icons/**/*'], ['sprites'])
    gulp.watch(['assets/src/fonts/**/*.{ttf,woff,woff2,eof,svg}'], ['copyfonts'])
    gulp.watch(['**/*.php', '!**/*.css.map'], ['phpcs', 'phplint'])
})

gulp.task('default', ['watch'])