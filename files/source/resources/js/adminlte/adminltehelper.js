var AdminLTEHelper = {
    "__externalFiles": [],
    "__externalFilesCompletedCallback": null,
    "initializeApplication": function () {

    },
    "initializeSideMenu": function () {
        $(".main-sidebar .active").removeClass("active");

        var mainFolder = document.body.getAttribute("data-main-folder");
        var strPageURL = window.Router.currentRoute.path;

        strPageURL = strPageURL.replace(("/" + mainFolder), "");

        if ((strPageURL.length > 0)
                && (strPageURL[0] == '/')) {
            strPageURL = strPageURL.substr(1);
        }

        if ("" == strPageURL) {
            return;
        }

        if (("login" == strPageURL)
                || ("forgotpassword" == strPageURL)
                || "profile" == strPageURL) {
            return;
        }

        var PageURL = document.getElementById("pageurl" + strPageURL);
        if (PageURL == undefined) {
            return;
        }

        if ($(PageURL).hasClass("child_menu")) {
            var strParentPageURL = PageURL.getAttribute("data-parent-url");
            if (null === document.getElementById("parentpageurl" + strParentPageURL)) {
                window.location.href = URLPrefix + "home";
            }

            if ("" != strParentPageURL) {
                $(".parentPageLI").removeClass("menu-open").addClass("menu-close");
                $("#parentpageurl" + strParentPageURL).addClass("active");
                $("#parentpageurl" + strParentPageURL).parent().removeClass("menu-close").addClass("menu-open");
            }
        }	

        $(PageURL).addClass("active");
    },
    "initializeFormElements": function () {

    },
	"loadCSS": function (url) {
        if(document.createStyleSheet) {
            document.createStyleSheet(url);
        } else {
            var c=document.createElement("link");
            c.href = url;
            c.rel = "stylesheet";
            c.type = "text/css";
            document.getElementsByTagName("head")[0].appendChild(c);
        }
    },
	"loadJS": function (url, callback) {
        $.getScript(url, function() {
            if (callback) {
                callback(url);
            }
        });
    },
    "getMainFolder": function () {
        if (document.body.getAttribute("data-main-folder")) {
            return document.body.getAttribute("data-main-folder");
        } else {
            return "";
        }
    },
    "getURL": function (url) {
        return ("/" + AdminLTEHelper.getMainFolder() + "/" + url);
    },
    "getAPIURL": function (url) {
        return ("/" + AdminLTEHelper.getMainFolder() + "/api/" + url);
    },
    "doVueApplicationMounted": function () {
        AdminLTEHelper.initializeSideMenu();
        AdminLTEHelper.initializeFormElements();
    },
    "doVueApplicationUpdate": function () {
        AdminLTEHelper.initializeSideMenu();
        AdminLTEHelper.initializeFormElements();
    },
    "doVueMenuApplicationMounted": function () {

    },
    "doVueMenuApplicationUpdate": function () {

    },
    "loadExternalFiles": function (files, completedCallback) {
        var fileCount = files.length;
        AdminLTEHelper.__externalFiles = files.slice();
        AdminLTEHelper.__externalFilesCompletedCallback = completedCallback;
        var file = "";
        var extension = "";

        for (var i = 0; i < fileCount; i++) {
            file = files[i];
            extension = file.split('.').pop();
            extension = extension.toLowerCase();

            if ("css" == extension) {
                AdminLTEHelper.loadCSS(file);
                AdminLTEHelper.doExternalFileLoad(file);
            } else {
                AdminLTEHelper.loadJS(file,
                        AdminLTEHelper.doExternalFileLoad);
            }
        }
    },
    "decodeHTMLEntities": function (text) {
        var element = document.createElement('div');

        if(text && typeof text === 'string') {
            // strip script/html tags
            text = text.replace(/<script[^>]*>([\S\s]*?)<\/script>/gmi, "");
            text = text.replace(/<\/?\w(?:[^"'>]|"[^"]*"|'[^']*')*>/gmi, "");
            element.innerHTML = text;
            text = element.textContent;
            element.textContent = "";
        }

        return text;
    },
    "encodeHTMLEntities": function (text) {
        text = String(text);

        var map = {
            "&": "&amp;",
            "<": "&lt;",
            ">": "&gt;",
            '"': "&quot;",
            "'": "&#039;"
        };

        return text.replace(/[&<>"']/g, function(m) {
            return map[m];
        });
    },
    "doExternalFileLoad": function (file) {
        var fileIndex = AdminLTEHelper.__externalFiles.indexOf(file);
        if (fileIndex > -1) {
            AdminLTEHelper.__externalFiles.splice(
                    fileIndex,
                    1);
        }

        if (0 == AdminLTEHelper.__externalFiles.length) {
            if (AdminLTEHelper.__externalFilesCompletedCallback) {
                AdminLTEHelper.__externalFilesCompletedCallback();
            }
        }
    },
}

module.exports = AdminLTEHelper;