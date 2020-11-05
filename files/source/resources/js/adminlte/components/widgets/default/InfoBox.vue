<template>
    <div class="widgetcomponent">
        <router-link class="info-box clickable-infobox sbp-item" tag="div" :to="infoboxURL" :menu-permission-token="model.toLowerCase()">
            <span class="info-box-icon elevation-1" v-bind:style="{backgroundColor: data.iconbackground}"><i :class="data.icon"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">{{data.text}}</span>
                <span class="info-box-number">{{data.count}}</span>
            </div>
        </router-link>
        
    </div>
</template>

<script>
    export default {
        data() {
            return {
                model: this.$attrs.model,
                data: [],
                infoboxURL: "",
            };
        },
        methods: {
            loadData: function () {
                axios.get(AdminLTEHelper.getAPIURL("__layout/get_infoboxvalue/" + this.$attrs.pagename + "/" + this.model))
                    .then(({ data }) => {
                        this.data = data;
                        this.infoboxURL = data.href;
                    }).catch(({ data }) => {
                        this.$Progress.fail();
                    });
            }
        },
        mounted() {
            this.loadData();

            var eventName = "refresh" + this.model + "Data";
            this.$root.$on(eventName, () => {
                this.loadData();
            });     
            this.$nextTick(() => {
                this.$root.$emit("widget-rendered", this.model, "infobox");
            });       
        },
        updated() {
            
        }
    }
</script>