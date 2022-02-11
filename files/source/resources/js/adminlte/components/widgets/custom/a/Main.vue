<template>
    <div>
        <div :style="metadata.css">{{metadata.text}}</div>
        <SettingsForm></SettingsForm>
    </div>
</template>

<script>
    import SettingsForm from './SettingsForm.vue';

    export default {
        components: {SettingsForm},
        props: ['metadata'],
        methods: {
            loadData: function () {
                console.log("Custom A loadData")
            }
        },
        mounted() {
            console.log("Custom A mounted")

            this.loadData();
        },
        updated() {
            
        }
    }
</script>