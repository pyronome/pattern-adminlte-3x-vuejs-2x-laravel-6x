<template>
    <div>
        <section>
            <layout-editor :pagename="pagename" :page_widgets="children"></layout-editor>
        </section>

        <section>
            <div class="row">
                <div v-for="(child, index) in children" :key="index" :class="child.grid_class">
                    <div class="widget-header" v-show="child.db_data.enabled" style="text-align:right">
                        <widget-header :instance_id="child.instance_id"></widget-header>
                    </div>
                    <div class="widget-body">
                        <elements :element="child"></elements>
                    </div>
                </div>
            </div>
        </section>

        <body-loader :body_loader_active="body_loader_active" class="content-wrapper bodyLoader"></body-loader>
        
        <input type="hidden" id="widgets_form_data">
    </div>
</template>

<script>

export default {
    data() {
        return {
            children: [],
            main_folder: '',
            page: {
                is_ready: false,
                has_server_error: false,
                is_active_widgets_loading: false,
                is_active_widgets_loaded: false
            },
            active_widgets: [],
            body_loader_active: false
        };
    },
    props: ['pagename'],
    watch: {
        pagename: function () {
            var self = this;
            self.body_loader_active = true;
            self.main_folder = AdminLTEHelper.getMainFolder();
            self.page.is_ready = false;
            self.processLoadQueue();
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
                    child["grid_class"] = page_widget.enabled ? self.getWidgetGridClass(page_widget["grid_size"]) : "";
                    child["content"] = JSON.parse(page_widget["meta_data_json"]);

                    self.children.push(child);
                }
            });

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
        }
    },
    mounted() {
        
    }
}
</script>
