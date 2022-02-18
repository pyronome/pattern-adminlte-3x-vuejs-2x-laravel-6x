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
            setWidgetFormValues: function() {
                var instance_id = this.instance_id;
                var data = $(document.getElementById("container-" + instance_id)).data("widget_data");
                console.log("Infobox Settings -> instance_id:" + this.instance_id);
                document.getElementById(instance_id + "model").value = data.content.model;
                document.getElementById(instance_id + "title").value = data.content.title;
                document.getElementById(instance_id + "icon").value = data.content.icon;
                document.getElementById(instance_id + "iconbackground").value = data.content.iconbackground;
                document.getElementById(instance_id + "redirectURL").value = data.content.redirectURL;
            },
            getWidgetFormValues: function() {
                var instance_id = this.instance_id;

                return {
                    "model" : document.getElementById(instance_id + "model").value,
                    "title" : document.getElementById(instance_id + "title").value,
                    "icon" : document.getElementById(instance_id + "icon").value,
                    "iconbackground" : document.getElementById(instance_id + "iconbackground").value,
                    "redirectURL" : document.getElementById(instance_id + "redirectURL").value,
                };
            }
        },
        mounted() {
            window.mainLayoutInstance.widgetSettingComponents[this.instance_id] = this;
        }
    }
</script>