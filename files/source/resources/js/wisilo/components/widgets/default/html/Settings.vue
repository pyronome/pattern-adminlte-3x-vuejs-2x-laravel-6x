<template>
    <div>
        <widget-settings-dialog :instance_id="instance_id">
            <div class="row">
                <div class="form-group col-lg-12">
                    <label :for="instance_id + 'html'" class="detail-label">
                        HTML Content
                        <insert-custom-variable-button :target="instance_id + 'html'"></insert-custom-variable-button>
                    </label>
                    <textarea class="textarea vue-editor" :id="instance_id + 'html'" rows="5"></textarea>
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
                
                $(document.getElementById(instance_id + "html")).summernote("code", data.content.html);
            },
            getWidgetFormValues: function() {
                var instance_id = this.instance_id;

                return {
                    "html" : $(document.getElementById(instance_id + "html")).summernote("code"),
                };
            }
        },
        mounted() {
            window.mainLayoutInstance.pageWidgets[this.instance_id].content_settings = this;
        }
    }
</script>