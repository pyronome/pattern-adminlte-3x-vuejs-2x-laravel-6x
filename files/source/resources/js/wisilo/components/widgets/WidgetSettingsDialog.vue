<template>
    <div class="modal fade widget-settings-dialog" :id="instance_id + 'ModalSettings'" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{ $t('Widget') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-5 col-sm-3">
                            <div class="nav flex-column nav-tabs h-100" id="vert-tabs-tab" role="tablist" aria-orientation="vertical">
                                <a class="nav-link active"
                                    :id="instance_id + 'general-tab'" 
                                    data-toggle="pill" 
                                    :href="'#' + instance_id + 'general'"
                                    role="tab" 
                                    :aria-controls="instance_id + 'general'"
                                    aria-selected="true">
                                    General
                                </a>
                                <a class="nav-link" 
                                    :id="instance_id + 'content-tab'" 
                                    data-toggle="pill" 
                                    :href="'#' + instance_id + 'content'"
                                    role="tab" 
                                    :aria-controls="instance_id + 'content'"
                                    aria-selected="false">
                                    Advanced
                                </a>
                            </div>
                        </div>
                        <div class="col-7 col-sm-9">
                            <div class="tab-content" id="vert-tabs-tabContent">
                                <div class="tab-pane text-left fade show active" :id="instance_id + 'general'" role="tabpanel" :aria-labelledby="instance_id + 'general-tab'">
                                    <div class="row">
                                        <div class="form-group col-lg-12">
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox"
                                                    :id="instance_id + '__enabled'"
                                                    class="">
                                                <label :for="instance_id + '__enabled'" class="detail-label">
                                                    {{ $t('Enabled') }}  
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group col-lg-12">
                                            <label :for="instance_id + '__title'" class="detail-label">{{ $t('Caption') }}</label>
                                            <input type="text"
                                                class="form-control "
                                                :id="instance_id + '__title'">
                                        </div> 
                                    </div>
                                    <div class="row">
                                        <input type="hidden" :id="instance_id + 'size'">
                                        <div class="col-lg-12">
                                            <label class="detail-label">{{ $t('Screen Size') }}</label>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="detail-label" :for="instance_id + '__large_screen_size'">{{ $t('Large') }}</label>
                                            <select :id="instance_id + '__large_screen_size'" class="form-control">
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                                <option value="5">5</option>
                                                <option value="6">6</option>
                                                <option value="7">7</option>
                                                <option value="8">8</option>
                                                <option value="9">9</option>
                                                <option value="10">10</option>
                                                <option value="11">11</option>
                                                <option value="12" selected>12</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="detail-label" :for="instance_id + '__medium_screen_size'">{{ $t('Medium') }}</label>
                                            <select :id="instance_id + '__medium_screen_size'" class="form-control">
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                                <option value="5">5</option>
                                                <option value="6">6</option>
                                                <option value="7">7</option>
                                                <option value="8">8</option>
                                                <option value="9">9</option>
                                                <option value="10">10</option>
                                                <option value="11">11</option>
                                                <option value="12" selected>12</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="detail-label" :for="instance_id + '__small_screen_size'">{{ $t('Small') }}</label>
                                            <select :id="instance_id + '__small_screen_size'" class="form-control">
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                                <option value="5">5</option>
                                                <option value="6">6</option>
                                                <option value="7">7</option>
                                                <option value="8">8</option>
                                                <option value="9">9</option>
                                                <option value="10">10</option>
                                                <option value="11">11</option>
                                                <option value="12" selected>12</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" :id="instance_id + 'content'" role="tabpanel" :aria-labelledby="instance_id + 'content-tab'">
                                    <slot></slot>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button 
                        type="button" 
                        @click="saveWidget()"
                        class="btn btn-success btn-md btn-on-table float-right btnSaveWidget">
                        Ok
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ["instance_id"],
    mounted() {
        window.mainLayoutInstance.pageWidgets[this.instance_id].general_settings = this;

        $(document.getElementById(this.instance_id + "ModalSettings")).off("shown.bs.modal").on("shown.bs.modal", function (e) { 
            $(document).off("focusin.modal"); 
        });

        $(".modal.fade.widget-settings-dialog input").keydown(function(e){
            if (e.keyCode == 65 && e.ctrlKey) {
                e.target.select()
            }
        })
    },
    methods: {
        setWidgetFormValues: function(instance_id) {
            /* var instance_id = this.instance_id; */
            var data = window.mainLayoutInstance.pageWidgets[instance_id].data;
            
            document.getElementById(instance_id + "__enabled").checked = (1 == data.general.enabled);
            document.getElementById(instance_id + "__title").value = data.general.title;

            var sizes = data.general.grid_size.split(",");
            document.getElementById(instance_id + "__large_screen_size").value = sizes[0];
            document.getElementById(instance_id + "__medium_screen_size").value = sizes[1];
            document.getElementById(instance_id + "__small_screen_size").value = sizes[2];

            window.mainLayoutInstance.pageWidgets[instance_id].content_settings.setWidgetFormValues();

            if (window.mainLayoutInstance.pageWidgets[instance_id].data_source) {
                window.mainLayoutInstance.pageWidgets[instance_id].data_source.setValues(data.data_source);
            }

            if (window.__variable_mapping_edit__) {
                window.__variable_mapping_edit__.renderVariableMappingList(instance_id, data.variable_mapping);
            }
        },
        saveWidget: function() {
            var instance_id = this.instance_id;
            var data = window.mainLayoutInstance.pageWidgets[instance_id].data;

            // General
            data.general.enabled = document.getElementById(instance_id + "__enabled").checked ? 1 : 0;
            data.general.title = document.getElementById(instance_id + "__title").value;

            var large_screen_size = document.getElementById(instance_id + "__large_screen_size").value;
            var medium_screen_size = document.getElementById(instance_id + "__medium_screen_size").value;
            var small_screen_size = document.getElementById(instance_id + "__small_screen_size").value;

            data.general.grid_size = large_screen_size + "," + medium_screen_size + "," + small_screen_size;

            data.general.conditional_data_json = this.getConditionalDataJSON();

            // Content
            data.content = window.mainLayoutInstance.pageWidgets[instance_id].content_settings.getWidgetFormValues();

            // Data Source
            var data_source = {};
            if (window.mainLayoutInstance.pageWidgets[instance_id].data_source) {
                data_source = window.mainLayoutInstance.pageWidgets[instance_id].data_source.getValues();
            }
            data.data_source = data_source;

            // Variable Mapping
            var variable_mapping_data = {};
            if (window.__variable_mapping_edit__) {
                variable_mapping_data = window.__variable_mapping_edit__.getVariableMappingData(instance_id);
            }
            data.variable_mapping = variable_mapping_data;
            
            window.mainLayoutInstance.pageWidgets[instance_id].data = data;

            window.mainLayoutInstance.vueComponent.showLoader();
            window.mainLayoutInstance.vueComponent.refreshWidget(instance_id);
            $(document.getElementById(instance_id + "ModalSettings")).modal("hide")

            setTimeout(function(){
                window.mainLayoutInstance.vueComponent.hideLoader();
            }, 1000);
        },
        getConditionalDataJSON: function() {
            var arrConditionalData = [];
            var instance_id = this.instance_id;

            if (!document.getElementById(instance_id + "-conditionlist")) {
                return "";
            }

            var conditionTables = $(".condition-table", document.getElementById(instance_id + "-conditionlist"));
            

            for (let i = 0; i < conditionTables.length; i++) {
                const table = conditionTables[i];

                let conditionalData = {
                    "guid": table.getAttribute("data-guid"),
                    "condition_json": JSON.parse(table.getAttribute("data-condition-json")),
                    "conditional_fields": []
                };

                let fieldData = [];
                const conditionalFieldButtons = $(".btn-edit-field", table);
                for (let j = 0; j < conditionalFieldButtons.length; j++) {
                    const btn = conditionalFieldButtons[j];
                    fieldData.push(JSON.parse(btn.getAttribute("data-field-json")));
                };
                conditionalData.conditional_fields = fieldData;

                arrConditionalData.push(conditionalData);
            };

            return JSON.stringify(arrConditionalData);
        }
    }
};
</script>