<template>
    <div>
        <section>
            <widget-list :pagename="pagename"></widget-list>
        </section>

        <section class="container-fluid mt-3">
            <div class="row" id="divWidgetContainer">
                <div v-for="(pageWidget, index) in pageWidgets" :key="index" 
                    :class="'widget-container ' + widget_admin_class + ' ' + pageWidget.grid_class" 
                    :id="'container-' + pageWidget.instance_id"
                    :data-instance-id="pageWidget.instance_id">
                    <div class="widget-main-container">
                        <div :class="'widget-header ' + widget_admin_class">
                            <widget-header 
                                :instance_id="pageWidget.instance_id" 
                                :data="pageWidget.data"
                                :parent_instance_id="pageWidget.data.parent_instance_id">
                            </widget-header>
                        </div>
                        <div class="widget-body">
                            <widget-body :element="pageWidget"></widget-body>
                        </div>
                    </div>
                </div>
            </div>
        </section>
                
        <body-loader :body_loader_active="body_loader_active" class="content-wrapper bodyLoader"></body-loader>
    </div>
</template>

<script>

export default {
    data() {
        return {
            pageWidgets: [],
            main_folder: '',
            widget_admin_class:"",
            is_admin: false,
            page: {
                is_ready: false,
                has_server_error: false,
                is_active_widgets_loading: false,
                is_active_widgets_loaded: false,
                external_files: [
                    ("/js/wisilo/bootstrap-switch/js/bootstrap-switch.js"),
                    ("/js/wisilo/bootstrap-iconpicker/css/bootstrap-iconpicker.min.css"),
                    ("/js/wisilo/bootstrap-iconpicker/js/iconset/fontawesome5-3-1.min.js"),
                    ("/js/wisilo/bootstrap-iconpicker/js/bootstrap-iconpicker.min.js"),
                    ("/js/wisilo/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css"),
                    ("/js/wisilo/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"),
                    ("/js/wisilo/select2/dist/js/select2.min.js"),
                ],
            },
            active_widgets: [],
            body_loader_active: false,
            layoutForm: new Form({
                'pagename': '',
                'layoutdata': [],
            }),
        };
    },
    props: ['pagename', "pagevariables"],
    watch: {
        pagename: function (pagename) {
            var self = this;
            self.layoutForm.pagename = pagename;
            self.body_loader_active = true;
            self.main_folder = WisiloHelper.getMainFolder();
            self.page.is_ready = false;
            WisiloHelper.loadExternalFiles(self.page.external_files, self.processLoadQueue());
        }
    },
    
    methods: {
        processLoadQueue: function () {
            var self = this;

            if (self.page.has_server_error) {
                self.$Progress.finish();
                self.page.is_ready = true;
                return;
            }

            if (!self.page.is_active_widgets_loaded) {
                self.$Progress.start();
                self.getActiveWidgets();
            } else {
                self.$nextTick(function () {

                    setTimeout(function() {
                        self.initializePage();
                        self.body_loader_active = false;
                    }, 500);                        
                });
                
                self.$Progress.finish();
                self.page.is_ready = true;
            }
        },
        initializePage: function () {
            var self = this;

            $("#btnToggleEditMode").off('click').on('click', function () {
                self.doToggleEditModeChange(this);
            });

            $("#btnAddNewWidgets").off("click").on("click", function () {
                $(".select_widget").prop("checked", false);
                document.getElementById("buttonSaveSelectedWidgets").setAttribute("parent-instance-id", "");
                document.getElementById("buttonSaveSelectedWidgets").setAttribute("container-guid", "");
                $("#modalWidgetList").modal();
            });

            $("#btnSaveWidgets").off("click").on("click", function () {
                self.saveWidgets();
            });

            var activeWidgets = self.active_widgets;

            activeWidgets.forEach(activeWidget => {
                let widgetname = activeWidget["widget"];
                if (null !== window.Widgets[widgetname]) {
                    let instance_id = WisiloHelper.generateGUID("widget");
                    window.mainLayoutInstance.pageWidgets[instance_id] = [];

                    let child = {
                        "instance_id": instance_id,
                        "container_guid": "",
                        "widget": window.Widgets[widgetname],
                        "data": {
                            "instance_id": instance_id,
                            "parent_instance_id": "",
                            "container_index": "",
                            "container_title": "",
                            "general": activeWidget,
                            "content": JSON.parse(activeWidget["meta_data_json"]),
                            "data_source": ("" == activeWidget["data_source_json"]) ? [] : JSON.parse(activeWidget["data_source_json"]),
                            "variable_mapping": ("" == activeWidget["variable_mapping_json"]) ? [] : JSON.parse(activeWidget["variable_mapping_json"])
                        },
                        "grid_class": self.getWidgetGridClass(activeWidget["grid_size"]) + " " + (activeWidget.enabled ? "" : " widget-disabled")
                    }

                    self.pageWidgets.push(child);
                }
            });

            $("#divWidgetContainer").sortable({
                handle: ".widget-move-handle",
                cancel: '',
                change: function( event, ui ) {
                    $("#btnSaveWidgets").removeClass("btn-default").addClass("btn-success");
                }
            }).disableSelection();

            setTimeout(function() {
                self.setWidgetsFormData();
            }, 500);
        },
        doToggleEditModeChange: function(btn) {
            var editModeActive = btn.getAttribute("on-edit-mode");
            
            if (0 == editModeActive) {
                editModeActive = 1;
                btn.setAttribute("on-edit-mode", editModeActive);
                $("#btnToggleEditMode").removeClass("btn-default").addClass("btn-primary");

                $(".show-on-edit-mode").removeClass("d-none")
                $(".widget-editable").addClass("widget-edit-mode")

            } else {
                editModeActive = 0;
                btn.setAttribute("on-edit-mode", editModeActive);
                $("#btnToggleEditMode").addClass("btn-default").removeClass("btn-primary");

                $(".show-on-edit-mode").addClass("d-none")
                $(".widget-editable").removeClass("widget-edit-mode")
            }

            this.setWidgetContainers(editModeActive)
        },
        setWidgetContainers: function(editModeActive) {
            var active_widget_containers = [];
            var widgetContainers = $(".widget-container-element.widget-container", document.getElementById("divWidgetContainer"));
            var widgetContainer = null;
            var parent_instance_id = "";
            var widgetContainerGUID = "";

            for (let index = 0; index < widgetContainers.length; index++) {
                widgetContainer = widgetContainers[index];
                parent_instance_id = widgetContainer.getAttribute("parent_instance_id");
                widgetContainerGUID = widgetContainer.getAttribute("data-container-guid");

                if (1 == editModeActive) {
                    if (undefined ===active_widget_containers[parent_instance_id]) {
                        active_widget_containers[parent_instance_id] = widgetContainerGUID;
                    } else {
                        $(widgetContainer).addClass("d-none");
                        $(".widget-container.widget-editable", widgetContainer).addClass("d-none");
                    }
                } else {
                    $(widgetContainer).removeClass("d-none");
                    $(".widget-container.widget-editable", widgetContainer).removeClass("d-none");
                }
            }
        },
        getActiveWidgets: function () {
            var self = this;

            if (self.page.is_active_widgets_loading) {
                return;
            }

            self.page.is_active_widgets_loading = true;

            if ((undefined === self.pagename) || ('' == self.pagename) || (null === self.pagename)) {
                return;
            }

            axios.get(WisiloHelper.getAPIURL("__layout/get_widgets/" + self.pagename))
                .then(({ data }) => {
                    self.page.is_active_widgets_loaded = true;
                    self.page.is_active_widgets_loading = false;
                    self.active_widgets = data.page_widgets;
                }).catch(({ data }) => {
                    self.page.is_active_widgets_loaded = true;
                    self.page.is_active_widgets_loading = false;
                    self.$Progress.fail();
                    self.page.has_server_error = true;
                    self.processLoadQueue();
                }).finally(function() {
                   self.processLoadQueue();
                });
        },
        getWidgetGridClass: function(size) {
            var grid_class = "";

            if ("" != size) {
                var parts = size.split(",");
                grid_class = "col-lg-" + parts[0] + " col-sm-" + parts[1] + " col-" + parts[2];
            } else {
                grid_class = "col-lg-12 col-sm-12 col-12";
            }

            return grid_class;
        },
        setWidgetsFormData: function() {
            var self = this;

            self.pageWidgets.forEach(child => {
                let instance_id = child.instance_id;
                window.mainLayoutInstance.pageWidgets[instance_id].data = child.data;
            });
        },
        saveWidgets: function () {
            var self = this;
            self.$Progress.start();
            self.layoutForm.layoutdata = self.getLayoutData();

            /* console.log(self.layoutForm.layoutdata)

            return; */
            
            self.layoutForm.post(WisiloHelper.getAPIURL("__layout/post_layout"))
                .then(({ data }) => {
                    self.$Progress.finish();
                    self.page.is_post_success = true;
                }).catch(({ data }) => {
                    self.$Progress.fail();
                    self.page.is_post_success = false;
                }).finally(function() {
                    if (self.page.is_post_success) {
                        Vue.swal.fire({
                            position: 'top-end',
                            title: self.$t("Your changes have been saved!"),
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true,
                            onClose: () => {
                                window.location.reload()
                            }
                        });
                    }
                }
            );
        },
        getLayoutData: function() {
            var layoutData = [];
            
            var widgetContainers = $(".widget-container.widget-editable:not(.widget-container-element)");
            for (let index = 0; index < widgetContainers.length; index++) {
                if (widgetContainers[index].classList.contains("d-none")) {
                    continue;
                } else {
                    let instance_id = widgetContainers[index].getAttribute("data-instance-id");
                    layoutData.push(window.mainLayoutInstance.pageWidgets[instance_id].data);
                }
            }

            return layoutData;
        },
        addNewWidgets: function(selectedWidgets) {
            var self = this;

            var parent_instance_id = document.getElementById("buttonSaveSelectedWidgets").getAttribute("parent-instance-id");
            var container_guid = document.getElementById("buttonSaveSelectedWidgets").getAttribute("container-guid");
            if (("" != parent_instance_id) && ("" != container_guid)) {
                if (undefined !== window.mainLayoutInstance.widgetContainers[container_guid]) {
                    window.mainLayoutInstance.widgetContainers[container_guid].addNewWidgets(selectedWidgets);
                }

                return;
            }

            for (let index = 0; index < selectedWidgets.length; index++) {
                let widgetData = selectedWidgets[index];
                widgetData["parent_instance_id"] = parent_instance_id;
                widgetData["container_index"] = "";
                widgetData["container_title"] = "";

                let widgetname = widgetData.general.widget;

                if (null !== window.Widgets[widgetname]) {
                    let winWidget = window.Widgets[widgetname];
                    
                    let instance_id = WisiloHelper.generateGUID("widget");
                    window.mainLayoutInstance.pageWidgets[instance_id] = [];

                    widgetData["instance_id"] = instance_id;

                    let child = {
                        "instance_id": instance_id,
                        "container_guid": "",
                        "widget": winWidget,
                        "data": widgetData,
                        "grid_class": self.getWidgetGridClass(widgetData.general.grid_size)
                    }

                    self.pageWidgets.push(child);
                }
            }

            $("#divWidgetContainer").sortable({
                handle: ".widget-move-handle",
                cancel: '',
                change: function( event, ui ) {
                    $("#btnSaveWidgets").removeClass("btn-default").addClass("btn-success");
                }
            }).disableSelection();

            self.body_loader_active = true;

            setTimeout(function(){
                self.setWidgetsFormData();
                $(".widget-editable").addClass("widget-edit-mode")
                $("html, body").animate({ scrollTop: $(document).height() }, 1500);
                self.body_loader_active = false;
            }, 500);
        },
        copyWidget: function(copy_data, widgetname) {
            var parent_instance_id = "";
            if ((undefined !== copy_data.parent_instance_id) && ("" != copy_data.parent_instance_id)) {
                parent_instance_id = copy_data.parent_instance_id;
            }

            var container_index = "";
            if ((undefined !== copy_data.container_index) && ("" != copy_data.container_index)) {
                container_index = copy_data.container_index;
            }

            if (("" != parent_instance_id) && ("" != container_index)) {
                if (undefined !== window.mainLayoutInstance.widgetContainers[parent_instance_id + "-" + container_index]) {
                    window.mainLayoutInstance.widgetContainers[parent_instance_id + "-" + container_index].copyWidget(copy_data, widgetname);
                }

                return;
            }

            var self = this;

            var winWidget = window.Widgets[widgetname];
            var instance_id = WisiloHelper.generateGUID("widget");
            window.mainLayoutInstance.pageWidgets[instance_id] = [];

            let general_data = {
                "enabled" : copy_data.general.enabled,
                "__order" : 0,
                "title" : copy_data.general.title,
                "widget" : widgetname,
                "grid_size" : copy_data.general.grid_size,
                "icon" : copy_data.general.icon,
            };

            var child = {
                "instance_id": instance_id,
                "container_guid": "",
                "widget": winWidget,
                "data": {
                    "instance_id": instance_id,
                    "parent_instance_id": "",
                    "container_index": "",
                    "container_title": "",
                    "general": general_data,
                    "content": copy_data.content,
                    "data_source": copy_data.data_source,
                    "variable_mapping": copy_data.variable_mapping
                },
                "grid_class": self.getWidgetGridClass(general_data.grid_size)
            };

            self.pageWidgets.push(child);

            self.body_loader_active = true;

            setTimeout(function(){
                self.setWidgetsFormData();

                $(".widget-editable").addClass("widget-edit-mode");
                $("html, body").animate({ scrollTop: $(document).height() }, 1500);

                self.body_loader_active = false;
            }, 500);
        },
        refreshWidget: function(instance_id) {
            var self = this;

            // General
            var data = window.mainLayoutInstance.pageWidgets[instance_id].data;

            var grid_class = self.getWidgetGridClass(data.general.grid_size) + " " + (data.general.enabled ? "" : " widget-disabled");
            document.getElementById("container-" + instance_id).className = "widget-container widget-editable " + grid_class + " widget-edit-mode";

            document.getElementById(instance_id + "-header-text").innerHTML = data.general.title;

            // Content
            window.mainLayoutInstance.pageWidgets[instance_id]["main"].refresh();
        },
        showLoader: function() {
            this.body_loader_active = true;
        },
        hideLoader: function() {
            this.body_loader_active = false;
        }
    },
    mounted() {
        window.mainLayoutInstance = {};
        window.mainLayoutInstance.vueComponent = this;
        window.mainLayoutInstance.pageWidgets = [];
        window.mainLayoutInstance.widgetContainers = [];
        window.mainLayoutInstance.current_editing_widget_instance_id = 0;

        if (1 == document.getElementById("is_current_user_admin").value) {
            this.widget_admin_class = "widget-editable";
        }
    }
}
</script>
