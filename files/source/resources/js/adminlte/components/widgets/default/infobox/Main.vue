<template>
    <div class="widgetcomponent">
        <router-link v-show="enabled" class="info-box clickable-infobox" tag="div" :to="content.redirectURL">
            <span class="info-box-icon elevation-1" v-bind:style="{backgroundColor: content.iconbackground}"><i :class="content.icon"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">{{content.title}}</span>
                <span class="info-box-number">{{count}}</span>
            </div>
        </router-link>
        <settingsForm :instance_id="instance_id"></settingsForm>
    </div>
</template>

<script>
    import settingsForm from './SettingsForm.vue';

    export default {
        components: {settingsForm},
        props: ['instance_id','enabled','content'],
        data() {
            return {
                model: this.content.model,
                count: 0,
            };
        },
        methods: {
            loadData: function () {
                axios.get(AdminLTEHelper.getAPIURL("__layout/get_infoboxvalue/" + this.model))
                    .then(({ data }) => {
                        this.data = data;
                        this.count = data.count;
                    }).catch(({ data }) => {
                        this.$Progress.fail();
                    });
            }
        },
        mounted() {
            this.loadData();

            /* var eventName = "refresh" + this.model + "Data";
            this.$root.$on(eventName, () => {
                this.loadData();
            });     
            this.$nextTick(() => {
                this.$root.$emit("widget-rendered", this.model, "infobox");
            });   */     
        },
        updated() {
            
        }
    }
</script>