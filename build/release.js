const gulp        = require('gulp');
const config      = require('./config.js');
const extension   = require('./package.json');

const requireDir  = require('require-dir');
const zip         = require('gulp-zip');
const xml2js      = require('xml2js');
const fs          = require('fs');
const path        = require('path');
const del         = require('del');
const replace     = require('gulp-replace');
const filter      = require('gulp-filter');
const merge       = require('merge-stream');

const parser      = new xml2js.Parser();

const jgulp   = requireDir('./node_modules/joomla-gulp', {recurse: true});
const redcore = requireDir('./redCORE/build/gulp-redcore', {recurse: true});
const dir = requireDir('./joomla-gulp-extensions', {recurse: true});

const gitversion = require('./gitversion');

// Override of the release script
gulp.task('release',
	[
		'release:tracks',
		'release:plugins',
		'release:modules'
	], function() {
		fs.readFile( '../component/tracks.xml', function(err, data) {
			parser.parseString(data, function (err, result) {
				const fileName = config.skipVersion ? extension.name + '_ALL_UNZIP_FIRST.zip' : extension.name + '-v' + gitversion + '_ALL_UNZIP_FIRST.zip';
				del.sync(path.join(config.release_dir, fileName), {force: true});

				// We will output where release package is going so it is easier to find
				console.log('Creating all in one release file in: ' + path.join(config.release_dir, fileName));
				return gulp.src([
					config.release_dir + '/**',
					'!' + fileName
				])
					.pipe(zip(fileName))
					.pipe(gulp.dest(config.release_dir));
			});
		});
	}
);

gulp.task('release:tracks', ['clean:release', 'release:prepare-tracks', 'release:prepare-redcore'], function (cb) {
	fs.readFile( '../component/tracks.xml', function(err, data) {
		parser.parseString(data, function (err, result) {
			const fileName = config.skipVersion ? extension.name + '.zip' : extension.name + '-v' + gitversion + '.zip';
			const fileNameNoRedcore = config.skipVersion ? extension.name + '_no_redCORE.zip' : extension.name + '-v' + gitversion + '_no_redCORE.zip';

			// We will output where release package is going so it is easier to find
			console.log('Creating new release file in: ' + path.join(config.release_dir, fileName));
			gulp.src('./tmp/**/*')
				.pipe(zip(fileName))
				.pipe(gulp.dest(config.release_dir))
				.on('end', function(){
					gulp.src(['./tmp/**/*', '!./tmp/redCORE{,/**}'])
						.pipe(zip(fileNameNoRedcore))
						.pipe(gulp.dest(config.release_dir))
						.on('end', function(){
							del(['tmp']);
							cb();
						});
				});
		});
	});
});

gulp.task('release:prepare-tracks', function () {
	const xmlFilter = filter('../**/tracks.xml', {restore: true});
	return gulp.src([
		'../component/**/*'
	])
		.pipe(xmlFilter)
		.pipe(replace(/(##VERSION##)/g, gitversion))
		.pipe(xmlFilter.restore)
		.pipe(gulp.dest('tmp'));
});

gulp.task('release:prepare-redcore', function () {
	return gulp.src([
		'./redCORE/extensions/**/*'
	])
		.pipe(gulp.dest('tmp/redCORE'));
});

gulp.task('release:plugins',
	jgulp.src.plugins.getPluginsTasks('release:plugins')
);

gulp.task('release:modules',
	jgulp.src.modules.getModulesTasks('release:modules', 'frontend')
);

gulp.task('prepare:release', ['clean:release']);

gulp.task('clean:release', function(){
	return del(config.release_dir, {force: true});
});

function getVersion(xml) {
	if (config.gitVersion && gitversion) {
		return gitversion;
	}
	else {
		return xml.extension.version[0];
	}
}
