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
		'release:modules',
		'release:languages'
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

gulp.task('release:languages', ['prepare:release'], function() {
	const langPath = '../languages';
	const releaseDir = path.join(config.release_dir, 'tracks_languages_UNZIP_FIRST');

	const folders = fs.readdirSync(langPath)
		.map(function(file) {
			return path.join(langPath, file);
		})
		.filter(function(file) {
			return fs.existsSync(path.join(file, 'install.xml'));
		});

	// We need to combine streams so we can know when this task is actually done
	return merge(folders.map(function(directory) {
			const data = fs.readFileSync(path.join(directory, 'install.xml'));

			// xml2js parseString is sync, but must be called using callbacks... hence this awkwards vars
			// see https://github.com/Leonidas-from-XIV/node-xml2js/issues/159
			var task;
			var error;

			parser.parseString(data, function (err, result) {
				if (err) {
					error = err;
					console.log(err);

					return;
				}

				const lang = path.basename(directory);
				const version = result.extension.version[0];
				const fileName = config.skipVersion ? extension.name + '_' + lang + '.zip' : extension.name + '_' + lang + '-v' + version + '.zip';

				task = gulp.src([directory + '/**'])
					.pipe(zip(fileName))
					.pipe(gulp.dest(releaseDir));
			});

			if (error) {
				throw error;
			}

			if (!error && !task) {
				throw new Error('xml2js callback became suddenly async or something.');
			}

			return task;
		})
	);
});

function getVersion(xml) {
	if (config.gitVersion && gitversion) {
		return gitversion;
	}
	else {
		return xml.extension.version[0];
	}
}
