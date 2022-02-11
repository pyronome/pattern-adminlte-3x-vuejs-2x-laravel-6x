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
                <div class="form-group col-lg-12">
                    <label :for="instance_id + 'text'" class="detail-label">{{ $t('Text') }}</label>
                    <input type="text"
                        class="form-control "
                        :id="instance_id + 'text'">
                </div>
            </div>
        </element-settings>
    </div>
</template>

<script>
    export default {
        props: ["instance_id"],
        methods: {
            fillWidgetFormValues: function() {
                var self = this;
                var instance_id = self.instance_id;
                var widgets_form_data = $("#widgets_form_data").data("widgets_form_data");
                var content_data = widgets_form_data[instance_id]["content_data"];
                
                document.getElementById(instance_id + "css").value = content_data.css;
                document.getElementById(instance_id + "text").value = content_data.text;
            },
            collectWidgetFormValues: function() {
                var instance_id = this.instance_id;
                var formData = {
                    "css" : document.getElementById(instance_id + "css").value,
                    "text" : document.getElementById(instance_id + "text").value
                };

                AdminLTEHelper.setWidgetFormContentData(instance_id, formData);
            }
        },
        mounted() {
            var self = this;

            self.$root.$on("fill-widget-form-values", (instance_id) => {
                if (self.instance_id == instance_id) {
                    self.fillWidgetFormValues();
                }
            });

            self.$root.$on("collect-widget-form-values", (instance_id) => {
                if (self.instance_id == instance_id) {
                    self.collectWidgetFormValues();
                }
            });
        }
    }
</script>