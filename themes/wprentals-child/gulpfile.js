

var gulp = require('gulp');
var sass = require('gulp-sass');
var maps = require('gulp-sourcemaps');
var livereload = require('gulp-livereload');
var clean = require('gulp-clean');
var minifycss = require('gulp-minify-css');

//CONFIG PATHS
var config = {
	theme  : './sass',
	build:'./dist'
};

// TASKS
gulp.task('sass', function () {
  gulp.src(config.theme+'/main.scss')
  	.pipe(maps.init())
    .pipe(sass().on('error', sass.logError))
    .pipe(maps.write('./'))
    .pipe(gulp.dest('./css/'))
    .pipe(livereload());
});

gulp.task('watch', function () {
	livereload.listen();
	gulp.watch('./sass/**/*.scss',function(event) {
		gulp.run('sass');
	});
	gulp.watch(['./**/*.php', '*.php'],function(event) {
		gulp.run('sass');
	});
	gulp.watch(['./**/*.js', '*.js'],function(event) {
		gulp.run('sass');
	});
});

gulp.task('build',['sass','copy'],function() {
	gulp.run('css-min');
		//gulp.run('copy-rest');
});

gulp.task('clean', function(){
	return gulp.src( config.build+'' , {read: false})
		.pipe(clean());
});

gulp.task('copy', ['clean'],function () {
	return gulp.src(['./**/*','!sass/**/*','!**/node_modules/**/*','!.git','!.gitgnore','!package.json','!Gruntfile.js','!gulpfile.js'])
	.pipe(gulp.dest(config.build+''));
});

gulp.task('css-min', function(){
	return gulp.src(config.build+'/css/*.css')
		.pipe(maps.init())
		.pipe(minifycss())
		.pipe(maps.write('./'))
		.pipe(gulp.dest(config.build+'/css/'));
});
