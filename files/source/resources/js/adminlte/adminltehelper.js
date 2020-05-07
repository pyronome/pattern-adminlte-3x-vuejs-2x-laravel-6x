module.exports = {
    "initializeSideMenu": function () {
        alert(window.Router.currentRoute.path);

        var strPageURL = document.body.getAttribute("data-page-url");
        if (null === strPageURL) {
            throw(new Error("document.body needs \"data-page-url\" attribute."));
            return false;
        }

        if (("login" == strPageURL)
                || ("forgotpassword" == strPageURL)
                || "profile" == strPageURL) {
            return;
        }

        var PageURL = document.getElementById("pageurl" + strPageURL);
        if (null === PageURL) {
            window.location.href = URLPrefix + "home";
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
    }
}