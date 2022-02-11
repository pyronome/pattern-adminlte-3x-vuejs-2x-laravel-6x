<template>
    <div>
        <div v-show="enabled" :style="content.css">{{content.text}}</div>
        <settingsForm :instance_id="instance_id"></settingsForm>
    </div>
</template>

<script>
    import settingsForm from './SettingsForm.vue';

    export default {
        components: {settingsForm},
        props: ['instance_id','enabled','content']
    }
</script>