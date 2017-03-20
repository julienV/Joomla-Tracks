const gulp        = require('gulp');

const fs          = require('fs');
const path        = require('path');
const replace     = require('gulp-replace');
const merge       = require('merge-stream');

gulp.task('langxml', function() {
	const langPath = '../languages';

	const folders = fs.readdirSync(langPath)
		.map(function(file) {
			return path.join(langPath, file);
		});

	// We need to combine streams so we can know when this task is actually done
	folders.map(function(directory) {
			fs.readFile('language_install_template.xml', 'utf-8', function(err, content){
				const text = content
					.replace(/(##LANG##)/g, path.basename(directory));
				fs.writeFileSync(path.join(directory, 'install.xml'), text);
			});
	});
});
