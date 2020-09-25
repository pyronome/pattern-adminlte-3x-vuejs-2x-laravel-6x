<template>
    <select>
        <slot></slot>
    </select>
</template>

<script>
export default {
    props: ['options', 'value'],
    mounted() {
        var vm = this
        $(this.$el)
            .select2({ data: this.options })
            .val(this.value)
            .trigger("change")
            .on('change', function () {
                vm.$emit('input', $(this).val())
            });
    },
    watch: {
        value: function (value) {
            if (!Array.isArray(value)) {
                if (value !== $(this.$el).val()) {
                    $(this.$el).val(value).trigger('change');
                }
            } else {
                if ([...value].sort().join(",") !== [...$(this.$el).val()].sort().join(",")) {
                    $(this.$el).val(value).trigger('change');
                }
            }
        },
        options: function (options) {
            if (undefined !== options && 0 != options.length) {
                $(this.$el).select2({ data: options });
            }
        }
    },
    destroyed() {
        $(this.$el).off().select2('destroy');
    }
}
</script>
