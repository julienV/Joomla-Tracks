var gulp = require('gulp'),
	jsonModify = require('gulp-json-modify'),
	argv = require('yargs')
		.usage('by default, runs default task(s)')
		.help('h')
		.alias('h', 'help')
		.alias('w', 'wwwDir')
		.describe('wwwDir', 'Target joomla dir')
		.describe('testRelease', 'Output to test folder')
		.boolean('skipVersion')
		.describe('skipVersion', 'Omit version from file name')
		.boolean('release')
		.describe('release', 'Create release package')
		.boolean('rememberWww')
		.alias('r', 'rememberWww')
		.describe('rememberWww', 'Remember www target dir')
		.argv;

var config = require('./gulp-config.json');
config.extensions = require('./gulp-extensions.json');

if (argv.wwwDir)
{
	config.wwwDir = argv.wwwDir;

	if (argv.rememberWww)
	{
		gulp.src([ './gulp-config.json' ])
			.pipe(jsonModify({
				key: 'wwwDir',
				value: argv.wwwDir
			}))
			.pipe(gulp.dest('./'))
	}
}

if (argv.testRelease)
{
	config.release_dir = config.testrelease_dir;
}

config.skipVersion = argv.skipVersion ? 1 : 0;

module.exports = config;
