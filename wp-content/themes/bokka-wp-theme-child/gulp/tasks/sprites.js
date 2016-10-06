var gulp        = require('gulp')
var rename      = require('gulp-rename')
var replace     = require('gulp-replace')
var responsive  = require('gulp-responsive')
var spritesmith = require('gulp.spritesmith')

gulp.task('resize', function () {

    // Resize @2x images to @1x
    return gulp.src('./assets/src/images/icons/2x/*.{png,jpg}')
        .pipe(responsive({
            '*': {
                width: '50%',
                height: '50%'
            }
        }))
        .pipe(rename(function (path) {
            path.basename = path.basename.replace(/(@2x)/g, '');
        }))
        .pipe(gulp.dest('./assets/src/images/icons/1x'));

});

gulp.task('sprites', ['resize'], function () {

    // Generate sprite image file and css
    var spriteData = gulp.src('./assets/src/images/icons/**/*.{png,jpg}')
        .pipe(spritesmith({
            retinaSrcFilter: ['./assets/src/images/icons/**/*@2x.{png,jpg}'],
            imgName: 'sprite.png',
            retinaImgName: 'sprite@2x.png',
            imgPath: '../images/sprite.png',
            retinaImgPath: '../images/sprite@2x.png',
            cssName: 'sprite.css'
        }));

        spriteData.img.pipe(gulp.dest("./assets/build/images"));
        spriteData.css
            .pipe(replace(/\s?\.icon-/gm, '.sprite-'))
            .pipe(gulp.dest("./assets/src/css/base"));

});