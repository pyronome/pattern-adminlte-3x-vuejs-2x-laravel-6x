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
                                <div class="form-group col-lg-12">
                                    <file-select label="File" :id="instance_id + 'dosya'" multiple="true"></file-select>
                                    <!-- <label :for="instance_id + 'dosya'" class="detail-label">{{ $t('File') }}</label>
                                    <input type="dosya"
                                        class="form-control "
                                        :id="instance_id + 'dosya'"> -->
                                </div>
                            </div>
                        </div>
                    </div>
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

                document.getElementById(instance_id + "css").value = data.content.css;
                document.getElementById(instance_id + "text").value = data.content.text;

                WisiloHelper.FileSelect.setValue((instance_id + "dosya"), data.content.dosya);
            },
            getWidgetFormValues: function() {
                var instance_id = this.instance_id;
                return {
                    "css" : document.getElementById(instance_id + "css").value,
                    "text" : document.getElementById(instance_id + "text").value,
                    "dosya" : WisiloHelper.FileSelect.getValue((instance_id + "dosya"))
                };
            }
        },
        mounted() {
            window.mainLayoutInstance.pageWidgets[this.instance_id].content_settings = this;
        }
    }
</script>