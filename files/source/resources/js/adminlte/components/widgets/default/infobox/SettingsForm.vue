<template>
    <div>
        <element-settings :instance_id="instance_id">
            <div class="row">
                <div class="form-group col-lg-12">
                    <label :for="instance_id + 'model'" class="detail-label">{{ $t('Model') }}</label>
                    <input type="text"
                        class="form-control "
                        :id="instance_id + 'model'">
                </div>
                <div class="form-group col-lg-12">
                    <label :for="instance_id + 'title'" class="detail-label">{{ $t('Title') }}</label>
                    <input type="text"
                        class="form-control "
                        :id="instance_id + 'title'">
                </div>
                <div class="form-group col-lg-12">
                    <label :for="instance_id + 'icon'" class="detail-label">{{ $t('Icon') }}</label>
                    <input type="text"
                        class="form-control "
                        :id="instance_id + 'icon'">
                </div>
                <div class="form-group col-lg-12">
                    <label :for="instance_id + 'iconbackground'" class="detail-label">{{ $t('Icon Background') }}</label>
                    <input type="text"
                        class="form-control "
                        :id="instance_id + 'iconbackground'">
                </div>
                <div class="form-group col-lg-12">
                    <label :for="instance_id + 'redirectURL'" class="detail-label">{{ $t('Redirect URL') }}</label>
                    <input type="text"
                        class="form-control "
                        :id="instance_id + 'redirectURL'">
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
                
                document.getElementById(instance_id + "model").value = content_data.model;
                document.getElementById(instance_id + "title").value = content_data.title;
                document.getElementById(instance_id + "icon").value = content_data.icon;
                document.getElementById(instance_id + "iconbackground").value = content_data.iconbackground;
                document.getElementById(instance_id + "redirectURL").value = content_data.redirectURL;

            },
            collectWidgetFormValues: function() {
                var instance_id = this.instance_id;
                var formData = {
                    "model" : document.getElementById(instance_id + "model").value,
                    "title" : document.getElementById(instance_id + "title").value,
                    "icon" : document.getElementById(instance_id + "icon").value,
                    "iconbackground" : document.getElementById(instance_id + "iconbackground").value,
                    "redirectURL" : document.getElementById(instance_id + "redirectURL").value,
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