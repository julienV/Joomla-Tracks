var gulp = require('gulp');

// Load config
var config = require('../../config.js');

// Dependencies
var browserSync = require('browser-sync');
var minifyCSS   = require('gulp-minify-css');
var rename      = require('gulp-rename');
var del         = require('del');
var zip         = require('gulp-zip');
var uglify      = require('gulp-uglify');
var fs          = require('fs');
var xml2js      = require('xml2js');
var parser      = new xml2js.Parser();
var path       	= require('path');

module.exports.addPlugin = function (group, name) {
	var baseTask  = 'plugins.' + group + '.' + name;
	var extPath   = '../plugins/' + group + '/' + name;
	var mediaPath = extPath + '/media';

	// Clean
	gulp.task('clean:' + baseTask, function() {
		return del.sync(config.wwwDir + '/plugins/' + group + '/' + name, {force : true});
	});

	// Clean: Media
	gulp.task('clean:' + baseTask + ':media', function() {
		return del.sync(config.wwwDir + '/media/' + 'plg_' + group + '_' + name, {force: true});
	});

	// Copy
	gulp.task('copy:' + baseTask, ['clean:' + baseTask], function() {
		return gulp.src([
			extPath + '/**',
			'!' + extPath + '/media',
			'!' + extPath + '/media/**'
		])
			.pipe(gulp.dest(config.wwwDir + '/plugins/' + group + '/' + name))
			.pipe(browserSync.reload({stream:true}));
	});

	// Copy: media
	gulp.task('copy:' + baseTask + ':media', ['clean:' + baseTask + ':media'], function() {
		return gulp.src([
			mediaPath + '/**'
		])
			.pipe(gulp.dest(config.wwwDir + '/media/' + 'plg_' + group + '_' + name))
			.pipe(browserSync.reload({stream:true}));
	});

	// Watch
	gulp.task('watch:' + baseTask,
		[
			'watch:' + baseTask + ':plugin',
			'watch:' + baseTask + ':media'
		]
	);

	// Watch: plugin
	gulp.task('watch:' + baseTask + ':plugin', function() {
		gulp.watch(extPath + '/**', ['copy:' + baseTask]);
	});

	// Watch: Media
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
					var fileName = config.skipVersion ? 'plg_' + group + '_' + name + '.zip' : 'plg_' + group + '_' + name + '-v' + version + '.zip';

					// We will output where release package is going so it is easier to find
					var releasePath = path.join(config.release_dir, 'plugins');
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
}
