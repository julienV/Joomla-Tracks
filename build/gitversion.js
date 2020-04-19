const gitversion = require('child_process').execSync('git describe', { encoding: 'utf8' }).toString().split('\n').join('');

module.exports = gitversion;
