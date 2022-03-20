<template>
    <div>
        <div class="widget-inner-container">
            <div :style="data.content.css">
                <h1>{{data.content.text}}</h1>
                <file-content 
                    :fileIds="data.content.dosya" 
                    image_style="width:100px;height:100px;display:inline-block;padding:10px;"
                    file_button_style="font-size:20px;font-weight:bold;text-decoration:none;color:#0069d9;">
                </file-content>
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