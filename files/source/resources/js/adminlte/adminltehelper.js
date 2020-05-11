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
	"loadJS": function (url, success) {
        var s = document.createElement("script");
        s.src = url;
        var h = document.getElementsByTagName("head")[0], d=false;
        s.onload = s.onreadystatechange = function () {
            if (!d && (!this.readyState
                    || this.readyState=="loaded"
                    || this.readyState=="complete")) {
                d = true;
                if (success) {
                    success(s.src);
                }
                s.onload = s.onreadystatechange = null;
                h.removeChild(s);
            }
        }
        h.appendChild(s);
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
        AdminLTEHelper.__externalFiles = files;
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
    "doExternalFileLoad": function (file) {
        var fileIndex = AdminLTEHelper.__externalFiles.indexOf(file);
        if (fileIndex != -1) {
            AdminLTEHelper.__externalFiles.splice(fileIndex, 1);
        }

        if (0 == AdminLTEHelper.__externalFiles.length) {
            if (AdminLTEHelper.__externalFilesCompletedCallback) {
                AdminLTEHelper.__externalFilesCompletedCallback();
            }
        }
    }
}

module.exports = AdminLTEHelper;