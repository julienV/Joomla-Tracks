var base = require('../basecomponentplugin');
var path = require('path');

var name = path.basename(__filename).replace('.js', '');
var group = path.basename(path.dirname(__filename));

base.addPlugin(group, name);
