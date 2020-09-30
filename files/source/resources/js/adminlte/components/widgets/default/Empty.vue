<template>
    <div class="widgetcomponent col-lg-12 col-md-12 col-xs-12">
    </div>
</template>

<script>
    export default {
        mounted() {
            this.$nextTick(() => {
                this.$root.$emit("widgetComponentRendered");
            });
        }
    }
</script>