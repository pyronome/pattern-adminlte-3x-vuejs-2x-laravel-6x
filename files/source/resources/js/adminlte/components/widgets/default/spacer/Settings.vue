<template>
    <div>
        <element-settings :instance_id="instance_id">
            <div class="row">
                <div class="form-group col-lg-12">
                    <label :for="instance_id + 'css'" class="detail-label">{{ $t('CSS') }}</label>
                    <input type="text"
                        class="form-control "
                        :id="instance_id + 'css'">
                </div>
            </div>
        </element-settings>
    </div>
</template>

<script>
    export default {
        props: ["instance_id"],
        methods: {
            setWidgetFormValues: function() {
                var instance_id = this.instance_id;
                var data = $(document.getElementById("container-" + instance_id)).data("widget_data");
                
                document.getElementById(instance_id + "css").value = data.content.css;
            },
            collectWidgetFormValues: function() {
                var instance_id = this.instance_id;

                return {
                    "css" : document.getElementById(instance_id + "css").value,
                };
            }
        },
        mounted() {
            window.mainLayoutInstance.widgetSettingComponents[this.instance_id] = this;
        }
    }
</script>