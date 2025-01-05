var resolve = require('json-refs').resolveRefs;
var YAML = require('yaml-js');
var fs = require('fs');
var jsonToYaml = require('json2yaml');
var path = require('path');


var root = YAML.load(fs.readFileSync(path.resolve(__dirname, 'index.yaml')).toString());
var options = {
    filter: ['relative', 'remote'],
    loaderOptions: {
        processContent: function (res, callback) {
            callback(null, YAML.load(res.text));
        }
    }
};

resolve(root, options)
    .then(function (result) {
        if (result.resolved.paths && result.resolved.paths.map) {
            var all = {};
            result.resolved.paths.map(function (item) {
                for (var i in item) {
                    all[i] = item[i];
                }
            });
            result.resolved.paths = all;
        }

        return result;
    })
    .then(function (results) {
        fs.writeFileSync(__dirname + '/../swagger/swagger.json', JSON.stringify(results.resolved, null, 2));
        fs.writeFileSync(__dirname + '/../swagger/swagger.yaml', jsonToYaml.stringify(results.resolved, null, 2));
    });
