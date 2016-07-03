var gulp = require('gulp');
var browserify = require('browserify');
var babelify = require('babelify');
var clean = require('gulp-clean');
var uglify = require('gulp-uglify');
var gulpSequence = require('gulp-sequence');
var source = require('vinyl-source-stream');
var folder = "src/Rate/CurrencyBundle/Resources/public";


gulp.task('build', function () {
    return browserify({entries: folder + '/jsx/app.jsx', extensions: ['.jsx'], debug: true})
        .transform('babelify', {presets: ['es2015', 'react']})
        .bundle()
        .pipe(source('bundle.js'))
        .pipe(gulp.dest(folder + '/js/'));
});

gulp.task('build_clean_copy', function (cb) {
    return gulpSequence('build', 'clean', ['js_copy', 'css_copy'], cb);
});

gulp.task('clean', function () {
    return gulp.src(['web/css/*', 'web/js/*'])
        .pipe(clean());
});

gulp.task('js_copy', function () {
    return gulp.src([folder + '/js/*'])
        .pipe(uglify())
        .pipe(gulp.dest('web/js/'));
});
gulp.task('css_copy', function () {
    return gulp.src([folder + '/css/*'])
        .pipe(gulp.dest('web/css/'));
});

gulp.task('watch', ['build_clean_copy'], function () {
    gulp.watch(folder + '/jsx/*.jsx', ['build_clean_copy']);
    gulp.watch(folder + '/css/*.css', ['build_clean_copy']);
});

gulp.task('default', ['watch']);