<template>
    <div :id="container_guid" 
        :class="'widget-container-element widget-container widget-editable ' + container_class" 
        :style="container_css" 
        :parent_instance_id="parent_instance_id"
        :data-container-guid="container_guid"
        :container-index="container_index">
        <div class="widget-main-container">
            <div class="widget-header widget-editable">
                <div class="widget-header-wrapper" :parent_instance_id="parent_instance_id">
                    <div class="widget-header-text" style="cursor:default;margin-left:10px">{{container_title}}</div>
                    <div class="widget-addnew-button-container">
                        <button type="button" class="btn btn-flat btn-xs" aria-expanded="false"
                            @click="showWidgetList()">
                            <i class="fas fa-plus" aria-hidden="true"></i> {{ $t('Add Widget(s)') }}
                        </button>
                    </div>
                </div>
            </div>
            <div class="widget-body">
                <div class="row divContainerContainer" :id="'container-' + container_guid">
                    <div v-for="(pageWidget, index) in pageWidgets" :key="index" 
                        :class="'widget-container ' + widget_admin_class + ' ' + pageWidget.grid_class" 
                        :id="'container-' + pageWidget.instance_id"
                        :data-instance-id="pageWidget.instance_id"
                        :data-container-guid="container_guid">
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
            </div>
        </div>
    </div>
</template>

<script>

