<template>
    <div class="content-wrapper widgetLoader" id="divLoader" >
        <img class="center-block" src="/img/adminlte/loader.svg" width="70" height="70" />
        <div id="divLoaderText" class="" v-html="loaderText"></div>
    </div>
</template>

<script>
export default {
    props: ['widget_loader_active', 'loaderText'],
    watch: {
        widget_loader_active: function (widget_loader_active) {
            if (widget_loader_active) {
                $("#divLoader").show();
            } else {
                $("#divLoader").fadeOut(1000);
            }
        }
    }
}
</script>