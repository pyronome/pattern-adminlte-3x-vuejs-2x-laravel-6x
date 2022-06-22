import filebox from './filebox/Main.vue';
import spacer from './spacer/Main.vue';
import infobox from './infobox/Main.vue';
import recordlist from './recordlist/Main.vue';
import youtubeVideo from './youtube/Main.vue';
import container from './container/Main.vue';
import productlist from './productlist/Main.vue';

export default {
    "container": {
        "component": container,
        "enabled": true,
        "name": "container",
        "title": "Container",
        "has_preview": false,
        "grid_size": "12,12,12",
        "icon": "",
        "description": "",
        "metadata" : {
            "css" : ""
        }
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
        }
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
        }
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
            "redirectURL": "home",
            "calculation_type" : "simple",
            "function" : "",
            "model" : "",
            "property" : "",
            "query" : "",
        }
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
        }
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
        }
    },
    "productlist": {
        "component": productlist,
        "enabled": true,
        "name": "productlist",
        "title": "Product List",
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
        }
    },
};