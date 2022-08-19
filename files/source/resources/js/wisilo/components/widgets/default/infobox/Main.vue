<template>
    <div class="widgetcomponent">
        <div class="widget-inner-container">
            <router-link class="info-box clickable-infobox" tag="div" :to="content.redirectURL">
                <span class="info-box-icon elevation-1" v-bind:style="{backgroundColor: content.iconbackground}"><i :class="content.icon"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text" v-html="content.title"></span>
                    <span class="info-box-number" v-html="content.infobox_value"></span>
                </div>
            </router-link>
        </div>
        <div class="widget-settings-dialog-container">
            <settingsDialog :instance_id="instance_id"></settingsDialog>
        </div>
        <div class="widget-preview-dialog-container">
            <previewDialog widget_name="infobox"></previewDialog>
        </div>
    </div>
</template>

<script>
    import settingsDialog from "./Settings.vue";
    import previewDialog from "./Preview.vue";

    export default {
        components: {settingsDialog, previewDialog},
        props: ["instance_id","data"],
        data() {
            return {
                "content": this.data.content,
                "dependant_customvariables": []
            };
        },
        methods: {
            refresh: function () {
                this.data = window.mainLayoutInstance.pageWidgets[this.instance_id].data;
            },
            loadData: function () {
                if (undefined === this.data.general.id) {
                    return;
                }

                var parameters = WisiloHelper.getWidgetParameter(this.data.general.id, "");
                axios.get(WisiloHelper.getAPIURL("__layout/get_infobox_data/" + parameters))
                    .then(({ data }) => {
                        if (data) {
                            if (data.infobox_data)
                                this.content = data.infobox_data;

                            if (data.dependant_customvariables)
                                this.dependant_customvariables = data.dependant_customvariables;
                        }

                        this.$Progress.finish();
                    }).catch(({ data }) => {
                        this.$Progress.fail();
                    });
            }
        },
        mounted() {
            this.loadData();
            window.mainLayoutInstance.pageWidgets[this.instance_id].main = this;
        }
    }
</script>