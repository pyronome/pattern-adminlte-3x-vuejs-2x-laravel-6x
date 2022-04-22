<template>
    <div id="__ds_fields" class="modal level2 fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{ $t('Field') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <input type="hidden" class="form-control" id="__ds_field__guid">

                        <div class="form-group col-lg-12">
                            <label for="__ds_field__function" class="detail-label">{{ $t('Function') }}</label>
                            <select id="__ds_field__function" class="form-control">
                                <option value="">{{ $t('Please select') }}</option>
                                <option value="avg">AVG</option>
                                <option value="count">COUNT</option>
                                <option value="first">FIRST</option>
                                <option value="last">LAST</option>
                                <option value="max">MAX</option>
                                <option value="min">MIN</option>
                                <option value="sum">SUM</option>
                            </select>
                        </div>

                        <div class="form-group col-lg-12">
                            <label for="__ds_field__property" class="detail-label">{{ $t('Property') }}</label>
                            <select id="__ds_field__property"  class="form-control">
                                <option value="">{{ $t('Please select') }}</option>
                                <option v-for="(property, index) in property_options" :key="index" :value="property.id">
                                    {{property.text}}
                                </option>
                            </select>
                        </div>

                        <div class="form-group col-lg-12">
                            <label for="__ds_field__customvariable" class="detail-label">{{ $t('Custom Variable') }}</label>
                            <select id="__ds_field__customvariable"  class="form-control">
                                <option value="0">{{ $t('Please select') }}</option>
                                <option value="__addnew">{{ $t('Add New') }}</option>
                                <option v-for="(customvariable, index) in customvariables" 
                                    :key="index" 
                                    :value="customvariable.id" 
                                    :name="customvariable.name"
                                    :selected="(customvariable.id == selected_customvariable_id)">
                                    {{customvariable.title}}
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-lg-12">
                            <span class="error invalid-feedback" id="__ds_field__errors'" style="display:none"></span>
                        </div>
                    </div>
                </div>
                <div class="modalfooter justify-content-between">
                    <div class="row">
                        <div class="col-md-12">
                            <button
                                type="button"
                                id="__ds_field__buttonSave"
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
        <script id="__ds_field__rowtemplate" type="text/html">
            <td class="">
                <span id="__guid__-sql_text">__sql_text__</span>
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
            last_model: "",
            property_options: [],
            is_property_options_loading: false,
            is_property_options_loaded: false,
            customvariables: [],
            selected_customvariable_id: 0,
            is_customvariables_loading: false,
            is_customvariables_loaded: false
        }
    },
    mounted() {
        window.__ds_fields = this;

        var self = this;

        $("#__ds_field__customvariable").off("change").on("change", function (e) {
            if ("__addnew" == this.value) {
                window.__custom_variables.addNewCustomVariable();
            }
        });
    },
    methods: {
        load_property_options: function(field_data) {
            var self = this;

            if (self.last_model == field_data.model) {
                if (undefined !== field_data.property) {
                    document.getElementById("__ds_field__property").value = field_data.property;
                }

                return;
            }

            if (self.is_property_options_loading) {
                return;
            }

            self.is_property_options_loading = true;

            axios.get(AdminLTEHelper.getAPIURL("__layout/get_model_properties/" + field_data.model))
                .then(({ data }) => {
                    self.is_property_options_loaded = true;
                    self.is_property_options_loading = false;
                    self.property_options = data.list;
                }).catch(({ data }) => {
                    self.is_property_options_loaded = true;
                    self.is_property_options_loading = false;
                }).finally(function() {
                    self.last_model = field_data.model;
                    if (undefined !== field_data.property) {
                        document.getElementById("__ds_field__property").value = field_data.property;
                    }
                });
        },
        load_customvariables: function(customvariable) {
            console.log("customvariable:" + customvariable)
            var self = this;
            self.customvariables = window.__custom_variables.list
            self.selected_customvariable_id = customvariable;
            /* if (undefined !== customvariable) {
                console.log("customvariable:" + customvariable)
                document.getElementById("__ds_field__customvariable").value = customvariable;
            } */
            
            return;


            if (self.is_customvariables_loading) {
                return;
            }

            self.is_customvariables_loading = true;

            axios.get(AdminLTEHelper.getAPIURL("adminlte/get_custom_variables"))
                .then(({ data }) => {
                    self.is_customvariables_loaded = true;
                    self.is_customvariables_loading = false;
                    self.customvariables = data.list;
                }).catch(({ data }) => {
                    self.is_customvariables_loaded = true;
                    self.is_customvariables_loading = false;
                }).finally(function() {
                    if (undefined !== field_data.customvariable) {
                        document.getElementById("__ds_field__customvariable").value = field_data.customvariable;
                    }
                });
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
                "guid" : AdminLTEHelper.generateGUID("__ds_field__"),
                "model" : document.getElementById(instance_id + "__ds_extreme_model").value,
                "property" : "",
                "function" : "",
                "customvariable" : "",
            }

            if (undefined === field_data.model) {
                self.show_swal_error(self.$t("Check data parameters for field dialog."));
                return;
            }

            if ("" === field_data.model) {
                self.show_swal_error(self.$t("Please select a model."));
                return;
            }

            self.setFormValues(field_data, instance_id);

            $("#__ds_fields").modal();
        },
        doEditField: function(guid, instance_id) {
            var elTR = document.getElementById(guid + "-tr");
            var field_data = $(elTR).data("field_data");

            this.setFormValues(field_data, instance_id);

            $("#__ds_fields").modal();
        },
        setFormValues: function(field_data, instance_id) {
            this.load_property_options(field_data);

            var customvariable = 0;
            if ((undefined !== field_data.customvariable) && ("" != field_data.customvariable)) {
                customvariable = field_data.customvariable;
            }
            
            this.load_customvariables(customvariable);

            if (undefined !== field_data.function) {
                document.getElementById("__ds_field__function").value = field_data.function;
            }

            if (undefined !== field_data.guid) {
                document.getElementById("__ds_field__guid").value = field_data.guid;
            } else {
                document.getElementById("__ds_field__guid").value = AdminLTEHelper.generateGUID("__ds_field__");
            }
            
            document.getElementById("__ds_field__buttonSave").setAttribute("data-instance-id", instance_id);  
        },
        doSaveField: function() {
            var self = this;
            var guid = document.getElementById("__ds_field__guid").value;
            var instance_id = document.getElementById("__ds_field__buttonSave").getAttribute("data-instance-id");
            
            var functionName = document.getElementById("__ds_field__function").value;
            if (null === functionName) {
                functionName = "";
            }

            var property = document.getElementById("__ds_field__property").value;
            if ((null === property) || ("" == property)) {
                self.show_swal_error(self.$t("Please select a property."));
                return;
            }

            var customvariableSelectElement = document.getElementById("__ds_field__customvariable");
            var customvariable = customvariableSelectElement.value;
            if ((null === customvariable) || ("" == customvariable)) {
                self.show_swal_error(self.$t("Please select a custom variable."));
                return;
            }

            var selectedCustomVariable = customvariableSelectElement.options[customvariableSelectElement.selectedIndex];

            var fieldData = {
                "guid" : guid,
                "model": document.getElementById(instance_id + "__ds_extreme_model").value,
                "function" : functionName,
                "property" : property,
                "customvariable" : customvariable,
                "sql_text": self.getSQLText(functionName, property, selectedCustomVariable.getAttribute("name"))
            }

            if (document.getElementById(guid + "-sql_text")) {
                // edit field
                document.getElementById(guid + "-sql_text").innerHTML = fieldData.sql_text;
                $(document.getElementById(guid + "-tr")).data("field_data", fieldData);
            } else {
                var fieldsContainer = document.getElementById(instance_id + "__ds_extreme_fields");

                // new field
                var trTemplateHTML = document.getElementById("__ds_field__rowtemplate").innerHTML;
                var trHTML = trTemplateHTML.replace(/__guid__/g, guid).replace(/__sql_text__/g, fieldData.sql_text);

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

            $("#__ds_fields").modal("hide");
        },
        doRemoveField: function(sender) {
            sender.parentNode.parentNode.remove();
        },
        getSQLText: function (functionName, property, customvariable_name) {
            var sql_text = "";
            if ("" == functionName) {
                sql_text = property;
            } else {
                sql_text = functionName + "(" + property + ") as " + customvariable_name;
            }

            return sql_text;
        },
        collectFieldData: function(instance_id) {
            var arrTR = $("tr", document.getElementById(instance_id + "__ds_extreme_fields"));
            var fields = [];

            for (let index = 0; index < arrTR.length; index++) {
                let field_data = $(arrTR[index]).data("field_data");

                let fieldData = {
                    "guid" : field_data.guid,
                    "model" : field_data.model,
                    "function" : field_data.function,
                    "property" : field_data.property,
                    "customvariable" : field_data.customvariable,
                    "sql_text" : field_data.sql_text
                }

                fields.push(fieldData);
            }

            return fields;
        },
        renderFields: function(instance_id, fields) {
            var self = this;
            var fieldsContainer = document.getElementById(instance_id + "__ds_extreme_fields");
            fieldsContainer.innerHTML = "";
            var trTemplateHTML = document.getElementById("__ds_field__rowtemplate").innerHTML;

            for (let index = 0; index < fields.length; index++) {
                let field_data = fields[index];
                
                let trHTML = trTemplateHTML.replace(/__guid__/g, field_data.guid).replace(/__sql_text__/g, field_data.sql_text);

                let tr = document.createElement("tr");
                tr.innerHTML = trHTML;
                tr.id = (field_data.guid + "-tr");
                tr.setAttribute("data-instance-id", instance_id);
                tr.setAttribute("data-guid", field_data.guid);

                fieldsContainer.appendChild(tr);

                $(document.getElementById(field_data.guid + "-tr")).data("field_data", field_data);
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