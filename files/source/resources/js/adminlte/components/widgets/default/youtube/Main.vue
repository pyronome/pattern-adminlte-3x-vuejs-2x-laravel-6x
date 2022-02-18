<template>
    <div>
        <div class="widget-inner-container">
            <iframe :width="data.content.width" height="315"
                 :src="data.content.youtubecode" 
                 title="YouTube video player" 
                 frameborder="0" 
                 allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen>
            </iframe>
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
                this.data = $(document.getElementById("container-" + this.instance_id)).data("widget_data");
            }
        },
        mounted() {
            window.mainLayoutInstance.widgetMainComponents[this.instance_id] = this;
        }
    }
</script>