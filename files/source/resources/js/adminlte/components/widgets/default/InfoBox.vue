<template>
    <div :class="data.size" >
        <router-link class="info-box clickable-infobox" tag="div" :to="infoboxURL">
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
                data: []
            };
        },
        computed: {
            infoboxURL: function () {
                return this.data.href
            }
        },
        methods: {
            loadData: function () {
                axios.get(AdminLTEHelper.getAPIURL("__layout/get_infoboxvalue/" + this.$attrs.pagename + "/" + this.model))
                    .then(({ data }) => {
                        this.data = data;
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
        }
    }
</script>