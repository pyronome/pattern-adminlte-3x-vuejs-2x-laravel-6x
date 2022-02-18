<template>
    <div>
        <section>
            <layout-editor :pagename="pagename"></layout-editor>
        </section>

        <section class="container-fluid mt-3">
            <div class="row" id="divWidgetContainer">
                <div v-for="(pageWidget, index) in pageWidgets" :key="index" 
                    :class="'widget-container ' + widget_admin_class + ' ' + pageWidget.grid_class" 
                    :id="'container-' + pageWidget.instance_id">
                    <div class="widget-main-container">
                        <div :class="'widget-header ' + widget_admin_class">
                            <widget-header 
                                :instance_id="pageWidget.instance_id" 
                                :data="pageWidget.data">
                            </widget-header>
                        </div>
                        <div class="widget-body">
                            <elements :element="pageWidget"></elements>
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
                    ("/js/adminlte/bootstrap-switch/js/bootstrap-switch.js"),
                    ("/js/adminlte/bootstrap-iconpicker/css/bootstrap-iconpicker.min.css"),
                    ("/js/adminlte/bootstrap-iconpicker/js/iconset/fontawesome5-3-1.min.js"),
                    ("/js/adminlte/bootstrap-iconpicker/js/bootstrap-iconpicker.min.js"),
                    ("/js/adminlte/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css"),
                    ("/js/adminlte/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"),
                    ("/js/adminlte/select2/dist/js/select2.min.js"),
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
            self.main_folder = AdminLTEHelper.getMainFolder();
            self.page.is_ready = false;
            AdminLTEHelper.loadExternalFiles(self.page.external_files, self.processLoadQueue());
        },
        pagevariables: function (pagevariables) {
            var self = this;
            self.is_admin = pagevariables.is_admin;

            if (self.is_admin) {
                self.widget_admin_class = "widget-editable";
            }
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
                $("#modalWidgetList").modal();
            });

            $("#btnSaveWidgets").off("click").on("click", function () {
                self.saveWidgets();
            });

            var activeWidgets = self.active_widgets;
            activeWidgets.forEach(activeWidget => {
                let widgetname = activeWidget["widget"];
                if (null !== window.Widgets[widgetname]) {
                    let child = {};
                    let instance_id = AdminLTEHelper.generateGUID("widget");

                    child["instance_id"] = instance_id;
                    child["widget"] = window.Widgets[widgetname];
                    child["data"] = {
                        "general": activeWidget,
                        "content": JSON.parse(activeWidget["meta_data_json"])
                    }

                    child["grid_class"] = self.getWidgetGridClass(activeWidget["grid_size"]) + " " + (activeWidget.enabled ? "" : " widget-disabled");

                    self.pageWidgets.push(child);
                }
            });

            $("#divWidgetContainer").sortable({
                handle: ".btn-move-widget",
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
                btn.setAttribute("on-edit-mode", 1);
                $("#btnToggleEditMode").removeClass("btn-default").addClass("btn-primary");

                $(".show-on-edit-mode").removeClass("d-none")
                $(".widget-editable").addClass("widget-edit-mode")

            } else {
                btn.setAttribute("on-edit-mode", 0);
                $("#btnToggleEditMode").addClass("btn-default").removeClass("btn-primary");

                $(".show-on-edit-mode").addClass("d-none")
                $(".widget-editable").removeClass("widget-edit-mode")
            }
        },
        getActiveWidgets: function () {
            var self = this;

            if (self.page.is_active_widgets_loading) {
                return;
            }

            self.page.is_active_widgets_loading = true;

            axios.get(AdminLTEHelper.getAPIURL("__layout/get_widgets/" + self.pagename))
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
                $(document.getElementById("container-" + instance_id)).data("widget_data", child.data);
            });
        },
        saveWidgets: function () {
            var self = this;
            self.$Progress.start();
            self.layoutForm.layoutdata = self.getLayoutData();
            
            self.layoutForm.post(AdminLTEHelper.getAPIURL("__layout/post_layout"))
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
            
            var widgetContainers = $("#divWidgetContainer > .widget-container");
            for (let index = 0; index < widgetContainers.length; index++) {
                const container = widgetContainers[index];
                layoutData.push($(container).data("widget_data"));
            }

            return layoutData;
        },
        addNewWidgets: function(selectedWidgets) {
            var default_general_data = {
                "enabled" : 1,
                "__order" : 0,
                "title" : "",
                "widget" : "",
                "grid_size" : "12,12,12",
                "icon" : ""
            };

            var self = this;
            var new_widgets = selectedWidgets.split(",");

            new_widgets.forEach(new_widget => {
                if (null !== window.Widgets[new_widget]) {
                    let winWidget = window.Widgets[new_widget];
                    let child = {};
                    let instance_id = AdminLTEHelper.generateGUID("widget");

                    child["instance_id"] = instance_id;
                    child["widget"] = winWidget;

                    let general_data = default_general_data;
                    general_data["title"] = winWidget.title;
                    general_data["widget"] = winWidget.name;
                    general_data["grid_size"] = winWidget.grid_size;
                    general_data["icon"] = winWidget.icon;

                    child["data"] = {
                        "general": general_data,
                        "content": winWidget.metadata
                    }

                    child["grid_class"] = self.getWidgetGridClass(winWidget.grid_size);

                    self.pageWidgets.push(child);
                }
            });

            $("#divWidgetContainer").sortable({
                handle: ".btn-move-widget",
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
        copyWidget: function(copy_data) {
            var self = this;

            let winWidget = window.Widgets[copy_data.general.widget];
            let child = {};
            let instance_id = AdminLTEHelper.generateGUID("widget");

            child["instance_id"] = instance_id;
            child["widget"] = winWidget;
            child["data"] = copy_data;
            child["grid_class"] = self.getWidgetGridClass(copy_data.general.grid_size);

            self.pageWidgets.push(child);

            self.body_loader_active = true;

            setTimeout(function(){
                self.setWidgetsFormData();

                $(".widget-editable").addClass("widget-edit-mode")

                $("html, body").animate({ scrollTop: $(document).height() }, 1500);

                self.body_loader_active = false;
            }, 500);
        },
        refreshWidget: function(instance_id) {
            var self = this;
            
            // General
            var widgetContainer = document.getElementById("container-" + instance_id);
            var data = $(widgetContainer).data("widget_data");

            var grid_class = self.getWidgetGridClass(data.general.grid_size) + " " + (data.general.enabled ? "" : " widget-disabled");
            widgetContainer.className = "widget-container widget-editable " + grid_class + " widget-edit-mode";

            document.getElementById(instance_id + "-header-text").innerHTML = data.general.title;

            // Content
            window.mainLayoutInstance.widgetMainComponents[instance_id].refresh();
        }
    },
    mounted() {
        window.mainLayoutInstance = {};
        window.mainLayoutInstance.vueComponent = this;
        window.mainLayoutInstance.settingsComponents = [];
        window.mainLayoutInstance.widgetMainComponents = [];
        window.mainLayoutInstance.widgetSettingComponents = [];
    }
}
</script>
