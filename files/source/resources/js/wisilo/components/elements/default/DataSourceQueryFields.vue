<template>
    <div id="__ds_query__fields-modal" class="modal level2 fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{ $t('Query Field') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <input type="hidden" class="form-control" id="__ds_query__fields-guid">

                        <div class="form-group col-lg-12">
                            <label for="__ds_query__fields-field" class="detail-label">
                                {{ $t('Field') }}
                            </label>
                            <input type="text" class="form-control " id="__ds_query__fields-field">
                        </div>
                        <div class="form-group col-lg-12">
                            <label for="__ds_query__fields-customvariable" class="detail-label">{{ $t('Custom Variable') }}</label>
                            <select id="__ds_query__fields-customvariable"  class="form-control">
                                <option value="0">{{ $t('Please select') }}</option>
                                <option value="__addnew">{{ $t('Add New') }}</option>
                                <option v-for="(customvariable, index) in customvariables" 
                                    :key="index" 
                                    :value="customvariable.id" 
                                    :name="customvariable.name"
                                    :title="customvariable.title"
                                    :selected="(customvariable.id == selected_customvariable_id)">
                                    {{customvariable.title}}
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-lg-12">
                            <span class="error invalid-feedback" id="__ds_query__fields-errors'" style="display:none"></span>
                        </div>
                    </div>
                </div>
                <div class="modalfooter justify-content-between">
                    <div class="row">
                        <div class="col-md-12">
                            <button
                                type="button"
                                id="__ds_query__fields-buttonSave"
                                @click="doSaveField"
                                data-field-guid=""
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
        <script id="__ds_query__fields-rowtemplate" type="text/html">
            <td class="">
                <span id="__guid__-label">__label__</span>
                <button type="button" title="Remove Field" class="btn-icon btn-icon-danger float-right btn-remove-field">
                    <span class="btn-label btn-label-right"><i class="fas fa-times"></i></span>
                </button>
                <button type="button" title="Edit Field" class="btn-icon btn-icon-primary float-right btn-edit-field">
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
            customvariables: [],
            selected_customvariable_id: 0,
            is_customvariables_loading: false,
            is_customvariables_loaded: false
        }
    },
    mounted() {
        window.__ds_query__fields = this;

        var self = this;

        $("#__ds_query__fields-customvariable").off("change").on("change", function (e) {
            if ("__addnew" == this.value) {
                window.__custom_variables.addNewCustomVariable();
            }
        });
    },
    methods: {
        load_customvariables: function(customvariable) {
            var self = this;
            self.customvariables = window.__custom_variables.list
            self.selected_customvariable_id = customvariable;
            /* if (undefined !== customvariable) {
                console.log("customvariable:" + customvariable)
                document.getElementById("__ds_query__fields-customvariable").value = customvariable;
            } */
            
            return;
        },
        show_swal_error: function(error_msg) {
            Vue.swal.fire({
                position: 'top-end',
                title: error_msg,
                icon: 'error',
                showConfirmButton: false,
                timer: 5000,
                timerProgressBar: true
            });
        },
        addField: function(instance_id) {
            var self = this;
            var field_data = {
                "guid" : WisiloHelper.generateGUID("dsqf"),
                "field" : "",
                "customvariable" : ""
            }

            self.setFormValues(field_data, instance_id);

            $("#__ds_query__fields-modal").modal();
        },
        doEditField: function(guid, instance_id) {
            var elTR = document.getElementById(guid + "-tr");
            var field_data = $(elTR).data("field_data");

            this.setFormValues(field_data, instance_id);

            $("#__ds_query__fields-modal").modal();
        },
        setFormValues: function(field_data, instance_id) {
            var customvariable = 0;
            if ((undefined !== field_data.customvariable) && ("" != field_data.customvariable)) {
                customvariable = field_data.customvariable;
            }
            
            this.load_customvariables(customvariable);

            if (undefined !== field_data.guid) {
                document.getElementById("__ds_query__fields-guid").value = field_data.guid;
            } else {
                document.getElementById("__ds_query__fields-guid").value = WisiloHelper.generateGUID("dsfq");
            }

            if (undefined !== field_data.field) {
                document.getElementById("__ds_query__fields-field").value = field_data.field;
            }
            
            document.getElementById("__ds_query__fields-buttonSave").setAttribute("data-instance-id", instance_id);  
        },
        doSaveField: function() {
            var self = this;
            var guid = document.getElementById("__ds_query__fields-guid").value;
            var instance_id = document.getElementById("__ds_query__fields-buttonSave").getAttribute("data-instance-id");
            
            var fieldName = document.getElementById("__ds_query__fields-field").value;
            if (null === fieldName) {
                fieldName = "";
            }

            var customvariableSelectElement = document.getElementById("__ds_query__fields-customvariable");
            var customvariable = customvariableSelectElement.value;
            if ((null === customvariable) || ("" == customvariable)) {
                self.show_swal_error(self.$t("Please select a custom variable."));
                return;
            }

            var selectedCustomVariable = customvariableSelectElement.options[customvariableSelectElement.selectedIndex];

            var fieldData = {
                "guid" : guid,
                "field" : fieldName,
                "customvariable" : customvariable,
                "label": self.getFieldLabel(fieldName, selectedCustomVariable.getAttribute("title"))
            }

            if (document.getElementById(guid + "-label")) {
                // edit field
                document.getElementById(guid + "-label").innerHTML = fieldData.label;
                $(document.getElementById(guid + "-tr")).data("field_data", fieldData);
            } else {
                var fieldsContainer = document.getElementById(instance_id + "__ds_query__fields");

                // new field
                var trTemplateHTML = document.getElementById("__ds_query__fields-rowtemplate").innerHTML;
                var trHTML = trTemplateHTML.replace(/__guid__/g, guid).replace(/__label__/g, fieldData.label);

                const tr = document.createElement("tr");
                tr.innerHTML = trHTML;
                tr.id = (guid + "-tr");
                tr.setAttribute("data-instance-id", instance_id);
                tr.setAttribute("data-guid", guid);

                fieldsContainer.appendChild(tr);

                $(document.getElementById(guid + "-tr")).data("field_data", fieldData);

                $(".btn-edit-field", fieldsContainer).off("click").on("click", function () {
                    self.doEditField(this.parentNode.parentNode.getAttribute("data-guid"), this.parentNode.parentNode.getAttribute("data-instance-id"));
                });

                $(".btn-remove-field", fieldsContainer).off("click").on("click", function () {
                    self.doRemoveField(this);
                });
            }

            $("#__ds_query__fields-modal").modal("hide");
        },
        doRemoveField: function(sender) {
            sender.parentNode.parentNode.remove();
        },
        getFieldLabel: function (fieldName, customvariable_name) {
            var label = "";
            /* if ("" == functionName) {
                label = property;
            } else {
                label = functionName + "(" + property + ") as " + customvariable_name;
            } */

            label = customvariable_name/*  + "(" + property + ")" */;

            return label;
        },
        collectFieldData: function(instance_id) {
            var arrTR = $("tr", document.getElementById(instance_id + "__ds_query__fields"));
            var fields = [];

            for (let index = 0; index < arrTR.length; index++) {
                let field_data = $(arrTR[index]).data("field_data");

                let fieldData = {
                    "guid" : field_data.guid,
                    "field" : field_data.field,
                    "customvariable" : field_data.customvariable,
                    "label" : field_data.label
                }

                fields.push(fieldData);
            }

            return fields;
        },
        renderFields: function(instance_id, fields) {
            var self = this;
            var fieldsContainer = document.getElementById(instance_id + "__ds_query__fields");
            fieldsContainer.innerHTML = "";
            var trTemplateHTML = document.getElementById("__ds_query__fields-rowtemplate").innerHTML;

            for (let index = 0; index < fields.length; index++) {
                let field_data = fields[index];
                let field_guid = field_data.guid;
                
                let trHTML = trTemplateHTML.replace(/__guid__/g, field_guid).replace(/__label__/g, field_data.label);

                let tr = document.createElement("tr");
                tr.innerHTML = trHTML;
                tr.id = (field_guid + "-tr");
                tr.setAttribute("data-instance-id", instance_id);
                tr.setAttribute("data-guid", field_guid);

                fieldsContainer.appendChild(tr);

                $(document.getElementById(field_guid + "-tr")).data("field_data", field_data);
            }
            
            $(".btn-edit-field", fieldsContainer).off("click").on("click", function () {
                self.doEditField(this.parentNode.parentNode.getAttribute("data-guid"), this.parentNode.parentNode.getAttribute("data-instance-id"));
            });

            $(".btn-remove-field", fieldsContainer).off("click").on("click", function () {
                self.doRemoveField(this);
            });
        }
    }
}
</script>