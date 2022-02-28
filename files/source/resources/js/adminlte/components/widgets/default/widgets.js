import widgetA from './a/Main.vue';
import widgetB from './b/Main.vue';
import spacer from './spacer/Main.vue';
import infobox from './infobox/Main.vue';
import youtubeVideo from './youtube/Main.vue';

export default {
    "widgetA": {
        "component": widgetA,
        "name": "widgetA",
        "title": "Widget A",
        "has_preview": true,
        "grid_size": "12,12,12",
        "icon": "",
        "description": "",
        "metadata" : {
            "css" : "padding:20px,background:white",
            "text" : "New A",
        },
    },
    "widgetB": {
        "component": widgetB,
        "name": "widgetB",
        "title": "Widget B",
        "has_preview": false,
        "grid_size": "12,12,12",
        "icon": "",
        "description": "",
        "metadata" : {
            "css" : "padding:20px,background:white",
            "text" : "New B",
        },
    },
    "spacer": {
        "component": spacer,
        "name": "spacer",
        "title": "Spacer",
        "has_preview": false,
        "grid_size": "12,12,12",
        "icon": "",
        "description": "",
        "metadata" : {
            "css" : "",
        },
    },
    "infobox": {
        "component": infobox,
        "name": "infobox",
        "title": "Infobox",
        "has_preview": true,
        "grid_size": "12,12,12",
        "icon": "",
        "description": "",
        "metadata" : {
            "title" : "",
            "icon" : "fas fa-cog",
            "iconbackground" : "#17a2b8",
            "redirectURL": "",
            "calculation_type" : "simple",
            "function" : "",
            "model" : "",
            "property" : "",
            "query" : "",
        },
    },
    "youtubeVideo": {
        "component": youtubeVideo,
        "name": "youtubeVideo",
        "title": "Youtube Video",
        "has_preview": true,
        "grid_size": "12,12,12",
        "icon": "",
        "description": "",
        "metadata" : {
            "youtubecode" : "",
            "width" : 560,
        },
    },
};