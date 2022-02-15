<template>
    <div>
        <section>
            <layout-editor :pagename="pagename" :page_widgets="children"></layout-editor>
        </section>

        <section class="container-fluid">
            <div class="row" id="divWidgetContainer">
                <div v-for="(child, index) in children" :key="index" 
                    :class="'widget-container ' + widget_admin_class + ' ' + child.grid_class" 
                    :id="'container-' + child.instance_id">
                    <div :class="'widget-header ' + widget_admin_class" v-show="child.db_data.enabled">
                        <widget-header :instance_id="child.instance_id"></widget-header>
                    </div>
                    <div class="widget-body">
                        <elements :element="child"></elements>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12 col-md-12 col-xs-12">
                    <button type="button"
                        id="btnSaveWidgets"
                        @click="saveWidgets"
                        class="btn btn-success btn-md btn-on-card float-right sticky-btn d-none">
                        {{ $t('Save Layout') }}
                    </button>
                </div>
            </div> 
        </section>

        <body-loader :body_loader_active="body_loader_active" class="content-wrapper bodyLoader"></body-loader>
        
        <input type="hidden" id="widgets_form_data">
        <input type="hidden" id="new_widgets" @click="newWidgets">
    </div>
</template>

<script>

export default {
    data() {
        return {
            children: [],
            main_folder: '',
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
            self.processLoadQueue();
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
        initializePage: function () {
            var self = this;
            var page_widgets = self.active_widgets;
            page_widgets.forEach(page_widget => {
                let widgetname = page_widget["widget"];
                if (null !== window.Widgets[widgetname]) {
                    let child = {};
                    let instance_id = AdminLTEHelper.generateGUID("widget");

                    child["instance_id"] = instance_id;
                    child["widget"] = window.Widgets[widgetname];
                    child["db_data"] = page_widget;
                    child["grid_class"] = page_widget.enabled ? self.getWidgetGridClass(page_widget["grid_size"]) : "widget-disabled";
                    child["content"] = JSON.parse(page_widget["meta_data_json"]);

                    self.children.push(child);
                }
            });

            $("#divWidgetContainer").sortable({
                handle: ".btn-move-widget",
                cancel: '',
                change: function( event, ui ) {
                    $("#btnSaveWidgets").removeClass("d-none");
                }
            }).disableSelection();

            self.setWidgetsFormData();
        },
        setWidgetsFormData: function() {
            var self = this;
            var widgets_form_data = {};
            
            self.children.forEach(child => {
                let instance_id = child.instance_id;

                let general_data = {
                    "enabled" : child.db_data.enabled,
                    "__order" : child.db_data.__order,
                    "title" : child.db_data.title,
	                "widget" : child.widget.name,
	                "grid_size" : child.db_data.grid_size,
	                "icon" : child.db_data.icon
                };

                let content_data = child.content;

                let widget_form_data = {
                    "general_data" : general_data,
                    "content_data" : content_data
                }

                widgets_form_data[instance_id] = widget_form_data;
            });

            $("#widgets_form_data").data("widgets_form_data", widgets_form_data);
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
                                /* console.log("posted") */
                                window.location.reload()
                            }
                        });
                    }
                }
            );
        },
        getLayoutData: function() {
            var widgets = [];
            var widgets_form_data = $("#widgets_form_data").data("widgets_form_data");
            var sortedIds = $("#divWidgetContainer").sortable("toArray");
            sortedIds.forEach(item => {
                let instance_id = item.replace("container-", "");
                widgets.push(widgets_form_data[instance_id]);
            });

            return widgets;
        },
        newWidgets: function() {
            var default_db_data = {
                "enabled" : 1,
                "__order" : 0,
                "title" : "",
                "widget" : "",
                "grid_size" : "12,12,12",
                "icon" : ""
            };

            var self = this;
            var new_widgets = document.getElementById("new_widgets").value.split(",");
            new_widgets.forEach(new_widget => {
                if (null !== window.Widgets[new_widget]) {
                    let winWidget = window.Widgets[new_widget];
                    let child = {};
                    let instance_id = AdminLTEHelper.generateGUID("widget");

                    child["instance_id"] = instance_id;
                    child["widget"] = winWidget;

                    default_db_data["title"] = winWidget.title;
                    default_db_data["widget"] = winWidget.name;
                    default_db_data["grid_size"] = winWidget.grid_size;
                    default_db_data["icon"] = winWidget.icon;

                    child["db_data"] = default_db_data;
                    child["grid_class"] = self.getWidgetGridClass(winWidget.grid_size);
                    child["content"] = winWidget.metadata;

                    self.children.push(child);
                }
            });

            $("#divWidgetContainer").sortable({
                handle: ".btn-move-widget",
                cancel: '',
                change: function( event, ui ) {
                    $("#btnSaveWidgets").removeClass("d-none");
                }
            }).disableSelection();

            self.setWidgetsFormData();
        }
    },
    mounted() {
        
    }
}
</script>
