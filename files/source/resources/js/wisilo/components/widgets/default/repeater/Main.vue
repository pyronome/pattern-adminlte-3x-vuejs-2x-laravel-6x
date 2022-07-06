<template>
    <div>
        <div class="widget-inner-container">
            <!-- <div v-html="list"></div> -->
            <div v-for="(row, row_index) in list" :key="row_index">
                <widget-container :pagename="layout_id"
                    :repeater="true"
                    container_title="PartA" 
                    :parent_instance_id="instance_id"
                    :external_data="row" 
                    container_class=""
                    container_css="">
                </widget-container>
            </div>
        </div>
        <div class="widget-settings-dialog-container">
            <settingsDialog :instance_id="instance_id"></settingsDialog>
        </div>

    </div>
</template>

<script>
    import settingsDialog from "./Settings.vue";

    export default {
        components: {settingsDialog},
        props: ["instance_id","data"],
        data() {
            return {
                layout_id: 0,
                "content": this.data.content,
                "list": [],
                "data_pagination": [],
                "dependant_customvariables": [],
                "session": [],
                external_data: [],
                container_guid: "",
            };
        },
        methods: {
            refresh: function () {
                this.data = window.mainLayoutInstance.pageWidgets[this.instance_id].data;
            },
            loadData: function () {
                var self = this;
                var parameters = WisiloHelper.getWidgetParameter(self.data.general.id, self.container_guid);
                
                axios.get(WisiloHelper.getAPIURL("__layout/get_query_result/" + parameters))
                    .then(({ data }) => {
                        if (data) {
                            self.list = data.list;
                            self.data_pagination = data.data_pagination;
                            self.dependant_customvariables = data.dependant_customvariables;
                            self.session = data.session;
                        }

                        self.$Progress.finish();
                    }).catch(({ data }) => {
                        self.$Progress.fail();
                    });
            }
        },
        mounted() {
            window.mainLayoutInstance.pageWidgets[this.instance_id].main = this;

            var layout_id = 0;

            if (undefined !== this.data
                && undefined !== this.data.general
                && undefined !== this.data.general.id) {
                layout_id = this.data.general.id;
            }

            this.layout_id = layout_id;

            var widgetContainer = document.getElementById("container-" + this.instance_id);
            var container_guid = widgetContainer.getAttribute("data-container-guid");

            if (null != container_guid) {
                this.container_guid = container_guid;
                this.external_data = window.mainLayoutInstance.widgetContainers[container_guid].external_data;
                if (Object.keys(this.external_data).length > 0) {
                    window.__custom_variables.setCustomVariableValues(this.external_data);
                }
            }
            
            this.loadData();
        }
    }
</script>