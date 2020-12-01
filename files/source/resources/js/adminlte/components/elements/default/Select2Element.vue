<template>
    <select>
        <slot></slot>
    </select>
</template>

<script>
export default {
    props: ['options', 'value', 'readonly'],
    data() {
        return {
            initial_value: undefined,
            value_initialized: false,
            options_initialized: false,
        }
    },
    mounted() {
        var vm = this;
        $(this.$el).select2({ data: this.options });
    },
    watch: {
        value: function (value) {
            this.initial_value = value;
            this.setValue(value);
        },
        options: function (options) {
            if (undefined !== options && 0 != options.length) {
                if ($(this.$el).children().length > 1) {
                    $(this.$el).children().detach();
                    this.$el.innerHTML = "<option></option>";
                }

                $(this.$el).select2({ data: options }).trigger('change');
                this.options_initialized = true;

                if ((!this.value_initialized) && (undefined !== this.initial_value)) {
                    this.setValue(this.initial_value);
                }
            }
        },
        readonly: function (readonly) {
            if (readonly) {
                $(this.$el).closest('div').find('.select2-container:first').addClass('select2-readonly')
            }
        },
    },
    methods: {
        setValue: function(value) {
            if (!this.options_initialized) {
                return;
            }

            if (!Array.isArray(value)) {
                if (value !== $(this.$el).val()) {
                    $(this.$el).val(value).trigger('change').on('change', function () {
                        this.$emit('input', $(this).val())
                    });
                }
            } else {
                if ([...value].sort().join(",") !== [...$(this.$el).val()].sort().join(",")) {
                    $(this.$el).val(value).trigger('change').on('change', function () {
                        this.$emit('input', $(this).val())
                    });
                }
            }

            this.value_initialized = true;
        }
    },
    destroyed() {
        $(this.$el).off().select2('destroy');
    }
}
</script>