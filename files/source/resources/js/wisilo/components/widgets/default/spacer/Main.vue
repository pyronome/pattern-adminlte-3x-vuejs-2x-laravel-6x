<template>
    <div>
        <div class="widget-inner-container">
            <div :style="data.content.css"></div>
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
        methods: {
            refresh: function () {
                this.data = window.mainLayoutInstance.pageWidgets[this.instance_id].data;
            }
        },
        mounted() {
            window.mainLayoutInstance.pageWidgets[this.instance_id].main = this;
        }
    }
</script>