<template>
    <div>
        <div class="modal fade" id="ModalInsertVariableToTarget" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form>
                        <div class="modal-header">
                            <h4 class="modal-title">{{ $t('Insert Variable') }}</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row mt-1">
                                <div class="form-group col-lg-12">
                                    <label for="__iv__type" class="detail-label">{{ $t('Variable Type') }}</label>
                                    <select id="__iv__type" v-model="insertForm.variable_type" class="form-control">
                                        <option value=""></option>
                                        <option value="model_property">{{ $t('Model Property') }}</option>
                                        <option value="query_result_fields" data-prefix="QueryResultFields">{{ $t('Query Result Fields') }}</option>
                                        <option value="custom_variables">{{ $t('Custom Variables') }}</option>
                                        <option value="global_parameters">{{ $t('Global Parameters') }}</option>
                                        <option value="user_parameters">{{ $t('User Parameters') }}</option>
                                        <option value="url_parameters">{{ $t('URL Parameters') }}</option>
                                        <option value="request_parameters">{{ $t('Request Parameters') }}</option>
                                    </select>
                                </div>
                            </div>

                            <div v-show="('model_property' == insertForm.variable_type)" class="row">
                                <div class="form-group col-lg-12">
                                    <label for="__iv__model" class="detail-label">{{ $t('Model') }}</label>
                                    <select id="__iv__model"  class="form-control">
                                        <option v-for="(model, index) in model_options" :key="index" :value="model.id">
                                            {{model.text}}
                                        </option>
                                    </select>
                                </div>
                                <div class="form-group col-lg-12">
                                    <label for="__iv__property" class="detail-label">{{ $t('Property') }}</label>
                                    <select id="__iv__property"  class="form-control">
                                        <option v-for="(property, index) in property_options" :key="index" :value="property.id">
                                            {{property.text}}
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div v-show="('query_result_fields' == insertForm.variable_type)" class="row">
                                <div class="form-group col-lg-12">
                                    <label for="__iv__query_result_field" class="detail-label">{{ $t('Query Variable') }}</label>
                                    <input type="text" class="form-control " id="__iv__query_result_field">
                                </div>
                                <!-- <div class="col-lg-12">
                                    <div class="text-muted" style="font-size:14px;" v-pre>
                                        - "result" is single variable in simple calculation.<br>
                                        - Every query column can be use come from advanced calculation.<br>
                                    </div>
                                </div> -->
                            </div>

                            <div v-show="('custom_variables' == insertForm.variable_type)" class="row">
                                <div class="form-group col-lg-12">
                                    <label for="__iv__custom_variable" class="detail-label">{{ $t('Custom Variable') }}</label>
                                    <select2-element class="select2-element"
                                        id="__iv__custom_variable"
                                        name="__iv__custom_variable"
                                        :options="custom_variable_options">
                                    </select2-element>
                                </div>
                            </div>

                            <div v-show="('global_parameters' == insertForm.variable_type)" class="row">
                                <div class="form-group col-lg-12">
                                    <label for="__iv__global_parameter" class="detail-label">{{ $t('Config Parameter') }}</label>
                                    <select2-element class="select2-element"
                                        id="__iv__global_parameter"
                                        name="__iv__global_parameter"
                                        :options="global_parameter_options">
                                    </select2-element>
                                </div>
                            </div>

                            <div v-show="('user_parameters' == insertForm.variable_type)" class="row">
                                <div class="form-group col-lg-12">
                                    <label for="__iv__user_parameter" class="detail-label">{{ $t('Config Parameter') }}</label>
                                    <select2-element class="select2-element"
                                        id="__iv__user_parameter"
                                        name="__iv__user_parameter"
                                        :options="user_parameter_options">
                                    </select2-element>
                                </div>
                            </div>

                            <div v-show="('url_parameters' == insertForm.variable_type)" class="row">
                                <div class="form-group col-lg-12">
                                    <label for="__iv__url_parameter" class="detail-label">{{ $t('Parameter Index') }}</label>
                                    <select id="__iv__url_parameter"  class="form-control">
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
                                    </select>
                                </div>
                            </div>

                            <div v-show="('request_parameters' == insertForm.variable_type)" class="row">
                                <div class="form-group col-lg-12">
                                    <label for="__iv__request_parameter" class="detail-label">{{ $t('Parameter Name') }}</label>
                                    <input type="text" class="form-control " id="__iv__request_parameter">
                                </div>
                                <!-- <div class="col-lg-12">
                                    <div class="text-muted" style="font-size:14px;" v-pre>
                                        - "result" is single variable in simple calculation.<br>
                                        - Every query column can be use come from advanced calculation.<br>
                                    </div>
                                </div> -->
                            </div>
                        </div>
                        <div class="modalfooter justify-content-between">
                            <div class="row">
                                <div class="col-md-12">
                                    <button type="button"
                                        id="buttonInsertVariableToTarget"
                                        data-target=""
                                        class="btn btn-success btn-md btn-on-table float-right"
                                        @click="insertVariableToTarget">
                                        {{ $t('Insert') }}
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary float-right" data-dismiss="modal" style="margin-right:10px;">{{ $t('Cancel') }}</button>                                    
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            insertForm: new Form({
                variable_type: "",
                variable_text: "",
            }),
            model_options: [],
            property_options: [],
            global_parameter_options: [],
            user_parameter_options: [],
            custom_variable_options: [],
            page: {
                is_ready: false,
                has_server_error: false,
                is_model_options_loading: false,
                is_model_options_loaded: false,
                is_property_options_loading: false,
                is_property_options_loaded: false,
                is_global_parameter_options_loading: false,
                is_global_parameter_options_loaded: false,
                is_user_parameter_options_loading: false,
                is_user_parameter_options_loaded: false,
                is_custom_variable_options_loading: false,
                is_custom_variable_options_loaded: false,
                external_files: [
                    ("/js/adminlte/bootstrap-iconpicker/css/bootstrap-iconpicker.min.css"),
                    ("/js/adminlte/bootstrap-iconpicker/js/iconset/fontawesome5-3-1.min.js"),
                    ("/js/adminlte/bootstrap-iconpicker/js/bootstrap-iconpicker.min.js"),
                    ("/js/adminlte/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css"),
                    ("/js/adminlte/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"),
                ],
            },
            show_calculation_type_simple: true,
        };
    },
    methods: {
        processLoadQueue: function () {
            var self = this;

            if (self.page.has_server_error) {
                self.$Progress.finish();
                self.page.is_ready = true;
                return;
            }

            if (!self.page.is_model_options_loaded 
                && !self.page.is_global_parameter_options_loaded
                && !self.page.is_user_parameter_options_loaded
                && !self.page.is_custom_variable_options_loaded) {
                self.$Progress.start();
            }
            
            if (!self.page.is_model_options_loaded) {
                self.load_model_options();
            }
            
            if (!self.page.is_global_parameter_options_loaded) {
                self.load_global_parameter_options();
            }

            if (!self.page.is_user_parameter_options_loaded) {
                self.load_user_parameter_options();
            }

            if (!self.page.is_custom_variable_options_loaded) {
                self.load_custom_variable_options();
            }
            
            self.initializePage();
        },
        load_model_options: function() {
            var self = this;
            if (self.page.is_model_options_loading) {
                return;
            }

            self.page.is_model_options_loading = true;
            
            axios.get(AdminLTEHelper.getAPIURL("__layout/get_model_list"))
                .then(({ data }) => {
                    self.page.is_model_options_loaded = true;
                    self.page.is_model_options_loading = false;
                    self.model_options = data.list;
                    self.processLoadQueue();
                }).catch(({ data }) => {
                    self.page.is_model_options_loaded = true;
                    self.page.is_model_options_loading = false;
                    self.$Progress.fail();
                    self.page.has_server_error = true;
                    self.processLoadQueue();
                });
        },
        load_property_options: function(model) {
            var self = this;
            if (self.page.is_property_options_loading) {
                return;
            }

            self.page.is_property_options_loading = true;

            axios.get(AdminLTEHelper.getAPIURL("__layout/get_model_properties/" + model))
                .then(({ data }) => {
                    self.page.is_property_options_loaded = true;
                    self.page.is_property_options_loading = false;
                    self.property_options = data.list;
                    self.processLoadQueue();
                }).catch(({ data }) => {
                    self.page.is_property_options_loaded = true;
                    self.page.is_property_options_loading = false;
                    self.$Progress.fail();
                    self.processLoadQueue();
                });
        },
        load_global_parameter_options: function() {
            var self = this;
            if (self.page.is_global_parameter_options_loading) {
                return;
            }

            self.page.is_global_parameter_options_loading = true;
            
            axios.get(AdminLTEHelper.getAPIURL("adminlte/get_global_parameter_options"))
                .then(({ data }) => {
                    self.page.is_global_parameter_options_loaded = true;
                    self.page.is_global_parameter_options_loading = false;
                    self.global_parameter_options = data.list;
                    self.processLoadQueue();
                }).catch(({ data }) => {
                    self.page.is_global_parameter_options_loaded = true;
                    self.page.is_global_parameter_options_loading = false;
                    self.$Progress.fail();
                    self.page.has_server_error = true;
                    self.processLoadQueue();
                });
        },
        load_user_parameter_options: function() {
            var self = this;
            if (self.page.is_user_parameter_options_loading) {
                return;
            }

            self.page.is_user_parameter_options_loading = true;
            
            axios.get(AdminLTEHelper.getAPIURL("adminlte/get_user_parameter_options"))
                .then(({ data }) => {
                    self.page.is_user_parameter_options_loaded = true;
                    self.page.is_user_parameter_options_loading = false;
                    self.user_parameter_options = data.list;
                    self.processLoadQueue();
                }).catch(({ data }) => {
                    self.page.is_user_parameter_options_loaded = true;
                    self.page.is_user_parameter_options_loading = false;
                    self.$Progress.fail();
                    self.page.has_server_error = true;
                    self.processLoadQueue();
                });
        },
        load_custom_variable_options: function() {
            var self = this;
            if (self.page.is_custom_variable_options_loading) {
                return;
            }

            self.page.is_custom_variable_options_loading = true;
            
            axios.get(AdminLTEHelper.getAPIURL("adminlte/get_custom_variable_options"))
                .then(({ data }) => {
                    self.page.is_custom_variable_options_loaded = true;
                    self.page.is_custom_variable_options_loading = false;
                    self.custom_variable_options = data.list;
                    self.processLoadQueue();
                }).catch(({ data }) => {
                    self.page.is_custom_variable_options_loaded = true;
                    self.page.is_custom_variable_options_loading = false;
                    self.$Progress.fail();
                    self.page.has_server_error = true;
                    self.processLoadQueue();
                });
        },
        initializePage: function() {
            var self = this;

            $(document.getElementById("__iv__model")).off("change").on("change", function (e) {
                self.load_property_options(this.value);
            });
        },
        setWidgetFormValues: function() {
            var self = this;
            var instance_id = self.instance_id;
            var data = window.mainLayoutInstance.pageWidgets[instance_id].data;

            // Style
            document.getElementById(instance_id + "title").value = data.content.title;

            var iconVal = data.content.icon;
            if ("" == iconVal || undefined === iconVal) {
                $(document.getElementById(instance_id + "icon_picker")).iconpicker('setIcon', 'empty');
            } else{
                $(document.getElementById(instance_id + "icon_picker")).iconpicker('setIcon', iconVal);
            }

            var colorPicker = document.getElementById(instance_id + "iconbackground");
            $(colorPicker).val(data.content.iconbackground);
            $(colorPicker).trigger('change');

            document.getElementById(instance_id + "redirectURL").value = data.content.redirectURL;

            // Value
            var selectorText = 'input[name="' + instance_id + 'calculation_type"][value="' + data.content.calculation_type + '"]';
            $(selectorText).prop('checked', true);

            self.show_calculation_type_simple = ("simple" == data.content.calculation_type);

            $(document.getElementById(instance_id + "model")).val(data.content.model).trigger('change');
            $(document.getElementById(instance_id + "property")).val(data.content.property).trigger('change');
            document.getElementById(instance_id + "property").setAttribute("val", data.content.property);
            $(document.getElementById(instance_id + "function")).val(data.content.function).trigger('change');
            document.getElementById(instance_id + "query").value = data.content.query;
        },
        getWidgetFormValues: function() {
            var instance_id = this.instance_id;
            let radioSelectorText = 'input[name="' + instance_id + 'calculation_type"]:checked';

            return {
                "title" : document.getElementById(instance_id + "title").value,
                "icon" : document.getElementById(instance_id + "icon").value,
                "iconbackground" : document.getElementById(instance_id + "iconbackground").value,
                "redirectURL" : document.getElementById(instance_id + "redirectURL").value,
                "calculation_type" : $(radioSelectorText).val(),
                "model" : document.getElementById(instance_id + "model").value,
                "property" : document.getElementById(instance_id + "property").value,
                "function" : document.getElementById(instance_id + "function").value,
                "query" : document.getElementById(instance_id + "query").value,
            };
        },
        insertAtCursor: function(inputField, variableText) {
            //IE support
            if (document.selection) {
                inputField.focus();
                sel = document.selection.createRange();
                sel.text = variableText;
            }
            //MOZILLA and others
            else if (inputField.selectionStart || inputField.selectionStart == '0') {
                var startPos = inputField.selectionStart;
                var endPos = inputField.selectionEnd;
                inputField.value = inputField.value.substring(0, startPos)
                    + variableText
                    + inputField.value.substring(endPos, inputField.value.length);
            } else {
                inputField.value += variableText;
            }
        },
        insertVariableToTarget: function () {
            var self = this;
            var variableType = self.insertForm.variable_type;
            var data = {};

            switch (variableType) {
                case "model_property":
                    data = self.getModelPropertyVariable();
                    break;
                case "query_result_fields":
                    data = self.getQueryResultFieldVariable();
                    break;
                case "custom_variables":
                    data = self.getCustomVariable();
                    break;   
                case "global_parameters":
                    data = self.getGlobalParameterVariable();
                    break;   
                case "user_parameters":
                    data = self.getUserParameterVariable();
                    break; 
                case "url_parameters":
                    data = self.getURLParameterVariable();
                    break;  
                case "request_parameters":
                    data = self.getRequestParameterVariable();
                    break;      
            }

            if (data.has_error) {
                Vue.swal.fire({
                    position: 'top-end',
                    title: data.msg,
                    icon: 'error',
                    showConfirmButton: false,
                    timer: 1000,
                    timerProgressBar: true,
                    onClose: () => {}
                });

                return;
            }
            
            var targetId = document.getElementById("buttonInsertVariableToTarget").getAttribute("data-target");
            if (document.getElementById(targetId)) {
                var target = document.getElementById(targetId);
                self.insertAtCursor(target, data.variable);
            }

            $("#ModalInsertVariableToTarget").modal("hide");
        },
        getModelPropertyVariable: function () {
            var result = {
                "has_error": false,
                "msg": "",
                "variable": ""
            };

            var model = $("#__iv__model").val();
            var property = $("#__iv__property").val();

            if ("" == model) {
                result.has_error = true;
                result.msg = self.$t("Please select a model.");
                return result;
            }

            if ("" == property) {
                result.has_error = true;
                result.msg = self.$t("Please select a property.");
                return result;
            }

            result.variable = "{{" + model + "/" + property + "}}";

            return result;
        },
        getQueryResultFieldVariable: function () {
            var result = {
                "has_error": false,
                "msg": "",
                "variable": ""
            };

            var variable = $("#__iv__query_result_field").val();

            if ("" == variable) {
                result.has_error = true;
                result.msg = self.$t("Please enter a query result field.");
                return result;
            }

            result.variable = "{{QueryResultFields/" + variable + "}}";

            return result;
        },
        getCustomVariable: function () {
            var result = {
                "has_error": false,
                "msg": "",
                "variable": ""
            };

            var customVariable = $("#__iv__custom_variable").val();

            if ("" == customVariable) {
                result.has_error = true;
                result.msg = self.$t("Please select a custom variable.");
                return result;
            }

            result.variable = "{{" + customVariable + "}}";

            return result;
        },
        getGlobalParameterVariable: function () {
            var result = {
                "has_error": false,
                "msg": "",
                "variable": ""
            };

            var configParameter = $("#__iv__global_parameter").val();

            if ("" == configParameter) {
                result.has_error = true;
                result.msg = self.$t("Please select a global config parameter.");
                return result;
            }

            result.variable = "{{" + configParameter + "}}";

            return result;
        },
        getUserParameterVariable: function () {
            var result = {
                "has_error": false,
                "msg": "",
                "variable": ""
            };

            var configParameter = $("#__iv__user_parameter").val();

            if ("" == configParameter) {
                result.has_error = true;
                result.msg = self.$t("Please select a user config parameter.");
                return result;
            }

            result.variable = "{{" + configParameter + "}}";

            return result;
        },
        getURLParameterVariable: function () {
            var result = {
                "has_error": false,
                "msg": "",
                "variable": ""
            };

            var urlParameterIndex = $("#__iv__url_parameter").val();

            if ("" == urlParameterIndex) {
                result.has_error = true;
                result.msg = self.$t("Please select a URL parameter index.");
                return result;
            }

            result.variable = "{{URLParameters/" + urlParameterIndex + "}}";

            return result;
        },
        getRequestParameterVariable: function () {
            var result = {
                "has_error": false,
                "msg": "",
                "variable": ""
            };

            var requestParameterName = $("#__iv__request_parameter").val();

            if ("" == requestParameterName) {
                result.has_error = true;
                result.msg = self.$t("Please enter a request parameter name.");
                return result;
            }

            result.variable = "{{RequestParameters/" + requestParameterName + "}}";

            return result;
        },
        showInsertVariableDialog: function(variable_options, target) {
            $("#__iv__type > option").hide();

            if (0 == variable_options.length) {
                $("#__iv__type > option").show();
            } else {
                variable_options.forEach(element => {
                    let selectorText = "#__iv__type > option[value='" + element + "']";
                    $(selectorText).show();
                });
            }

            document.getElementById("buttonInsertVariableToTarget").setAttribute("data-target", target);

            this.insertForm.variable_type = "";

            $("#ModalInsertVariableToTarget").off("shown.bs.modal").on("shown.bs.modal", function (e) { 
                $(document).off("focusin.modal"); 
            });

            $("#ModalInsertVariableToTarget").modal();
        }
    },
    mounted() {
        this.processLoadQueue();
        window.insertVariableDialog = this;
    }
}
</script>