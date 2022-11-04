import defaultWidgets from './default/widgets.js';
import customWidgets from './custom/widgets.js';

var widgets = {};

var defaultWidgetsKeys = Object.keys(defaultWidgets);
var defaultWidgetsKeyCount = defaultWidgetsKeys.length;

for (var i = 0; i < defaultWidgetsKeyCount; i++) {
    let key = defaultWidgetsKeys[i];
    if (undefined === customWidgets[key]) {
        widgets[key] = defaultWidgets[key];
    }
}

var customWidgetsKeys = Object.keys(customWidgets);
var customWidgetsKeyCount = customWidgetsKeys.length;

for (var i = 0; i < customWidgetsKeyCount; i++) {
    let key = customWidgetsKeys[i];
    if (undefined === defaultWidgets[key]) {
        widgets[key] = customWidgets[key];
    }
}

export var Widgets = widgets;