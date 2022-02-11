import defaultWidgets from './default/widgets.js';
import customWidgets from './custom/widgets.js';

var defaultWidgetsKeys = Object.keys(defaultWidgets);
var defaultWidgetsKeyCount = defaultWidgetsKeys.length;

for (var i = 0; i < defaultWidgetsKeyCount; i++) {
    if (undefined !== customWidgets[defaultWidgetsKeys[i]]) {
        defaultWidgets[defaultWidgetsKeys[i]] = customWidgets[defaultWidgetsKeys[i]];
    }
}

var customWidgetsKeys = Object.keys(customWidgets);
var customWidgetsKeyCount = customWidgetsKeys.length;

for (var i = 0; i < customWidgetsKeyCount; i++) {
    if (undefined !== defaultWidgets[defaultWidgetsKeys[i]]) {
        defaultWidgets[customWidgetsKeys[i]] = customWidgets[customWidgetsKeys[i]];
    }
}

defaultWidgetsKeys = Object.keys(defaultWidgets);
defaultWidgetsKeyCount = defaultWidgetsKeys.length;

var widgets = {};

for (var i = 0; i < defaultWidgetsKeyCount; i++) {
    widgets[defaultWidgetsKeys[i]] = defaultWidgets[defaultWidgetsKeys[i]];
}

export var Widgets = widgets;