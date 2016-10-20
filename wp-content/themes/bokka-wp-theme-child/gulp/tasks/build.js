/*
 __________ ____ ___.___.____     ________
 \______   \    |   \   |    |    \______ \
  |    |  _/    |   /   |    |     |    |  \
  |    |   \    |  /|   |    |___  |    `   \
  |______  /______/ |___|_______ \/_______  /
         \/                     \/        \/
 */


var gulp = require('gulp')

gulp.task('build', ['sprites', 'css', 'copyfonts', 'image', 'phpcs', 'phplint', 'build-webpack'])