var gulp = require('gulp');

var config = require('../../config.js');

// Dependencies
var browserSync = require('browser-sync');
var minifyCSS   = require('gulp-minify-css');
var rename      = require('gulp-rename');
var del         = require('del');
var less        = require('gulp-less');
var uglify      = require('gulp-uglify');
var zip         = require('gulp-zip');
var fs          = require('fs');
var xml2js      = require('xml2js');
var parser      = new xml2js.Parser();
var path       	= require('path');

module.exports.addModule = function (name) {
	var baseTask  = 'modules.frontend.' + name;
	var extPath   = '../modules/frontend/' + name;
	var mediaPath = extPath + '/media';

	// Clean
	gulp.task('clean:' + baseTask, ['clean:' + baseTask + ':media'], function() {
		del.sync(config.wwwDir + '/modules/' + name, {force: true});
	});

	// Clean: Media
	gulp.task('clean:' + baseTask + ':media', function() {
		del.sync(config.wwwDir + '/media/' + name, {force: true});
	});

	// Copy
	gulp.task('copy:' + baseTask, ['clean:' + baseTask, 'copy:' + baseTask + ':media'], function() {
		return gulp.src([
			extPath + '/**',
			'!' + extPath + '/media',
			'!' + extPath + '/media/**'
		])
			.pipe(gulp.dest(config.wwwDir + '/modules/' + name))
			.pipe(browserSync.reload({stream:true}));
	});

	// Copy: media
	gulp.task('copy:' + baseTask + ':media', ['clean:' + baseTask + ':media'], function() {
		return gulp.src([
			mediaPath + '/**'
		])
			.pipe(gulp.dest(config.wwwDir + '/media/' + name))
			.pipe(browserSync.reload({stream:true}));
	});

	// Watch
	gulp.task('watch:' + baseTask,
		[
			'watch:' + baseTask + ':module',
			'watch:' + baseTask + ':media'
		],
		function() {
		});

	// Watch: Module
	gulp.task('watch:' + baseTask + ':module', function() {
		gulp.watch([
			extPath + '/**',
			'!' + extPath + '/media',
			'!' + extPath + '/media/**'
		], ['copy:' + baseTask]);
	});

	// Watch: Module
	gulp.task('watch:' + baseTask + ':media', function() {
		gulp.watch([
			extPath + '/media/**'
		], ['copy:' + baseTask + ':media']);
	});

	// Release: plugin
	gulp.task('release:' + baseTask, ['prepare:release'], function (cb) {
		fs.readFile(extPath + '/' + name + '.xml', function(err, data) {
			if (err) console.log(err);
			parser.parseString(data, function (err, result) {
				try {
					var version = result.extension.version[0];
					var fileName = config.skipVersion ? name + '.zip' : name + '-v' + version + '.zip';

					// We will output where release package is going so it is easier to find
					var releasePath = path.join(config.release_dir, 'modules/frontend');
					console.log('Creating new release file in: ' + releasePath);
					return gulp.src([
							extPath + '/**'
						])
						.pipe(zip(fileName))
						.pipe(gulp.dest(releasePath)).
						on('end', cb);
				}
				catch (error) {
					console.log('error building ' + name)
				}
			});
		});
	});
};
