var gulp = require('gulp');

var config = require('../../config.js');

// Dependencies
var browserSync = require('browser-sync');
var del         = require('del');
var fs          = require('fs');
var minifyCSS   = require('gulp-minify-css');
var rename      = require('gulp-rename');
var symlink     = require('gulp-symlink');
var sass        = require('gulp-ruby-sass');
var uglify      = require('gulp-uglify');
var zip         = require('gulp-zip');

var name = 'tracks';
var baseTask  = 'libraries.' + name;
var extPath   = '../component/libraries/' + name;
var mediaPath = extPath + '/media/' + name;

// Clean
gulp.task('clean:' + baseTask,
	[
		'clean:' + baseTask + ':library',
		'clean:' + baseTask + ':manifest',
		'clean:' + baseTask + ':media'
	],
	function() {
});

// Clean: library
gulp.task('clean:' + baseTask + ':library', function() {
	del.sync(config.wwwDir + '/libraries/' + name, {force : true});
});

// Clean: manifest
gulp.task('clean:' + baseTask + ':manifest', function() {
	del.sync(config.wwwDir + '/administrator/manifests/libraries/' + name + '.xml', {force : true});
});

// Clean: media
gulp.task('clean:' + baseTask + ':media', function() {
	del.sync(config.wwwDir + '/media/' + name, {force : true});
});

// Copy
gulp.task('copy:' + baseTask,
	[
		'copy:' + baseTask + ':library',
		'copy:' + baseTask + ':manifest',
		'copy:' + baseTask + ':media'
	],
	function() {
});

// Copy: library
gulp.task('copy:' + baseTask + ':library',
	['clean:' + baseTask + ':library'], function() {
	return gulp.src([
		extPath + '/**',
		'!' + extPath + '/' + name + '.xml',
		'!' + extPath + '/**/docs',
		'!' + extPath + '/**/docs/**',
		'!' + extPath + '/vendor/**/sample',
		'!' + extPath + '/vendor/**/sample/**',
		'!' + extPath + '/vendor/**/tests',
		'!' + extPath + '/vendor/**/tests/**',
		'!' + extPath + '/vendor/**/composer.json',
		'!' + extPath + '/vendor/**/*.md',
		'!' + extPath + '/vendor/**/*.sh',
		'!' + extPath + '/vendor/**/build.xml',
		'!' + extPath + '/vendor/**/phpunit*.xml',
		'!' + extPath + '/vendor/**/.*.yml',
		'!' + extPath + '/vendor/**/.editorconfig',
		'!' + mediaPath,
		'!' + mediaPath + '/**'

	])
	.pipe(gulp.dest(config.wwwDir + '/libraries/' + name));
});

// Copy: manifest
gulp.task('copy:' + baseTask + ':manifest', ['clean:' + baseTask + ':manifest'], function() {
	return gulp.src(extPath + '/' + name +'.xml')
		.pipe(gulp.dest(config.wwwDir + '/administrator/manifests/libraries'));
});

// Copy: media
gulp.task('copy:' + baseTask + ':media', ['clean:' + baseTask + ':media'], function() {
	return gulp.src(mediaPath + '/**')
		.pipe(gulp.dest(config.wwwDir + '/media/' + name));
});


// Watch
gulp.task('watch:' + baseTask,
	[
		'watch:' + baseTask + ':library',
		'watch:' + baseTask + ':manifest'
	],
	function() {
});

// Watch: library
gulp.task('watch:' +  baseTask + ':library', function() {
	gulp.watch([
			extPath + '/**',
			'!' + extPath + '/' + name + '.xml',
			'!' + mediaPath,
			'!' + mediaPath + '/**'
		], ['copy:' + baseTask + ':library', browserSync.reload]);
});

// Watch: manifest
gulp.task('watch:' +  baseTask + ':manifest', function() {
	gulp.watch(extPath + '/' + name + '.xml', ['copy:' + baseTask + ':manifest', browserSync.reload]);
});
