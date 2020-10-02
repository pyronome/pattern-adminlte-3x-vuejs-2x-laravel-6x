<template>
    <div class="content-wrapper bodyLoader" id="divLoader" >
        <img class="center-block" src="/img/adminlte/loader.svg" width="70" height="70" />
        <div id="divLoaderText" class="" v-html="loaderText"></div>
    </div>
</template>

<script>
export default {
    props: ['body_loader_active', 'loaderText'],
    watch: {
        body_loader_active: function (body_loader_active) {
            if (body_loader_active) {
                $("#divLoader").show();
            } else {
                $("#divLoader").fadeOut(1000);
            }
        }
    }
}
</script>