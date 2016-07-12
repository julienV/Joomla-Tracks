const gulp = require('gulp');

const config      = require('./config.js');
const extension   = require('./package.json');
const requireDir  = require('require-dir');
const zip         = require('gulp-zip');
const xml2js      = require('xml2js');
const fs          = require('fs');
const path        = require('path');
const del         = require('del');
const exec        = require('child_process').exec;
const replace     = require('gulp-replace');
const filter      = require('gulp-filter');

const jgulp = requireDir('./node_modules/joomla-gulp', {recurse: true});
const dir = requireDir('./joomla-gulp-extensions', {recurse: true});

const parser      = new xml2js.Parser();

var gitDescribe = '';

gulp.task('prepare:release', ['clean:release', 'git_version'], function(){
	return del(config.release_dir, {force: true});
});

gulp.task('clean:release', function(){
	return del(config.release_dir, {force: true});
});

gulp.task('git_version', function(){
	return getGitDescribe(function(str) {
		gitDescribe = str;
	});
});

// Override of the release script
gulp.task('release',
	[
		'release:tracks',
		'release:modules',
		'release:plugins',
		'release:languages',
	], function() {
		fs.readFile( '../component/tracks.xml', function(err, data) {
			parser.parseString(data, function (err, result) {
				const version = gitDescribe;
				const fileName = config.skipVersion ? extension.name + '_ALL_UNZIP_FIRST.zip' : extension.name + '-v' + version + '_ALL_UNZIP_FIRST.zip';
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

gulp.task('release:tracks', ['prepare:release'], function (cb) {
	fs.readFile( '../component/tracks.xml', function(err, data) {
		parser.parseString(data, function (err, result) {
			const version = gitDescribe;
			const fileName = config.skipVersion ? extension.name + '.zip' : extension.name + '-v' + version + '.zip';
			const xmlFilter = filter(['../**/*.xml'], {restore: true});

			// We will output where release package is going so it is easier to find
			console.log('Creating new release file in: ' + path.join(config.release_dir, fileName));

			gulp.src('../component/**/*')
				.pipe(xmlFilter)
				.pipe(replace(/(##VERSION##)/g, version))
				.pipe(xmlFilter.restore)
				.pipe(zip(fileName))
				.pipe(gulp.dest(config.release_dir))
				.on('end', cb);
		});
	});
});

gulp.task('release:plugins',
	jgulp.src.plugins.getPluginsTasks('release:plugins')
);

gulp.task('release:modules',
	jgulp.src.modules.getModulesTasks('release:modules', 'frontend')
);

gulp.task('release:languages', ['prepare:release'], function() {
	const langPath = '../languages';
	const releaseDir = path.join(config.release_dir, 'language');

	const folders = fs.readdirSync(langPath)
		.map(function(file){
			return path.join(langPath, file);
		})
		.filter(function(file) {
			return fs.statSync(file).isDirectory();
		});

	const tasks = folders.map(function(directory) {
		return fs.readFile(path.join(directory, 'install.xml'), function(err, data) {
			parser.parseString(data, function (err, result) {
				const lang = path.basename(directory);
				const version = result.extension.version[0];
				const fileName = config.skipVersion ? extension.name + '_' + lang + '.zip' : extension.name + '_' + lang + '-v' + version + '.zip';

				return gulp.src([
						directory + '/**'
					])
					.pipe(zip(fileName))
					.pipe(gulp.dest(releaseDir));
			});
		});
	});

	return tasks;
});

function getGitDescribe(cb) {
	exec('git describe', function (err, stdout, stderr) {
		cb(stdout.split('\n').join(''))
	})
}
