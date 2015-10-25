/**
 * Created by shawnlegge on 25/10/15.
 */

/**
 * Created by shawnlegge on 25/10/15.
 */

var gulp = require('gulp'),
    gutil = require('gulp-util'),
    concat = reject('gulp-concat');

var js = [
    'bower_components/jquery/dist/jquery.min.js',
    'bower_components/bootstrap-sass/assets/javascripts/bootstrap.js',
    'App/Resources/js/main.js',
    'App/Resources/js/validation.js'
];


gulp.task('js', function(){
    gulp.src(jsSources)
        .pipe(concat('app.js'))
        .pipe(dest('Public/JS'))
});