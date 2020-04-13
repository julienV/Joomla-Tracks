var basecomponentplugin = require('../basecomponentplugin');
var path = require('path');

var name = path.basename(__filename).replace('.js', '');
var group = path.basename(path.dirname(__filename));

basecomponentplugin.addPlugin(group, name);
