var gulp = require('gulp');

var config = require('../../config.js');

// Dependencies
var browserSync = require('browser-sync');
var concat      = require('gulp-concat');
var del         = require('del');
var fs          = require('fs');
var less        = require('gulp-less');
var minifyCSS   = require('gulp-minify-css');
var rename      = require('gulp-rename');
var symlink     = require('gulp-symlink');
var sass        = require('gulp-ruby-sass');
var uglify      = require('gulp-uglify');
var zip         = require('gulp-zip');
var replace     = require('gulp-replace');
var xml2js      = require('xml2js');
var parser      = new xml2js.Parser();

var baseTask  = 'components.tracks';
var extPath   = '../component';
var updateXmlPath = '../../update_server_xml/';
var assetsPath = '../assets/components/tracks';
var mediaPath = extPath + '/media/com_tracks';
var wwwMediaPath = config.wwwDir + '/media/com_tracks';
var pluginsPath = extPath + '/plugins';

// Clean
gulp.task('clean:' + baseTask,
	[
		'clean:' + baseTask + ':frontend',
		'clean:' + baseTask + ':backend',
		'clean:' + baseTask + ':media',
		'clean:' + baseTask + ':plugins'
	]
);

// Clean: frontend
gulp.task('clean:' + baseTask + ':frontend', function() {
	return del(config.wwwDir + '/components/com_tracks', {force : true});
});

// Clean: backend
gulp.task('clean:' + baseTask + ':backend', function() {
	return del(config.wwwDir + '/administrator/components/com_tracks', {force : true});
});

// Clean: media
gulp.task('clean:' + baseTask + ':media', function() {
	return del(config.wwwDir + '/media/com_tracks', {force : true});
});

// Clean: plugins
gulp.task('clean:' + baseTask + ':plugins', function() {
	return del(config.wwwDir + '/plugins/tracks_projecttype/default', {force : true});
});

// Copy
gulp.task('copy:' + baseTask,
	[
		'copy:' + baseTask + ':frontend',
		'copy:' + baseTask + ':backend',
		'copy:' + baseTask + ':media',
		'copy:' + baseTask + ':plugins'
	]
);

// Copy: frontend
gulp.task('copy:' + baseTask + ':frontend', ['clean:' + baseTask + ':frontend'], function() {
	console.log(extPath + '/site/**');
	return gulp.src(extPath + '/site/**')
		.pipe(gulp.dest(config.wwwDir + '/components/com_tracks'));
});

// Copy: backend
gulp.task('copy:' + baseTask + ':backend', ['clean:' + baseTask + ':backend'], function(cb) {
	return (
		gulp.src([
			extPath + '/admin/**'
		])
		.pipe(gulp.dest(config.wwwDir + '/administrator/components/com_tracks')) &&
		gulp.src(extPath + '/tracks.xml')
		.pipe(gulp.dest(config.wwwDir + '/administrator/components/com_tracks')) &&
		gulp.src(extPath + '/install.php')
		.pipe(gulp.dest(config.wwwDir + '/administrator/components/com_tracks'))
	);
});

// Copy: media
gulp.task('copy:' + baseTask + ':media', ['clean:' + baseTask + ':media'], function() {
	return gulp.src(mediaPath + '/**')
		.pipe(gulp.dest(config.wwwDir + '/media/com_tracks'));
});

// Copy: plugins
gulp.task('copy:' + baseTask + ':plugins', ['clean:' + baseTask + ':plugins'], function() {
	return gulp.src(pluginsPath + '/**')
		.pipe(gulp.dest(config.wwwDir + '/plugins'));
});


function compileLessFile(src, destinationFolder, options) {
	return gulp.src(src)
		.pipe(less(options))
		.pipe(gulp.dest(mediaPath + '/' + destinationFolder))
		.pipe(gulp.dest(wwwMediaPath + '/' + destinationFolder))
		.pipe(minifyCSS())
		.pipe(rename(function (path) {
			path.basename += '.min';
		}))
		.pipe(gulp.dest(mediaPath + '/' + destinationFolder))
		.pipe(gulp.dest(wwwMediaPath + '/' + destinationFolder))
		.pipe(browserSync.reload({stream: true}));
}

// Less
gulp.task('less:' + baseTask, function () {
	return compileLessFile(
		[
			assetsPath + '/less/tracks.less',
			assetsPath + '/less/tracksbackend.less'
		],
		'css',
		{paths: ['../assets/components/tracks/less']}
	);
});

function compileScripts(src, ouputFileName, destinationFolder) {
	return gulp.src(src)
		.pipe(concat(ouputFileName))
		.pipe(gulp.dest(mediaPath + '/' + destinationFolder))
		.pipe(gulp.dest(wwwMediaPath + '/' + destinationFolder))
		.pipe(uglify().on('error', gutil.log))
		.pipe(rename(function (path) {
			path.basename += '.min';
		}))
		.pipe(gulp.dest(mediaPath + '/' + destinationFolder))
		.pipe(gulp.dest(wwwMediaPath + '/' + destinationFolder))
		.pipe(browserSync.reload({stream: true}));
}

// Watch
gulp.task('watch:' + baseTask,
	[
		'watch:' + baseTask + ':frontend',
		'watch:' + baseTask + ':backend',
		'watch:' + baseTask + ':plugins',
		'watch:' + baseTask + ':media',
		//'watch:' + baseTask + ':scripts',
		'watch:' + baseTask + ':less'
	]
);

// Watch: frontend
gulp.task('watch:' + baseTask + ':frontend', function() {
	return gulp.watch(extPath + '/site/**',
	['copy:' + baseTask + ':frontend']);
});

// Watch: backend
gulp.task('watch:' + baseTask + ':backend', function() {
	return gulp.watch([
		extPath + '/admin/**',
		extPath + '/tracks.xml',
		extPath + '/install.php'
	],
	['copy:' + baseTask + ':backend']);
});

// Watch: plugins
gulp.task('watch:' + baseTask + ':plugins', function() {
	return gulp.watch(extPath + '/plugins/**',
		['copy:' + baseTask + ':plugins']);
});

// Watch: plugins
gulp.task('watch:' + baseTask + ':media', function() {
	return gulp.watch(extPath + '/media/**',
		['copy:' + baseTask + ':media']);
});

// Watch: plugins
gulp.task('watch:' + baseTask + ':less', function() {
	return gulp.watch(assetsPath + '/less/**',
		['less:' + baseTask]);
});

gulp.task('update-sites:' + baseTask, function(){
	fs.readFile( extPath + '/tracks.xml', function(err, data) {
		parser.parseString(data, function (err, result) {
			const version = result.extension.version[0];
			gulp.src(['./update_server_xml/com_tracks.xml'])
				.pipe(replace(/<version>(.*)<\/version>/g, "<version>" + version + "</version>"))
				.pipe(gulp.dest('./update_server_xml'));
		});
	});
});
