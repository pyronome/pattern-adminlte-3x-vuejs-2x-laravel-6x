var AdminLTEHelper = {
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
    "getMainFolder": function () {
        if (element.getAttribute("data-main-folder")) {
            return element.getAttribute("data-main-folder");
        } else {
            return "";
        }
    },
    "doRouterLinkClick": function (sender) {
        AdminLTEHelper.initializeSideMenu();
    }
}

module.exports = AdminLTEHelper;