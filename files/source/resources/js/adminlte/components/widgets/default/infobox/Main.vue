<template>
    <div class="widgetcomponent">
        <div class="widget-inner-container">
            <router-link class="info-box clickable-infobox" tag="div" :to="data.content.redirectURL">
                <span class="info-box-icon elevation-1" v-bind:style="{backgroundColor: data.content.iconbackground}"><i :class="data.content.icon"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">{{data.content.title}}</span>
                    <span class="info-box-number">{{count}}</span>
                </div>
            </router-link>
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
                count: 0,
            };
        },
        methods: {
            refresh: function () {
                this.data = $(document.getElementById("container-" + this.instance_id)).data("widget_data");
            },
            loadData: function () {
                axios.get(AdminLTEHelper.getAPIURL("__layout/get_infoboxvalue/" + this.data.content.model))
                    .then(({ data }) => {
                        this.count = data.count;
                    }).catch(({ data }) => {
                        this.$Progress.fail();
                    });
            }
        },
        mounted() {
            this.loadData();
            window.mainLayoutInstance.widgetMainComponents[this.instance_id] = this;
        }
    }
</script>