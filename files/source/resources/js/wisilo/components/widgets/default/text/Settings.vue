<template>
    <div>
        <widget-settings-dialog :instance_id="instance_id">
            <div class="row">
                <div class="form-group col-lg-12">
                    <label :for="instance_id + 'text'" class="detail-label">{{ $t('Text') }}</label>
                    <insert-custom-variable-button :target="instance_id + 'text'"></insert-custom-variable-button>
                    <input type="text"
                        class="form-control "
                        :id="instance_id + 'text'">
                </div>
            </div>
        </widget-settings-dialog>
    </div>
</template>

<script>
    export default {
        props: ["instance_id"],
        methods: {
            setWidgetFormValues: function() {
                var instance_id = this.instance_id;
                var data = window.mainLayoutInstance.pageWidgets[this.instance_id].data;
                
                document.getElementById(instance_id + "text").value = data.content.text;
            },
            getWidgetFormValues: function() {
                var instance_id = this.instance_id;

                return {
                    "text" : document.getElementById(instance_id + "text").value,
                };
            }
        },
        mounted() {
            window.mainLayoutInstance.pageWidgets[this.instance_id].content_settings = this;
        }
    }
</script>