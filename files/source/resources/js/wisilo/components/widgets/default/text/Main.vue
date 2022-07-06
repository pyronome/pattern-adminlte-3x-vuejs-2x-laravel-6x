<template>
    <div>
        <div class="widget-inner-container">
            <div v-html="categoryTitle"></div>
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
                categoryTitle: ""
            };
        },
        methods: {
            refresh: function () {
                this.data = window.mainLayoutInstance.pageWidgets[this.instance_id].data;
            }
        },
        mounted() {
            var self = this;

            window.mainLayoutInstance.pageWidgets[self.instance_id].main = self;
            
            window.__custom_variables.setCustomVariableValues(self.instance_id, 
                function () {
                    self.categoryTitle = window.__custom_variables.replaceCustomVariables(self.data.content.text);
                }
            );
        }
    }
</script>