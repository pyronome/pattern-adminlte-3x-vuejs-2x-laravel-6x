<template>
    <div>
        <widget-settings-dialog :instance_id="instance_id">
            <div :id="instance_id + '-accordion'">
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <h4 class="card-title w-100">
                            <a class="d-block w-100" data-toggle="collapse" :href="'#' + instance_id + '-accordion-dsettings'">
                                Default Settings
                            </a>
                        </h4>
                    </div>
                    <div class="collapse show"
                        :id="instance_id + '-accordion-dsettings'"
                        :data-parent="'#' + instance_id + '-accordion'">
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-lg-12">
                                    <label :for="instance_id + 'record_list_title'" class="detail-label">
                                        {{ $t('Title') }}
                                    </label>
                                    <input type="text" class="form-control " :id="instance_id + 'record_list_title'">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <data-source :instance_id="instance_id"></data-source>
                <!-- <widget-conditional-settings :instance_id="instance_id" :conditional_fields="conditional_fields"></widget-conditional-settings> -->
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
                
                document.getElementById(instance_id + "record_list_title").value = data.content.record_list_title;
            },
            getWidgetFormValues: function() {
                var instance_id = this.instance_id;

                return {
                    "record_list_title" : document.getElementById(instance_id + "record_list_title").value,
                };
            }
        },
        mounted() {
            window.mainLayoutInstance.pageWidgets[this.instance_id].content_settings = this;
        }
    }
</script>