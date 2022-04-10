import widgetA from './a/Main.vue';
import filebox from './filebox/Main.vue';
import spacer from './spacer/Main.vue';
import infobox from './infobox/Main.vue';
import recordlist from './recordlist/Main.vue';
import youtubeVideo from './youtube/Main.vue';

export default {
    "widgetA": {
        "component": widgetA,
        "enabled": true,
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
    "filebox": {
        "component": filebox,
        "enabled": true,
        "name": "filebox",
        "title": "Filebox",
        "has_preview": false,
        "grid_size": "12,12,12",
        "icon": "",
        "description": "",
        "metadata" : {
            "css" : "padding:20px,background:white",
            "text" : "Files",
        },
    },
    "spacer": {
        "component": spacer,
        "enabled": true,
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
        "enabled": true,
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
    "recordlist": {
        "component": recordlist,
        "enabled": true,
        "name": "recordlist",
        "title": "Record List",
        "has_preview": true,
        "grid_size": "12,12,12",
        "icon": "",
        "description": "",
        "metadata" : {
            "columns" : [],
            "calculation_type" : "simple",
            "function" : "",
            "model" : "",
            "property" : "",
            "query" : "",
        },
    },
    "youtubeVideo": {
        "component": youtubeVideo,
        "enabled": true,
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