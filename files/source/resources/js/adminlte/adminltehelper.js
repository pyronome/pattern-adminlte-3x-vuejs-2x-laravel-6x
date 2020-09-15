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
    "getCSRFToken": function () {
        return $('meta[name="csrf-token"]').attr('content');
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
    "initializeMediaJS": function() {
        AdminLTEHelper.initializeDropzone();
        
        $(".buttonBrowseFile").off("click").on("click", function () {
            AdminLTEHelper.doBrowseButtonClick(this);
        });
    
        $("#modelEditPage_fileTemplate").on("htmldbrender", function (event) {
            AdminLTEHelper.doFileTemplateRender(this, event);
        });
    
        $("#modelEditPage_imageTemplate").on("htmldbrender", function (event) {
            AdminLTEHelper.doFileTemplateRender(this, event);
        });
    },    
    "initializeDropzone": function() {
        if (!document.getElementById("dropzone-data")) {
            return;
        }
        
        var uploadURL = document.getElementById("dropzone-data").getAttribute("data-action");
        uploadURL = (uploadURL + "?_token=" + AdminLTEHelper.getCSRFToken());
        
        var dropzoneElements = $("div.divDropzone");
        var dropzoneElement = null;
        var dropzoneElementCount = dropzoneElements.length;
        var dropzoneObject = null;
    
        for (var i = 0; i < dropzoneElementCount; i++) {
            dropzoneElement = dropzoneElements[i];
            dropzoneObject = new Dropzone(dropzoneElement, {
                "url": uploadURL,
                "complete": function (fileObject) {
                    var responseObject = JSON.parse(String(fileObject.xhr.responseText).trim());
                    if (responseObject.errorCount > 0) {      
                        AdminLTEHelper.showErrorDialog(responseObject.lastError);
                    } else {
                        AdminLTEHelper.selectUploadedFiles(responseObject.lastMessage);
                    }
                }
            });
    
            dropzoneObject.on("sending", function(file, xhr, formData) {
                formData.append("target", document.getElementById("dropzone-data").getAttribute("data-target-field"));
                formData.append("media_type", document.getElementById("dropzone-data").getAttribute("data-media-type"));
            });
        }
    },    
     "doFileTemplateRende": function(sender, event) {
        var targets = event.detail.targets;
        var target = null;
        var targetCount = targets.length;
    
        if (0 == targetCount) {
            return;
        }
    
        for (var i = 0; i < targetCount; i++) {
            target = document.getElementById(targets[i]);
    
            if (!target) {
                return;
            }
    
            if (target.children.length > 0) {
                $(".aDeleteFileListItem", target).off("click").on("click", function () {
                    AdminLTEHelper.doDeleteFileListItemLinkClick(this);
                });
    
                $(target).sortable({
                        items: "li",
                        handle: ".grippy",
                        update: function (event, ui) {
                            AdminLTEHelper.updateFileListInput(this);
                        }
                });
                
                $(target).disableSelection();
    
                target.style.display = "block";
            }
        }
    },
    "selectUploadedFiles": function (file_data) {
        var fileData = file_data.split("#");
        var fileId = fileData[0];
        var fileName = fileData[1];
        var filePath = fileData[2];
        
        var mediaType = 1;
        var imageFileExtensions = ["jpg", "jpeg", "png", "gif"];
        var fileExtension = String(fileName.split('.').pop()).toLowerCase();
        if (imageFileExtensions.indexOf(fileExtension) != -1) {
            mediaType = 2;
        } else {
            mediaType = 1;
        }
    
        var targetFileListId = document.getElementById("dropzone-data").getAttribute("data-target-file-list");
        var targetFileList = document.getElementById(targetFileListId);
        var inputElement = document.getElementById(targetFileList.getAttribute("data-target-input-id"));
        var mediaType = inputElement.getAttribute("data-media-type");
        var maxFileCount = inputElement.getAttribute("data-max-file-count");
        var currentValue = inputElement.value;
        var currentFileCount = 0;
        if ("" != currentValue) {
            var temp = currentValue.split(",");
            currentFileCount = temp.length;
        }
    
        if ((maxFileCount > 0) && (maxFileCount == currentFileCount)) {
            return false;
        }
    
        var innerElement = document.createElement('li');
        innerElement.id ="liFileListItem" + fileId;
        innerElement.className ="collection-item liMediaType" + mediaType;
        innerElement.setAttribute('data-object-id', fileId);
        innerElement.setAttribute('data-file-name', fileName);
        innerElement.setAttribute('data-file-path', filePath);
        innerElement.setAttribute('data-media-type', mediaType);
    
        var templateHTML = document.getElementById("ulFileListTemplate").innerHTML;
        templateHTML = templateHTML.replace(/__ID__/g, fileId);
        templateHTML = templateHTML.replace(/__MEDIA_TYPE__/g, mediaType);
        templateHTML = templateHTML.replace(/__FILE_NAME__/g, fileName);
        innerElement.innerHTML = templateHTML;
    
        targetFileList.appendChild(innerElement);
    
        AdminLTEHelper.updateFileListUL(targetFileList);
        AdminLTEHelper.updateFileListInput(targetFileList);
    },
    "updateFileListUL": function (targetFileList) {
        var imageFileExtensions = ["jpg", "jpeg", "png", "gif"];
        var elementLIList = $(">li", targetFileList);
        var elementLIListCount = elementLIList.length;
        var elementLI = null;
        var fileName = "";
        var filePath = "";
        var fileExtension = "";
        var imageFile = false;
        var src = "";
    
        $(".aDeleteFileListItem", targetFileList).off("click").on("click", function () {
            AdminLTEHelper.doDeleteFileListItemLinkClick(this);
        });
    
        for (var i = 0; i < elementLIListCount; i++) {
            elementLI = elementLIList[i];
            fileName = elementLI.getAttribute("data-file-name");
            filePath = elementLI.getAttribute("data-file-path");
            fileExtension = String(fileName.split('.').pop()).toLowerCase();
            
            if (imageFileExtensions.indexOf(fileExtension) != -1) {
                src = (__storageURL + filePath);
            } else {
                src = AdminLTEHelper.getDefaultImageSRC(fileExtension);
            }
    
            $(".imgFileListItemFileURL", elementLI).attr("src", src);
            $(".aFileListItemFileURL", elementLI).attr("href", (__storageURL + filePath));
        }
    
        if (elementLIListCount > 0) {
            targetFileList.style.display = "block";
            $(targetFileList).sortable({
                    items: "li",
                    handle: ".grippy",
                    update: function (event, ui) {
                        AdminLTEHelper.updateFileListInput(this);
                    }
            });
            
            $(targetFileList).disableSelection();
        } else {
            targetFileList.style.display = "none";
        }
    },
    "getDefaultImageSRC": function (extension) {
        return  (__publicAssetsURL + "img/adminlte/" + extension + ".png");
    },    
    "doDeleteFileListItemLinkClick": function(element) {
        if (!element) {
            return;
        }
    
        var elementUL = element.parentNode.parentNode;
    
        $(element.parentNode).detach();
    
        if (0 == $(">li", elementUL).length) {
            elementUL.style.display = "none";
        }
    
        AdminLTEHelper.updateFileListInput(elementUL);
    },
    "updateFileListInputs": function() {
    
        var fileLists = $("#formObject .ulFileList");
        var fileListCount = fileLists.length;
    
        for (var i = 0; i < fileListCount; i++) {
            AdminLTEHelper.updateFileListInput(fileLists[i].getAttribute("id"));
        }
    
    },
    "updateFileListInput": function(targetFileList) {
        var targetFileListInputId = targetFileList.getAttribute("data-target-input-id");
        var targetFileListInput = document.getElementById(targetFileListInputId);
    
        targetFileListInput.value = "";
    
        var targetFileListItems = $(">li", targetFileList);
        var targetFileListItemCount = targetFileListItems.length;
        var targetFileListItem = null;
        var fileListInputValue = "";
    
        for (var i = 0; i < targetFileListItemCount; i++) {
            targetFileListItem = targetFileListItems[i];
    
            if (fileListInputValue != "") {
                fileListInputValue += ",";
            }
    
            fileListInputValue += targetFileListItem.getAttribute("data-object-id");
        }
    
        targetFileListInput.value = fileListInputValue;
    },
    "doBrowseButtonClick": function(sender) {
        if (!sender) {
            return;
        }
        
        var targetFileListElementId = sender.getAttribute("data-target-file-list");
        var targetFileListElement = document.getElementById(targetFileListElementId);
    
        var targetInputElementId = targetFileListElement.getAttribute("data-target-input-id");
        var targetInputElement = document.getElementById(targetInputElementId)
        var targetField = targetInputElement.getAttribute("data-target-field");
        var mediaType = targetInputElement.getAttribute("data-media-type");
    
        document.getElementById("dropzone-data").setAttribute("data-target-file-list", targetFileListElement.id);
        document.getElementById("dropzone-data").setAttribute("data-target-field", targetField);
        document.getElementById("dropzone-data").setAttribute("data-media-type", mediaType);
    
        $("#divDropzone").trigger("click");
    },
    "humanFileSize": function(bytes, si) {
        var thresh = si ? 1000 : 1024;
        if(Math.abs(bytes) < thresh) {
            return bytes + ' B';
        }
        var units = si
        ? ['kB','MB','GB','TB','PB','EB','ZB','YB']
        : ['KiB','MiB','GiB','TiB','PiB','EiB','ZiB','YiB'];
        var u = -1;
        do {
            bytes /= thresh;
            ++u;
        } while(Math.abs(bytes) >= thresh && u < units.length - 1);
        return bytes.toFixed(1)+' '+units[u];
    },
    "generateGUID": function(strPrefix) {
        var dtNow = new Date();
        var strToken0 = String(dtNow.getUTCFullYear())
        + String((dtNow.getUTCMonth() + 1))
        + String(dtNow.getUTCDate())
        + String(dtNow.getHours())
        + String(dtNow.getMinutes())
        + String(dtNow.getSeconds());
        var strToken1 = 'xxxx'.replace(/[xy]/g, function(c) {var r = Math.random()*16|0,v=c=='x'?r:r&0x3|0x8;return v.toString(16);});
        var strToken2 = 'xxxx'.replace(/[xy]/g, function(c) {var r = Math.random()*16|0,v=c=='x'?r:r&0x3|0x8;return v.toString(16);});  
        return strPrefix + strToken0 + strToken1 + strToken2;
    },
    "updateLocationPicker": function(sender) {
        var senderId = sender.id;
        var currentLatitude = 41.015;
        var currentLongitude = 28.979;
        var mapElement = document.getElementById(senderId + "-MapObject");

        if (sender.value != "") {
            coordinates = String(sender.value).split(",");
            if (2 == coordinates.length) {
                currentLatitude = parseFloat(coordinates[0]);
                currentLongitude = parseFloat(coordinates[1]);
            }
        }

        var tmRefreshTimer = $(mapElement).data("tmRefreshTimer");
        clearTimeout(tmRefreshTimer);
        tmRefreshTimer = setTimeout(function () {
            google.maps.event.trigger(mapElement, "resize");

            document.getElementById(senderId + "-Latitude").value = currentLatitude;
            document.getElementById(senderId + "-Longitude").value = currentLongitude;

            $(mapElement).locationpicker({
                location: {
                    latitude: currentLatitude,
                    longitude: currentLongitude
                },
                locationName: "",
                radius: 500,
                zoom: 15,
                mapTypeId: google.maps.MapTypeId.ROADMAP,
                styles: [],
                mapOptions: {},
                scrollwheel: true,
                inputBinding: {
                    latitudeInput: $(document.getElementById(senderId+ "-Latitude")),
                    longitudeInput: $(document.getElementById(senderId+ "-Longitude")),
                    radiusInput: null,
                    locationNameInput: $(document.getElementById(senderId+ "-Address"))
                },
                enableAutocomplete: true,
                enableAutocompleteBlur: false,
                autocompleteOptions: null,
                addressFormat: 'postal_code',
                enableReverseGeocode: true,
                draggable: true,
                onchanged: function (currentLocation, radius, isMarkerDropped) {
                    sender.value = currentLocation.latitude + "," + currentLocation.longitude;
                },
                onlocationnotfound: function(locationName) {},
                oninitialized: function (component) {},
                // must be undefined to use the default gMaps marker
                markerIcon: undefined,
                markerDraggable: true,
                markerVisible : true
            });
        }, 100);

        $(mapElement).data("tmRefreshTimer", tmRefreshTimer);
    },
    "showGoogleMap": function(sender) {
        var lat = parseFloat(sender.getAttribute("data-lat"));
        var lng = parseFloat(sender.getAttribute("data-lng"));
        var place = {lat: lat, lng: lng};

        // The map, centered at Uluru
        var map = new google.maps.Map(sender, {zoom: 16, center: place});

        // The marker, positioned at Uluru
        var marker = new google.maps.Marker({position: place, map: map});
    },
    "initializeTextEditor": function() {
        $(".textarea.vue-editor").summernote({
            "font-styles": false,
            "height": 150,
            codemirror: {
                theme: "monokai"
            }
        });
        
        // editor içeriği yenilenmiyorsa aşağıdaki denenebilir
        /*$(sender).summernote({
            "font-styles": false,
            "height": 150,
            codemirror: {
                theme: "monokai"
            }
        });

        $(sender).summernote("code", event.detail.value);*/
    },
    "updateSwitch": function(sender) {
        $(sender).bootstrapSwitch("state", sender.checked);
        $(".fake-switch-container").hide();
    }
}

module.exports = AdminLTEHelper;