const gulp    = require('gulp');
const config  = require('./config.js');

// Release
const release = require('./release.js');

// Tests
const phpcs = require('gulp-phpcs');
gulp.task('phpcs', function () {
	return gulp.src(['../component/**/*.php', '../modules/**/*.php', '../plugins/**/*.php', '!../**/tmpl/**/*', '!../**/layouts/**/*'])
		.pipe(phpcs({
			standard: 'Joomla',
			warningSeverity: 0
		}))
		.pipe(phpcs.reporter('log'));
});