export default {
    data() {
        return {
            pageWidgets: [],
            main_folder: "",
            widget_admin_class:"",
            is_admin: false,
            page: {
                is_ready: false,
                has_server_error: false,
                is_active_widgets_loading: false,
                is_active_widgets_loaded: false
            },
            active_widgets: [],
            body_loader_active: false,
            layoutForm: new Form({
                'pagename': '',
                'layoutdata': [],
            }),
            container_id: "",
            container_guid: WisiloHelper.generateGUID("wc"),
            container_index: 0,
        };
    },
    props: ["pagename", "parent_instance_id", "container_title", "container_class", "container_css", "external_data", "repeater"],   
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

            /*
            $("#btnAddNewWidgets").off("click").on("click", function () {
                $(".select_widget").prop("checked", false);
                $("#modalWidgetList").modal();
            });*/

            var activeWidgets = self.active_widgets;
            activeWidgets.forEach(activeWidget => {
                let widgetname = activeWidget["widget"];
                if (null !== window.Widgets[widgetname]) {
                    let instance_id = WisiloHelper.generateGUID("widget");
                    window.mainLayoutInstance.pageWidgets[instance_id] = [];

                    let child = {
                        "instance_id": instance_id,
                        "container_guid": self.container_guid,
                        "widget": window.Widgets[widgetname],
                        "data": {
                            "instance_id": instance_id,
                            "parent_instance_id": self.parent_instance_id,
                            "container_index": self.container_index,
                            "container_title": "",
                            "general": activeWidget,
                            "content": JSON.parse(activeWidget["meta_data_json"]),
                            "data_source": ("" == activeWidget["data_source_json"]) ? [] : JSON.parse(activeWidget["data_source_json"]),
                            "variable_mapping": ("" == activeWidget["variable_mapping_json"]) ? [] : JSON.parse(activeWidget["variable_mapping_json"])
                        },
                        "grid_class": self.getWidgetGridClass(activeWidget["grid_size"]) + " " + (activeWidget.enabled ? "" : " widget-disabled"),
                    }

                    self.pageWidgets.push(child);
                }
            });
            
            var widgetContainerElement = document.getElementById("container-" + self.container_guid)
            $(widgetContainerElement).sortable({
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
        getActiveWidgets: function () {
            var self = this;

            if (self.page.is_active_widgets_loading) {
                return;
            }

            if (0 == self.pagename) {
                console.log("pagename 0 return")
                return
            }

            if (self.repeater) {
                self.container_index = 0;
            } else {
                self.container_index = self.findContainerIndex();
            }
            
            self.container_id = (self.pagename + "-" + self.container_index);

            self.page.is_active_widgets_loading = true;

            axios.get(WisiloHelper.getAPIURL("__layout/get_widgets/" + self.container_id))
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
        getLayoutData: function() {
            var layoutData = [];
            
            var widgetContainers = $("#divWidgetContainer > .widget-container");
            for (let index = 0; index < widgetContainers.length; index++) {
                let instance_id = widgetContainers[index].getAttribute("data-instance-id");
                layoutData.push(window.mainLayoutInstance.pageWidgets[instance_id].data);
            }

            return layoutData;
        },
        showWidgetList: function() {
            $(".select_widget").prop("checked", false);
            document.getElementById("buttonSaveSelectedWidgets").setAttribute("parent-instance-id", this.parent_instance_id);
            document.getElementById("buttonSaveSelectedWidgets").setAttribute("container-guid", this.container_guid);
            $("#modalWidgetList").modal();
        },
        addNewWidgets: function(selectedWidgets) {
            var self = this;
            var parent_instance_id = this.parent_instance_id;
            var container_index = this.container_index;

            for (let index = 0; index < selectedWidgets.length; index++) {
                let widgetData = selectedWidgets[index];
                widgetData["parent_instance_id"] = parent_instance_id;
                widgetData["container_index"] = container_index;
                widgetData["container_title"] = self.container_title;

                let widgetname = widgetData.general.widget;

                if (null !== window.Widgets[widgetname]) {
                    let winWidget = window.Widgets[widgetname];
                    
                    let instance_id = WisiloHelper.generateGUID("widget");
                    window.mainLayoutInstance.pageWidgets[instance_id] = [];

                    widgetData["instance_id"] = instance_id;

                    let child = {
                        "instance_id": instance_id,
                        "container_guid": self.container_guid,
                        /* "parent_instance_id": self.parent_instance_id, */
                        "widget": winWidget,
                        "data": widgetData,
                        "grid_class": self.getWidgetGridClass(widgetData.general.grid_size)
                    }

                    self.pageWidgets.push(child);
                }
            }

            var widgetContainerElement = document.getElementById("container-" + self.container_guid)
            $(widgetContainerElement).sortable({
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
                "container_guid": self.container_guid,
                "widget": winWidget,
                "data": {
                    "instance_id": instance_id,
                    "parent_instance_id": self.parent_instance_id,
                    "container_index": self.container_index,
                    "container_title": self.container_title,
                    "general": general_data,
                    "content": copy_data.content,
                    "data_source": copy_data.data_source,
                    "variable_mapping": copy_data.variable_mapping
                },
                "grid_class": self.getWidgetGridClass(general_data.grid_size)
            };

            self.pageWidgets.push(child);

            var widgetContainerElement = document.getElementById("container-" + self.container_guid)
            $(widgetContainerElement).sortable({
                handle: ".widget-move-handle",
                cancel: '',
                change: function( event, ui ) {
                    $("#btnSaveWidgets").removeClass("btn-default").addClass("btn-success");
                }
            }).disableSelection();

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
        },
        findContainerIndex: function() {
            var self = this;
            var ownerWidgetElement = document.getElementById("container-" + self.parent_instance_id);
            var widgetContainers = $(".widget-container-element", ownerWidgetElement);
            var container = null;

            for (let index = 0; index < widgetContainers.length; index++) {
                container = widgetContainers[index];

                if (self.container_guid == container.getAttribute("data-container-guid")) {
                    return index;
                }
            }

            return 0;
        }
    },
    mounted() {
        if (1 == document.getElementById("is_current_user_admin").value) {
            this.widget_admin_class = "widget-editable";
            this.container_index = this.findContainerIndex();
        }

        window.mainLayoutInstance.widgetContainers[this.container_guid] = this;

        this.processLoadQueue();
    }
}
</script>
