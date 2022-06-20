import defaultRoutes from './routes-default';
import customRoutes from './routes-custom';

var defaultRouteKeys = Object.keys(defaultRoutes);
var defaultRouteKeyCount = defaultRouteKeys.length;

for (var i = 0; i < defaultRouteKeyCount; i++) {
    if (undefined !== customRoutes[defaultRouteKeys[i]]) {
        defaultRoutes[defaultRouteKeys[i]] = customRoutes[defaultRouteKeys[i]];
    }
}

var customRouteKeys = Object.keys(customRoutes);
var customRouteKeyCount = customRouteKeys.length;

for (var i = 0; i < customRouteKeyCount; i++) {
    if (undefined !== defaultRoutes[defaultRouteKeys[i]]) {
        defaultRoutes[customRouteKeys[i]] = customRoutes[customRouteKeys[i]];
    }
}

var mainFolder = document.body.getAttribute("data-main-folder");
if (!mainFolder) {
    mainFolder = "wisilo";
}

var routes = [];
defaultRouteKeys = Object.keys(defaultRoutes);
defaultRouteKeyCount = defaultRouteKeys.length;
var temp = {};

for (var i = 0; i < defaultRouteKeyCount; i++) {
    temp = {};
    temp["path"] = "/" + mainFolder + defaultRouteKeys[i];
    temp["component"] = defaultRoutes[defaultRouteKeys[i]];
    routes.push(temp);
}

export var Routes = routes;