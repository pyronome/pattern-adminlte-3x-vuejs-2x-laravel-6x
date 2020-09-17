<template>
    <div class="googlemap">
    </div>
</template>

<script>
export default {
    props: ['coordinates'],
    mounted() {
        /* var vm = this
        $(this.$el)
            .select2({ data: this.options })
            .val(this.value)
            .trigger("change")
            .on('change', function () {
                vm.$emit('input', $(this).val())
            }); */
    },
    watch: {
        /* value: function (value) {
            if ([...value].sort().join(",") !== [...$(this.$el).val()].sort().join(",")) {
                $(this.$el).val(value).trigger('change');
            }
        }, */
        coordinates: function (coordinates) {
            if((undefined !== coordinates.lat) && (undefined !== coordinates.lng)) {
                AdminLTEHelper.showGoogleMap(this.$el);
            }
        }
    },
    destroyed() {
        /* $(this.$el).off().select2('destroy'); */
    }
}
</script>
