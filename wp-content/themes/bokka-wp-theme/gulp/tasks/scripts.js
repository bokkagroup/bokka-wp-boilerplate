/*
  ___         _      _
 / __| __ _ _(_)_ __| |_ ___
 \__ \/ _| '_| | '_ \  _(_-<
 |___/\__|_| |_| .__/\__/__/
               |_|

 */

var gulp    = require('gulp')
var notify  = require('gulp-notify')
var plumber = require('gulp-plumber')
var webpack = require('webpack')
var webpack = require('webpack-stream')
var webpackConfig = require('../../webpack.config.js')
var livereload = require('gulp-livereload')

gulp.task('build-webpack', [], function () {
    return gulp.src(['../../assets/src/js/initialize.js', '!node_modules/**/*', '!gulp/**/*', '!build/**/*'])
        .pipe(plumber({errorHandler: notify.onError('Error: webpack build error')}))
        .pipe(webpack(webpackConfig))
        .pipe(gulp.dest('./assets/build/js/'))
        .pipe(livereload())
})

gulp.task('watch-webpack', [], function () {
    webpackConfig.watch = true
    return gulp.src(['../../assets/src/js/initialize.js', '!node_modules/**/*', '!gulp/**/*', '!build/**/*'])
        .pipe(plumber({errorHandler: notify.onError('Error: webpack build error')}))
        .pipe(webpack(webpackConfig))
        .pipe(gulp.dest('./assets/build/js/'))
        .pipe(livereload())
})
