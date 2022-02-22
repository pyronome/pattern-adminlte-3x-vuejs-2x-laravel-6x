<template>
    <div>
        <widget-settings-dialog :instance_id="instance_id">
            <div class="row">
                <div class="form-group col-lg-12">
                    <label :for="instance_id + 'css'" class="detail-label">{{ $t('CSS') }}</label>
                    <input type="text"
                        class="form-control "
                        :id="instance_id + 'css'">
                </div>
                <div class="form-group col-lg-12">
                    <label :for="instance_id + 'text'" class="detail-label">{{ $t('Text') }}</label>
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
                var data = window.mainLayoutInstance.pageWidgets[instance_id].data;

                document.getElementById(instance_id + "css").value = data.content.css;
                document.getElementById(instance_id + "text").value = data.content.text;
            },
            getWidgetFormValues: function() {
                var instance_id = this.instance_id;

                return {
                    "css" : document.getElementById(instance_id + "css").value,
                    "text" : document.getElementById(instance_id + "text").value
                };
            }
        },
        mounted() {
            window.mainLayoutInstance.pageWidgets[this.instance_id].content_settings = this;
        }
    }
</script>