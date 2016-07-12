var gulp = require('gulp'),
	argv = require('yargs').argv;

var config = require('./gulp-config.json');
config.extensions = require('./gulp-extensions.json');

if (argv.wwwDir)
{
	config.wwwDir = argv.wwwDir;
}

if (argv.testRelease)
{
	config.release_dir = config.testrelease_dir;
}

config.skipVersion = argv.skipVersion ? 1 : 0;

module.exports = config;
