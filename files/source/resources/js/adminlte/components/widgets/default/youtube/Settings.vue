<template>
    <div>
        <widget-settings-dialog :instance_id="instance_id">
            <div class="row">
                <div class="form-group col-lg-12">
                    <label :for="instance_id + 'youtubecode'" class="detail-label">{{ $t('Youtube Embed SRC') }}</label>
                    <input type="text"
                        class="form-control "
                        :id="instance_id + 'youtubecode'">
                </div>
                <div class="form-group col-lg-12">
                    <label :for="instance_id + 'width'" class="detail-label">{{ $t('Iframe Width') }}</label>
                    <input type="text"
                        class="form-control "
                        :id="instance_id + 'width'">
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
                
                document.getElementById(instance_id + "youtubecode").value = data.content.youtubecode;
                document.getElementById(instance_id + "width").value = data.content.width;
            },
            getWidgetFormValues: function() {
                var instance_id = this.instance_id;

                return {
                    "youtubecode" : document.getElementById(instance_id + "youtubecode").value,
                    "width" : document.getElementById(instance_id + "width").value
                };
            }
        },
        mounted() {
            window.mainLayoutInstance.pageWidgets[this.instance_id].content_settings = this;
        }
    }
</script>