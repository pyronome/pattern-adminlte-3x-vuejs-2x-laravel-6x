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
        "grid_size": "12,12,12",
        "icon": "",
        "description": "",
        "metadata" : {
            "model" : "",
            "title" : "",
            "icon" : "fas fa-cog",
            "iconbackground" : "#17a2b8",
            "redirectURL": "",
        },
    },
    "youtubeVideo": {
        "component": youtubeVideo,
        "name": "youtubeVideo",
        "title": "Youtube Video",
        "grid_size": "12,12,12",
        "icon": "",
        "description": "",
        "metadata" : {
            "youtubecode" : "",
            "width" : 560,
        },
    },
};