const gulp = require('gulp');
const sass = require('gulp-sass');
const autoprefixer = require('gulp-autoprefixer');

sass.compiler = require('node-sass');

gulp.task('sass', function(){
	return  gulp.src('./scss/**/*.scss')
				.pipe(autoprefixer({
		            browsers: ['last 2 versions'],
		            cascade: false
		        }))
				.pipe(sass({outputStyle: 'compressed'}).on('error', sass.logError))
				.pipe(gulp.dest('./css'))
});

gulp.task('sass:watch', function(){
	gulp.watch('./scss/**/*.scss', gulp.series('sass'));
});