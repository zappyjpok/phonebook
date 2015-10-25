/**
 * Created by shawnlegge on 25/10/15.
 */

/**
 * Created by shawnlegge on 25/10/15.
 */

var gulp = require('gulp'),
    gutil = require('gulp-util'),
    concat = require('gulp-concat'),
    compass = require('gulp-compass'),
    sass = require('gulp-sass');

var jsFiles = [
    'bower_components/jquery/dist/jquery.min.js',
    'bower_components/bootstrap-sass/assets/javascripts/bootstrap.js',
    'App/Resources/js/main.js',
    'App/Resources/js/validation.js'
];

gulp.task('js', function(){
    gulp.src(jsFiles)
        .pipe(concat('app.js'))
        .pipe(gulp.dest('Public/JS'))
});

gulp.task('css', function(){
    return gulp.src([
        './App/Resources/scss/app.scss'
    ])
        .pipe(sass())
        .pipe(concat('app.css'))
        .on('error', gutil.log)
        .pipe(gulp.dest('Public/CSS'))
});

gulp.task('icons', function() {
    return gulp.src([
        './bower_components/font-awesome/fonts/**.*'
    ])
        .on('error', gutil.log)
        .pipe(gulp.dest('Public/fonts'))
});

gulp.task('icons-boot', function() {
    return gulp.src([
        './bower_components/bootstrap-sass/assets/fonts/bootstrap/**.*'
    ])
        .on('error', gutil.log)
        .pipe(gulp.dest('Public/fonts/bootstrap'))
});



