<template>
    <div id="__variable_mapping__-modal" class="modal level2 fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{ $t('Variable Mapping') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <input type="hidden" class="form-control" id="__variable_mapping__guid">

                        <div class="form-group col-lg-12">
                            <label for="__variable_mapping__local_variable" class="detail-label">{{ $t('Local Variable') }}</label>
                            <select id="__variable_mapping__local_variable"  class="form-control">
                                <option value="">{{ $t('Please select') }}</option>
                                <option v-for="(local_variable, index) in local_variables" :key="index" 
                                    :value="local_variable.id" 
                                    :name="local_variable.label"
                                    :selected="(local_variable.id == selected_local_variable_id)">
                                    {{local_variable.label}}
                                </option>
                            </select>
                        </div>

                        <div class="form-group col-lg-12">
                            <label for="__variable_mapping__custom_variable" class="detail-label">{{ $t('Custom Variable') }}</label>
                            <select id="__variable_mapping__custom_variable"  class="form-control">
                                <option value="0">{{ $t('Please select') }}</option>
                                <option value="__addnew">{{ $t('Add New') }}</option>
                                <option v-for="(custom_variable, index) in custom_variables" 
                                    :key="index" 
                                    :value="custom_variable.id" 
                                    :name="custom_variable.name"
                                    :title="custom_variable.title"
                                    :selected="(custom_variable.id == selected_custom_variable_id)">
                                    {{custom_variable.title}}
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-lg-12">
                            <span class="error invalid-feedback" id="__variable_mapping__errors'" style="display:none"></span>
                        </div>
                    </div>
                </div>
                <div class="modalfooter justify-content-between">
                    <div class="row">
                        <div class="col-md-12">
                            <button
                                type="button"
                                id="__variable_mapping__buttonSave"
                                @click="doSaveVariable"
                                data-variable-guid=""
                                data-instance-id=""
                                class="btn btn-success btn-md btn-on-table float-right">
                                {{ $t('Save') }}
                            </button>
                            <button type="button" class="btn btn-outline-secondary float-left" data-dismiss="modal" style="margin-right:10px;">{{ $t('Close') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script id="__variable_mapping__rowtemplate" type="text/html">
            <td class="">
                <span id="__guid__-label">__label__</span>
                <button type="button" title="Remove Mapping" class="btn-icon btn-icon-danger float-right btn-remove-mapping">
                    <span class="btn-label btn-label-right"><i class="fas fa-times"></i></span>
                </button>
                <button type="button" title="Edit Mapping" class="btn-icon btn-icon-primary float-right btn-edit-mapping">
                    <span class="btn-label btn-label-right"><i class="fas fa-pen"></i></span>
                </button>
            </td>
        </script>
    </div>
</template>

<script>
export default {
    data() {
        return {
            local_variables: [],
            selected_local_variable_id: 0,
            custom_variables: [],
            selected_custom_variable_id: 0,
        };
    },
    methods: {
            initializePage: function() {
            },
            load_local_variables: function(instance_id, local_variable) {
                this.local_variables = window.mainLayoutInstance.pageWidgets[instance_id].variable_mapping.local_variables
                this.selected_local_variable_id = local_variable;
                return;
            },
            load_custom_variables: function(custom_variable) {
                this.custom_variables = window.__custom_variables.list
                this.selected_custom_variable_id = custom_variable;
                return;
            },
            addVariableMapping: function(instance_id) {
                var mapping_data = {
                    "guid" : WisiloHelper.generateGUID("__vm__"),
                    "local_variable" : "",
                    "custom_variable" : "",
                    "label": ""
                }

                this.setFormValues(mapping_data, instance_id);

                $("#__variable_mapping__-modal").modal();
            },
            doEditMapping: function(guid, instance_id) {
                var elTR = document.getElementById(guid + "-tr");
                var mapping_data = $(elTR).data("mapping_data");

                this.setFormValues(mapping_data, instance_id);

                $("#__variable_mapping__-modal").modal();
            },
            setFormValues: function(mapping_data, instance_id) {
                var local_variable = 0;
                if ((undefined !== mapping_data.local_variable) && ("" != mapping_data.local_variable)) {
                    local_variable = mapping_data.local_variable;
                }
                
                this.load_local_variables(instance_id, local_variable);

                var custom_variable = 0;
                if ((undefined !== mapping_data.custom_variable) && ("" != mapping_data.custom_variable)) {
                    custom_variable = mapping_data.custom_variable;
                }
                
                this.load_custom_variables(custom_variable);

                if (undefined !== mapping_data.guid) {
                    document.getElementById("__variable_mapping__guid").value = mapping_data.guid;
                } else {
                    document.getElementById("__variable_mapping__guid").value = WisiloHelper.generateGUID("__vm__");
                }
                
                document.getElementById("__variable_mapping__buttonSave").setAttribute("data-instance-id", instance_id);  
            },
            doSaveVariable: function() {
                var self = this;
                var guid = document.getElementById("__variable_mapping__guid").value;
                var instance_id = document.getElementById("__variable_mapping__buttonSave").getAttribute("data-instance-id");
                
                var local_variableSelectElement = document.getElementById("__variable_mapping__local_variable");
                var local_variable = local_variableSelectElement.value;
                if ((null === local_variable) || ("" == local_variable)) {
                    self.show_swal_error(self.$t("Please select a local variable."));
                    return;
                }

                var custom_variableSelectElement = document.getElementById("__variable_mapping__custom_variable");
                var custom_variable = custom_variableSelectElement.value;
                if ((null === custom_variable) || ("" == custom_variable)) {
                    self.show_swal_error(self.$t("Please select a custom variable."));
                    return;
                }

                var selectedLocalVariable = local_variableSelectElement.options[local_variableSelectElement.selectedIndex];
                var selectedCustomVariable = custom_variableSelectElement.options[custom_variableSelectElement.selectedIndex];

                var mapping_data = {
                    "guid" : guid,
                    "local_variable" : local_variable,
                    "custom_variable" : custom_variable,
                    "label": self.getVariableLabel(
                        selectedLocalVariable.getAttribute("name"), 
                        selectedCustomVariable.getAttribute("title")
                    )
                }

                if (document.getElementById(guid + "-label")) {
                    // edit field
                    document.getElementById(guid + "-label").innerHTML = mapping_data.label;
                    $(document.getElementById(guid + "-tr")).data("mapping_data", mapping_data);
                } else {
                    var mappingContainer = document.getElementById(instance_id + "__variable_mapping_list__");

                    // new field
                    var trTemplateHTML = document.getElementById("__variable_mapping__rowtemplate").innerHTML;
                    var trHTML = trTemplateHTML.replace(/__guid__/g, guid).replace(/__label__/g, mapping_data.label);

                    const tr = document.createElement("tr");
                    tr.innerHTML = trHTML;
                    tr.id = (guid + "-tr");
                    tr.setAttribute("data-instance-id", instance_id);
                    tr.setAttribute("data-guid", guid);

                    mappingContainer.appendChild(tr);

                    $(document.getElementById(guid + "-tr")).data("mapping_data", mapping_data);

                    $(".btn-edit-mapping", mappingContainer).off("click").on("click", function () {
                        self.doEditMapping(this.parentNode.parentNode.getAttribute("data-guid"), this.parentNode.parentNode.getAttribute("data-instance-id"));
                    });

                    $(".btn-remove-mapping", mappingContainer).off("click").on("click", function () {
                        self.doRemoveMapping(this);
                    });
                }

                $("#__variable_mapping__-modal").modal("hide");
            },
            doRemoveMapping: function(sender) {
                var mapping_guid = sender.parentNode.parentNode.getAttribute("data-guid");
                sender.parentNode.parentNode.remove();
            },
            getVariableLabel: function (local_variableLabel, custom_variableLabel) {
                var label = local_variableLabel + " -> " + custom_variableLabel;
                return label;
            },
            getVariableMappingData: function(instance_id) {
                var mappings = [];

                if (!document.getElementById(instance_id + "__variable_mapping_list__")) {
                    return mappings;
                }

                var arrTR = $("tr", document.getElementById(instance_id + "__variable_mapping_list__"));

                for (let index = 0; index < arrTR.length; index++) {
                    let mapping_data = $(arrTR[index]).data("mapping_data");

                    let mappingData = {
                        "guid" : mapping_data.guid,
                        "local_variable" : mapping_data.local_variable,
                        "custom_variable" : mapping_data.custom_variable,
                        "label" : mapping_data.label
                    }

                    mappings.push(mappingData);
                }

                return mappings;
            },
            renderVariableMappingList: function(instance_id, variable_mapping_data) {
                var self = this;

                if (!document.getElementById(instance_id + "__variable_mapping_list__")) {
                    return;
                }

                var mappingContainer = document.getElementById(instance_id + "__variable_mapping_list__");
                mappingContainer.innerHTML = "";

                if (undefined === variable_mapping_data) {
                    return;
                }

                var trTemplateHTML = document.getElementById("__variable_mapping__rowtemplate").innerHTML;

                for (let index = 0; index < variable_mapping_data.length; index++) {
                    let mapping_data = variable_mapping_data[index];
                    let guid = mapping_data.guid;
                    
                    let trHTML = trTemplateHTML.replace(/__guid__/g, guid).replace(/__label__/g, mapping_data.label);

                    let tr = document.createElement("tr");
                    tr.innerHTML = trHTML;
                    tr.id = (guid + "-tr");
                    tr.setAttribute("data-instance-id", instance_id);
                    tr.setAttribute("data-guid", guid);

                    mappingContainer.appendChild(tr);

                    $(document.getElementById(guid + "-tr")).data("mapping_data", mapping_data);
                }

                $(".btn-edit-mapping", mappingContainer).off("click").on("click", function () {
                    self.doEditMapping(this.parentNode.parentNode.getAttribute("data-guid"), this.parentNode.parentNode.getAttribute("data-instance-id"));
                });

                $(".btn-remove-mapping", mappingContainer).off("click").on("click", function () {
                    self.doRemoveMapping(this);
                });
            }
        },
        mounted() {
            window.__variable_mapping_edit__ = this;
            this.initializePage();
        }
}
</script>