<template>
    <div>
        <div id="divWidgetConditionDialog" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{ $t('Condition') }}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-primary btn-md btn-on-card text-white float-sm-right" @click="showCustomVariableList">
                                    <i class="fas fa-list" aria-hidden="true"></i> <span>{{ $t('Custom Variables') }}</span>
                                </button>
                            </div>
                        </div>
                        <div class="jsquerybuilder-container" id="widgetConditionBuilderContainer"></div>
                    </div>
                    <div class="modalfooter justify-content-between show_by_permission_must_update">
                        <div class="row">
                            <div class="col-md-12">
                                <button
                                    type="button"
                                    id="buttonSaveWidgetCondition"
                                    @click="saveCondition"
                                    data-guid=""
                                    data-target-instance-id=""
                                    class="btn btn-success btn-md btn-on-table float-right">
                                    {{ $t('Save') }}
                                </button>
                                <button type="button" class="btn btn-outline-secondary float-left" data-dismiss="modal" style="margin-right:10px;">{{ $t('Close') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script id="action-operation-condition-rule-template-footer" type="text/html">
            <input class="form-control action-operation-condition-rule-constant"
                    type="text"
                    id="%id%_constant"
                    data-rule-name="%id%" />
        </script>
        <script id="action-operation-condition-rule-template-header" type="text/html">
            <select class="form-control action-operation-condition-rule-type" id="%id%_type" data-rule-name="%id%">
                <option value="1" selected>Constant</option>
                <option value="2">Variable</option>
            </select>
            <select class="form-control d-none action-operation-condition-rule-variable"
                    id="%id%_variable"
                    data-rule-name="%id%">
        </script>
        <script id="action-operation-condition-rule-template-footer" type="text/html">
            </select>
            <input class="form-control action-operation-condition-rule-constant"
                    type="text"
                    id="%id%_constant"
                    data-rule-name="%id%" />
        </script>
        <script id="action-operation-list-item-template" type="text/html">
            <span class="action-operation-title">%title%</span><br>
            <span class="action-operation-result">%result%</span>
            <span class="action-operation-parameters">%parameters%</span>
        </script>

        <script id="condition-table-template" type="text/html">
            <table class="table table-bordered table-hover table-sm condition-table"
                data-guid="__guid__" 
                id="__guid__-table" 
                data-condition-json='__condition_json__'>
                <thead>
                    <tr>
                        <th colspan="2" id="__guid__-condition-html">__condition_html__</th>
                        <th>
                            <button type="button" title="Edit Condition" class="btn-icon btn-icon-primary btn-edit-condition" data-guid="__guid__">
                                <span class="btn-label btn-label-right"><i class="fas fa-pen"></i></span>
                            </button>
                            <button type="button" title="Remove Condition" class="btn-icon btn-icon-danger btn-remove-condition" data-guid="__guid__">
                                <span class="btn-label btn-label-right"><i class="fas fa-times"></i></span>
                            </button>
                        </th>
                    </tr>
                </thead>
                <tbody>__conditional_fields_html__</tbody>
            </table>
        </script>
        <script id="conditional-field-row-template" type="text/html">
            <tr>
                <td>__label__</td>
                <td id="__field_guid__-html">__value__</td>
                <td>
                    <button type="button" title="Edit Value" 
                        class="btn-icon btn-icon-primary btn-edit-field" 
                        id="__field_guid__-btn"
                        data-field-guid="__field_guid__" 
                        data-field-json='__field_json__'>
                        <span class="btn-label btn-label-right"><i class="fas fa-pen"></i></span>
                    </button>
                </td>
            </tr>
        </script>

        <div id="divEditFieldDialog" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{ $t('Field') }}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row d-none" id="__cv_field_container__-text">
                            <div class="form-group col-lg-12">
                                <label for="__cv_field__-text" class="detail-label">
                                    <span id="__cv_field_label__-text"></span>
                                    <insert-variable-button 
                                        :variable_options="['query_result_fields','global_parameters','user_parameters','url_parameters','request_parameters']" 
                                        target="__cv_field__-text">
                                    </insert-variable-button>
                                </label>
                                <input type="text" class="form-control " id="__cv_field__-text">
                            </div>
                        </div>
                    </div>
                    <div class="modalfooter justify-content-between show_by_permission_must_update">
                        <div class="row">
                            <div class="col-md-12">
                                <button
                                    type="button"
                                    id="buttonSaveField"
                                    @click="saveField"
                                    data-field-guid=""
                                    class="btn btn-success btn-md btn-on-table float-right">
                                    {{ $t('Save') }}
                                </button>
                                <button type="button" class="btn btn-outline-secondary float-left" data-dismiss="modal" style="margin-right:10px;">{{ $t('Close') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    /* props: ["pagename"],
    watch: {
        pagename: function(pagename) {
            this.pagename = pagename;
            this.processLoadQueue();
        }
    },*/
    data() {
        return {
            instance_id: "",
            conditional_fields: [],
            model_options: [],
            property_options: [],
            global_parameter_options: [],
            user_parameter_options: [],
            custom_variable_options: [],
            page: {
                is_ready: false,
                has_server_error: false,
                is_post_success: false,
                is_global_parameter_options_loading: false,
                is_global_parameter_options_loaded: false,
                is_user_parameter_options_loading: false,
                is_user_parameter_options_loaded: false,
                is_custom_variable_options_loading: false,
                is_custom_variable_options_loaded: false,
                external_files: [
                    ("/js/adminlte/jsquerybuilder/css/query-builder.default.min-custom.css"),
                    ("/js/adminlte/jsquerybuilder/js/query-builder.standalone.min.js")
                ],
            }
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
                self.load_custom_variable_options(function(){});
            }
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
        load_custom_variable_options: function(callback) {
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
                }).finally(function() {
                    if (!self.page.has_server_error) {
                        callback();
                    }
                });
        },
        showConditionDialog: function(instance_id, conditional_fields) {
            this.instance_id = instance_id;
            this.conditional_fields = conditional_fields;

            document.getElementById("buttonSaveWidgetCondition").setAttribute("data-guid", "");

            this.initializeJSQueryBuilder(document.getElementById("widgetConditionBuilderContainer"), null);

            $("#divWidgetConditionDialog").modal();
        },
        saveCondition: function() {
            var self = this;
            var newCondition = false;
            var guid = document.getElementById("buttonSaveWidgetCondition").getAttribute("data-guid");
            if ("" == guid) {
                newCondition = true;
                guid = AdminLTEHelper.generateGUID("condition");
            }

            var conditionJSON = JSON.stringify($("#widgetConditionBuilderContainer").queryBuilder("getRules", { allow_invalid: true }));

            if (newCondition) {
                var tableTemplateHTML = document.getElementById("condition-table-template").innerHTML;
                var tbodyHTML = "";
                var trTemplateHTML = document.getElementById("conditional-field-row-template").innerHTML;
                let fieldValues = window.mainLayoutInstance.pageWidgets[this.instance_id].content_settings.getWidgetFormValues();

                self.conditional_fields.forEach(field => {
                    field.value = fieldValues[field.id];

                    let field_guid = AdminLTEHelper.generateGUID("field");

                    let trHTML = trTemplateHTML
                        .replace(/__field_guid__/g, field_guid)
                        .replace(/__label__/g, field.label)
                        .replace(/__value__/g, field.value)
                        .replace(/__field_json__/g, JSON.stringify(field));
                        
                    tbodyHTML += trHTML;
                });

                var tableHTML = tableTemplateHTML
                    .replace(/__guid__/g, guid)
                    .replace(/__condition_json__/g, conditionJSON)
                    .replace(/__condition_html__/g, self.getConditionBeautyHTML(conditionJSON))
                    .replace(/__conditional_fields_html__/g, tbodyHTML);

                document.getElementById(self.instance_id + "-conditionlist").innerHTML += tableHTML;

                $(".btn-edit-condition").off("click").on("click", function () {
                    self.doEditCondition(this);
                });

                $(".btn-remove-condition").off("click").on("click", function () {
                    self.doRemoveCondition(this);
                });

                $(".btn-edit-field").off("click").on("click", function () {
                    self.doEditField(this);
                });
            } else {
                document.getElementById(guid + "-table").setAttribute("data-condition-json", conditionJSON);
                document.getElementById(guid + "-condition-html").innerHTML = self.getConditionBeautyHTML(conditionJSON);
            }

            $("#divWidgetConditionDialog").modal("hide");
        },
        doEditCondition: function(elButton) {
            var guid = elButton.getAttribute("data-guid");

            document.getElementById("buttonSaveWidgetCondition").setAttribute("data-guid", guid);

            var conditionJSON = document.getElementById(guid + "-table").getAttribute("data-condition-json");
            this.initializeJSQueryBuilder(document.getElementById("widgetConditionBuilderContainer"), JSON.parse(conditionJSON));

            $("#divWidgetConditionDialog").modal();
        },
        doRemoveCondition: function(elButton) {
            var guid = elButton.getAttribute("data-guid");
            document.getElementById(guid + "-table").remove();
        },
        doEditField: function(elButton) {
            var guid = elButton.getAttribute("data-field-guid");

            document.getElementById("buttonSaveField").setAttribute("data-field-guid", guid);

            var fieldJSON = elButton.getAttribute("data-field-json");
            var objectField = JSON.parse(fieldJSON);
            var fieldType = objectField.type;

            $(document.getElementById("__cv_field_container__-" + fieldType)).removeClass("d-none");
            document.getElementById("__cv_field_label__-" + fieldType).innerHTML = objectField.label;
            document.getElementById("__cv_field__-" + fieldType).value = objectField.value;

            $("#divEditFieldDialog").modal();
        },
        saveField: function() {
            var guid = document.getElementById("buttonSaveField").getAttribute("data-field-guid");
            var elEditButton = document.getElementById(guid + "-btn");

            var fieldJSON = elEditButton.getAttribute("data-field-json");
            var objectField = JSON.parse(fieldJSON);

            objectField.value = document.getElementById("__cv_field__-" + objectField.type).value;
            fieldJSON = JSON.stringify(objectField);
            elEditButton.setAttribute("data-field-json", fieldJSON);

            document.getElementById(guid + "-html").innerHTML = objectField.value;

            $("#divEditFieldDialog").modal("hide");
        },
        // Helpers
        initializeJSQueryBuilder: function(container, condition) {
            var self = this;
            var options = [];
            var filters = [];
            
            var operators = [
                "equal",
                "not_equal",
                "in",
                "not_in",
                "less",
                "less_or_equal",
                "greater",
                "greater_or_equal",
                "begins_with",
                "not_begins_with",
                "contains",
                "not_contains",
                "ends_with",
                "not_ends_with",
                "is_empty",
                "is_not_empty",
                "is_null",
                "is_not_null",
                "is_integer",
                "is_not_integer",
                "is_numeric",
                "is_not_numeric",
                "matching_regex",
                "not_matching_regex"
            ];

            // Global Parameters
            options = self.global_parameter_options;
            var variableCount = options.length;
            for (var i = 0; i < variableCount; i++) {
                filters.push({
                    "id": options[i]["id"],
                    "label": options[i]["text"],
                    "type": "string",
                    "optgroup": "Global Parameters",
                    "operators": operators,
                    "input": self.inputJSQueryBuilder,
                    "valueSetter": self.setJSQueryBuilderInputValue,
                    "valueGetter": self.getJSQueryBuilderInputValue,
                    "input_event": ""
                });
            }

            // User Parameters
            options = self.user_parameter_options;
            var variableCount = options.length;
            for (var i = 0; i < variableCount; i++) {
                filters.push({
                    "id": options[i]["id"],
                    "label": options[i]["text"],
                    "type": "string",
                    "optgroup": "User Parameters",
                    "operators": operators,
                    "input": self.inputJSQueryBuilder,
                    "valueSetter": self.setJSQueryBuilderInputValue,
                    "valueGetter": self.getJSQueryBuilderInputValue,
                    "input_event": ""
                });
            }

            // Custom Variables
            options = self.custom_variable_options;
            var variableCount = options.length;
            for (var i = 0; i < variableCount; i++) {
                filters.push({
                    "id": options[i]["id"],
                    "label": options[i]["text"],
                    "type": "string",
                    "optgroup": "Custom Variables",
                    "operators": operators,
                    "input": self.inputJSQueryBuilder,
                    "valueSetter": self.setJSQueryBuilderInputValue,
                    "valueGetter": self.getJSQueryBuilderInputValue,
                    "input_event": ""
                });
            }
            
            if ($(container).queryBuilder != undefined) {
                $(container).queryBuilder("destroy");
                container.innerHTML = "";
            }

            if (condition != null) {
                if (0 == condition.rules.length) {
                    condition = null;
                } else if (null == condition.rules[0].field) {
                    condition = null;
                }
            }

            $(container).queryBuilder({
                plugins: [],
                validation: {
                    allow_empty_value: true
                },
                filters: filters,
                rules: condition
            });

            $(container).on("change", function () {
                self.changeJSQueryBuilderContainer(this);
            });
        },
        inputJSQueryBuilder: function (rule, name) {
            var self = this;
            var options = [];
            var variableCount = 0;
            options.push.apply(options, self.global_parameter_options);
            options.push.apply(options, self.user_parameter_options);
            options.push.apply(options, self.custom_variable_options);

            var templateHeaderHTML = document
                    .getElementById(
                    "action-operation-condition-rule-template-header")
                    .innerHTML;
            var templateFooterHTML = document
                    .getElementById(
                    "action-operation-condition-rule-template-footer")
                    .innerHTML;
            var variableOptionsHTML = "";

            templateHeaderHTML = templateHeaderHTML.replace(/%id%/gi, name);
            templateFooterHTML = templateFooterHTML.replace(/%id%/gi, name);

            variableOptionsHTML += '<optgroup label="Global Parameters">';
            options = self.global_parameter_options;
            variableCount = options.length;

            for (var i = 0; i < variableCount; i++) {
                variableOptionsHTML += "<option value=\""
                        + options[i]["id"]
                        + "\">"
                        + options[i]["text"]
                        + "</option>";
            }
            variableOptionsHTML += '</optgroup>';

            variableOptionsHTML += '<optgroup label="User Parameters">';
            options = self.user_parameter_options;
            variableCount = options.length;

            for (var i = 0; i < variableCount; i++) {
                variableOptionsHTML += "<option value=\""
                        + options[i]["id"]
                        + "\">"
                        + options[i]["text"]
                        + "</option>";
            }
            variableOptionsHTML += '</optgroup>';

            variableOptionsHTML += '<optgroup label="Custom Variables">';
            options = self.custom_variable_options;
            variableCount = options.length;

            for (var i = 0; i < variableCount; i++) {
                variableOptionsHTML += "<option value=\""
                        + options[i]["id"]
                        + "\">"
                        + options[i]["text"]
                        + "</option>";
            }
            variableOptionsHTML += '</optgroup>';

            return (templateHeaderHTML
                    + variableOptionsHTML
                    + templateFooterHTML);
        },
        setJSQueryBuilderInputValue: function (rule, value) {
            var self = this;
            var name = rule.id + "_value_0";
            var type = 1;

            if ("" == value) {
                $("#" + name + "_type").val(1);
                $("#" + name + "_constant").val(value);
            } else {
                type = value[0];
                value = value.substr(2);

                $("#" + name + "_type").val(type);

                if (1 == type) {
                    $("#" + name + "_constant").val(value);
                } else {
                    $("#" + name + "_variable").val(value);
                }
            }

            var targetElement = document.getElementById(name + "_type");
            if (targetElement != undefined) {
                self.changeConditionRuleType(targetElement);
            }
        },
        getJSQueryBuilderInputValue: function (rule) {
            var name = rule.id + "_value_0";
            var type = $("#" + name + "_type").val();
            var value = "";

            if (1 == type) {
                value = ("1;" + $("#" + name + "_constant").val());
            } else {
                value = ("2;" + $("#" + name + "_variable").val());
            }
            return value;
        },
        changeJSQueryBuilderContainer: function (sender) {
            var self = this;
            $(".action-operation-condition-rule-type", sender).off("change").on("change", function () {
                self.changeConditionRuleType(this);
            });
        },
        changeConditionRuleType: function (sender) {
            var type = $(sender).val();
            var name = sender.getAttribute("data-rule-name");
            var inputElement = document.getElementById(name + "_constant");
            var selectElement = document.getElementById(name + "_variable");

            $(inputElement).addClass("d-none");
            $(selectElement).addClass("d-none");

            if (type == 1) {
                $(inputElement).removeClass("d-none");
            } else {
                $(selectElement).removeClass("d-none");
            }
        },
        refreshCustomVariables: function() {
            var self = this;
            self.load_custom_variable_options(function(){
                self.refreshFilters();
            })
        },
        refreshFilters: function() {
            var self = this;
            var options = [];
            var filters = [];
            
            var operators = [
                "equal",
                "not_equal",
                "in",
                "not_in",
                "less",
                "less_or_equal",
                "greater",
                "greater_or_equal",
                "begins_with",
                "not_begins_with",
                "contains",
                "not_contains",
                "ends_with",
                "not_ends_with",
                "is_empty",
                "is_not_empty",
                "is_null",
                "is_not_null",
                "is_integer",
                "is_not_integer",
                "is_numeric",
                "is_not_numeric",
                "matching_regex",
                "not_matching_regex"
            ];

            // Global Parameters
            options = self.global_parameter_options;
            var variableCount = options.length;
            for (var i = 0; i < variableCount; i++) {
                filters.push({
                    "id": options[i]["id"],
                    "label": options[i]["text"],
                    "type": "string",
                    "optgroup": "Global Parameters",
                    "operators": operators,
                    "input": self.inputJSQueryBuilder,
                    "valueSetter": self.setJSQueryBuilderInputValue,
                    "valueGetter": self.getJSQueryBuilderInputValue,
                    "input_event": ""
                });
            }

            // User Parameters
            options = self.user_parameter_options;
            var variableCount = options.length;
            for (var i = 0; i < variableCount; i++) {
                filters.push({
                    "id": options[i]["id"],
                    "label": options[i]["text"],
                    "type": "string",
                    "optgroup": "User Parameters",
                    "operators": operators,
                    "input": self.inputJSQueryBuilder,
                    "valueSetter": self.setJSQueryBuilderInputValue,
                    "valueGetter": self.getJSQueryBuilderInputValue,
                    "input_event": ""
                });
            }

            // Custom Variables
            options = self.custom_variable_options;
            var variableCount = options.length;
            for (var i = 0; i < variableCount; i++) {
                filters.push({
                    "id": options[i]["id"],
                    "label": options[i]["text"],
                    "type": "string",
                    "optgroup": "Custom Variables",
                    "operators": operators,
                    "input": self.inputJSQueryBuilder,
                    "valueSetter": self.setJSQueryBuilderInputValue,
                    "valueGetter": self.getJSQueryBuilderInputValue,
                    "input_event": ""
                });
            }

            $("#widgetConditionBuilderContainer").queryBuilder("setFilters", filters);
        },
        showCustomVariableList: function() {
            $("#modalCustomVariableList").modal();
        },
        renderConditionList: function(instance_id, conditionalData) {
            var self = this;
            var listHTML = "";
            var tableHTML = "";

            conditionalData.forEach(conditionData => {
                tableHTML = self.getConditionTableHTML(conditionData);
                listHTML += tableHTML;
            });

            document.getElementById(instance_id + "-conditionlist").innerHTML = listHTML;

            $(".btn-edit-condition").off("click").on("click", function () {
                self.doEditCondition(this);
            });

            $(".btn-remove-condition").off("click").on("click", function () {
                self.doRemoveCondition(this);
            });

            $(".btn-edit-field").off("click").on("click", function () {
                self.doEditField(this);
            });
        },
        getConditionTableHTML: function(conditionData) {
            var self = this;
            var tableTemplateHTML = document.getElementById("condition-table-template").innerHTML;
            var tbodyHTML = "";
            var trTemplateHTML = document.getElementById("conditional-field-row-template").innerHTML;
            var guid = conditionData.guid;

            var condition = conditionData.condition_json;
            var conditionJSON = JSON.stringify(condition);

            var conditionalFields = conditionData.conditional_fields;

            for (let index = 0; index < conditionalFields.length; index++) {
                const field = conditionalFields[index];

                let field_guid = AdminLTEHelper.generateGUID("field");

                let trHTML = trTemplateHTML
                    .replace(/__field_guid__/g, field_guid)
                    .replace(/__label__/g, field.label)
                    .replace(/__value__/g, field.value)
                    .replace(/__field_json__/g, JSON.stringify(field));
                    
                tbodyHTML += trHTML;
            }

            var tableHTML = tableTemplateHTML
                .replace(/__guid__/g, guid)
                .replace(/__condition_json__/g, conditionJSON)
                .replace(/__condition_html__/g, self.getConditionBeautyHTML(conditionJSON))
                .replace(/__conditional_fields_html__/g, tbodyHTML);

            return tableHTML;
        },
        getConditionBeautyHTML: function(condition_json) {
            var conditionHTML = "";

            var conditionData = JSON.parse(condition_json);
            var rules = conditionData.rules;
            var rule = rules[0];
            var leftSide = rule.field;
            var operator = rule.operator;
            var rightSide = "";
            var tempParts = [];

            switch (operator) {
                case "equal":
                    tempParts = rule.value.split(";");
                    if ("1" == tempParts[0]) {
                        // constant
                        rightSide = tempParts[1];
                    } else {
                        // variable
                        rightSide = tempParts[1];
                    }

                    conditionHTML = leftSide + "<span> EQUAL </span>" + rightSide;
                    break;

                case "not_equal":
                    tempParts = rule.value.split(";");
                    if ("1" == tempParts[0]) {
                        // constant
                        rightSide = tempParts[1];
                    } else {
                        // variable
                        rightSide = tempParts[1];
                    }

                    conditionHTML = leftSide + "<span> NOT EQUAL </span>" + rightSide;
                    break;

                case "in":
                    tempParts = rule.value.split(";");
                    if ("1" == tempParts[0]) {
                        // constant
                        rightSide = tempParts[1];
                    } else {
                        // variable
                        rightSide = tempParts[1];
                    }

                    conditionHTML = leftSide + "<span> IN </span>" + rightSide;
                    break;

                case "not_in":
                    tempParts = rule.value.split(";");
                    if ("1" == tempParts[0]) {
                        // constant
                        rightSide = tempParts[1];
                    } else {
                        // variable
                        rightSide = tempParts[1];
                    }

                    conditionHTML = leftSide + "<span> NOT IN </span>" + rightSide;
                    break;

                case "less":
                    tempParts = rule.value.split(";");
                    if ("1" == tempParts[0]) {
                        // constant
                        rightSide = tempParts[1];
                    } else {
                        // variable
                        rightSide = tempParts[1];
                    }

                    conditionHTML = leftSide + "<span> LESS THAN </span>" + rightSide;
                    break;

                case "less_or_equal":
                    tempParts = rule.value.split(";");
                    if ("1" == tempParts[0]) {
                        // constant
                        rightSide = tempParts[1];
                    } else {
                        // variable
                        rightSide = tempParts[1];
                    }

                    conditionHTML = leftSide + "<span> LESS THAN OR EQUAL </span>" + rightSide;
                    break;

                case "greater":
                    tempParts = rule.value.split(";");
                    if ("1" == tempParts[0]) {
                        // constant
                        rightSide = tempParts[1];
                    } else {
                        // variable
                        rightSide = tempParts[1];
                    }

                    conditionHTML = leftSide + "<span> GREATER THAN </span>" + rightSide;
                    break;

                case "greater_or_equal":
                    tempParts = rule.value.split(";");
                    if ("1" == tempParts[0]) {
                        // constant
                        rightSide = tempParts[1];
                    } else {
                        // variable
                        rightSide = tempParts[1];
                    }

                    conditionHTML = leftSide + "<span> GREATER THAN OR EQUAL </span>" + rightSide;
                    break;

                case "begins_with":
                    tempParts = rule.value.split(";");
                    if ("1" == tempParts[0]) {
                        // constant
                        rightSide = tempParts[1];
                    } else {
                        // variable
                        rightSide = tempParts[1];
                    }

                    conditionHTML = leftSide + "<span> BEGINS WITH </span>" + rightSide;
                    break;

                case "not_begins_with":
                    tempParts = rule.value.split(";");
                    if ("1" == tempParts[0]) {
                        // constant
                        rightSide = tempParts[1];
                    } else {
                        // variable
                        rightSide = tempParts[1];
                    }

                    conditionHTML = leftSide + "<span> NOT BEGINS WITH </span>" + rightSide;
                    break;

                case "contains":
                    tempParts = rule.value.split(";");
                    if ("1" == tempParts[0]) {
                        // constant
                        rightSide = tempParts[1];
                    } else {
                        // variable
                        rightSide = tempParts[1];
                    }

                    conditionHTML = leftSide + "<span> CONTAINS </span>" + rightSide;
                    break;

                case "not_contains":
                    tempParts = rule.value.split(";");
                    if ("1" == tempParts[0]) {
                        // constant
                        rightSide = tempParts[1];
                    } else {
                        // variable
                        rightSide = tempParts[1];
                    }

                    conditionHTML = leftSide + "<span> NOT CONTAINS </span>" + rightSide;
                    break;

                case "ends_with":
                    tempParts = rule.value.split(";");
                    if ("1" == tempParts[0]) {
                        // constant
                        rightSide = tempParts[1];
                    } else {
                        // variable
                        rightSide = tempParts[1];
                    }

                    conditionHTML = leftSide + "<span> ENDS WITH </span>" + rightSide;
                    break;

                case "not_ends_with":
                    tempParts = rule.value.split(";");
                    if ("1" == tempParts[0]) {
                        // constant
                        rightSide = tempParts[1];
                    } else {
                        // variable
                        rightSide = tempParts[1];
                    }

                    conditionHTML = leftSide + "<span> NOT ENDS WITH </span>" + rightSide;
                    break;

                case "is_empty":
                    conditionHTML = leftSide + "<span> IS EMPTY </span>";
                    break;

                case "is_not_empty":
                    conditionHTML = leftSide + "<span> IS NOT EMPTY </span>";
                    break;

                case "is_null":
                    conditionHTML = leftSide + "<span> IS NULL </span>";
                    break;

                case "is_not_null":
                    conditionHTML = leftSide + "<span> IS NOT NULL </span>";
                    break;

                case "is_integer":
                    conditionHTML = leftSide + "<span> IS INTEGER </span>";
                    break;

                case "is_not_integer":
                    conditionHTML = leftSide + "<span> IS NOT INTEGER </span>";
                    break;

                case "is_numeric":
                    conditionHTML = leftSide + "<span> IS NUMERIC </span>";
                    break;

                case "is_not_numeric":
                    conditionHTML = leftSide + "<span> IS NOT NUMERIC </span>";
                    break;

                case "matching_regex":
                    tempParts = rule.value.split(";");
                    if ("1" == tempParts[0]) {
                        // constant
                        rightSide = tempParts[1];
                    } else {
                        // variable
                        rightSide = tempParts[1];
                    }

                    conditionHTML = leftSide + "<span> MATCHING REGEX </span>" + rightSide;
                    break;

                case "not_matching_regex":
                    tempParts = rule.value.split(";");
                    if ("1" == tempParts[0]) {
                        // constant
                        rightSide = tempParts[1];
                    } else {
                        // variable
                        rightSide = tempParts[1];
                    }

                    conditionHTML = leftSide + "<span> NOT MATCHING REGEX </span>" + rightSide;
                    break;

                default:
                    conditionHTML = condition_json;
                    break;
            }

            if (rules.length > 1) {
                conditionHTML = conditionHTML + "<span> ... </span>";
            }

            return conditionHTML;                
        }
    },
    mounted() {
        this.processLoadQueue();
        window.widgetConditionDialog = this;
        AdminLTEHelper.loadExternalFiles(this.page.external_files);
    }
}
</script>