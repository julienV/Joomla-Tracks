const gulp = require('gulp');

const config = require('../../config.js');

// Dependencies
const browserSync = require('browser-sync');
const rename      = require('gulp-rename');
const del         = require('del');
const less        = require('gulp-less');
const minifyCSS   = require('gulp-minify-css');
const uglify      = require('gulp-uglify');
const zip         = require('gulp-zip');
const fs          = require('fs');
const xml2js      = require('xml2js');
const parser      = new xml2js.Parser();
const path        = require('path');
const replace     = require('gulp-replace');

module.exports.addModule = function (name) {
	const baseTask  = 'modules.frontend.' + name;
	const extPath   = '../component/modules/frontend/' + name;
	const mediaPath = extPath + '/media';
	const assetsPath = '../assets/components/modules/' + name;

	function compileLessFile(src, destinationFolder, options) {
		return gulp.src(src)
			.pipe(less(options))
			.pipe(gulp.dest(mediaPath + '/' + destinationFolder))
			.pipe(minifyCSS())
			.pipe(rename(function (path) {
				path.basename += '.min';
			}))
			.pipe(gulp.dest(mediaPath + '/' + destinationFolder));
	}

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

	// Less
	gulp.task('less:' + baseTask, function () {
		return compileLessFile(
			[
				assetsPath + '/less/**/*.less'
			],
			'css',
			{paths: [path.join(assetsPath, 'less')]}
		);
	});

	// Watch
	gulp.task('watch:' + baseTask,
		[
			'watch:' + baseTask + ':module',
			'watch:' + baseTask + ':media',
			'watch:' + baseTask + ':less'
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

	// Watch: Module
	gulp.task('watch:' + baseTask + ':less', function() {
		gulp.watch([
			assetsPath + '/less/**'
		], ['less:' + baseTask]);
	});

	// Update site xml
	gulp.task('update-sites:' + baseTask, function(){
		fs.readFile( extPath + '/' + name + '.xml', function(err, data) {
			parser.parseString(data, function (err, result) {
				const version = result.extension.version[0];
				gulp.src(['./update_server_xml/' + name + '.xml'])
					.pipe(replace(/<version>(.*)<\/version>/g, "<version>" + version + "</version>"))
					.pipe(gulp.dest('./update_server_xml'));
			});
		});
	});

	gulp.task('insert-update-site:' + baseTask, function(){
		fs.readFile( extPath + '/' + name + '.xml', function(err, data) {
			parser.parseString(data, function (err, result) {
				if (result.extension.updateservers)
				{
					return;
				}

				var json = result;
				result.updateservers = [{}];
				result.updateservers[0] = {server : { $: { id: "1000202" }} };

				var builder = new xml2js.Builder();
				var xml = builder.buildObject(result);

				console.log(xml);
			});
		});
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
