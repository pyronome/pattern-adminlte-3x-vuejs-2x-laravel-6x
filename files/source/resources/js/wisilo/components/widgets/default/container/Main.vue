<template>
    <div>
        <div class="widget-inner-container">
            <div :style="data.content.css">
                <widget-container :pagename="layout_id"  container_title="Widget Container" :parent_instance_id="instance_id" :data="data"
                    container_class="" ></widget-container>
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
                layout_id: 0
            }
        },
        methods: {
            refresh: function () {
                this.data = window.mainLayoutInstance.pageWidgets[this.instance_id].data;
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
        }
    }
</script>