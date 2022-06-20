<template>
    <div>
        <div id="__ds_conditionDialog" class="modal level2 fade" tabindex="-1" role="dialog">
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
                        <div class="jsquerybuilder-container" id="__ds_condition_builder_container"></div>
                    </div>
                    <div class="modalfooter justify-content-between show_by_permission_must_update">
                        <div class="row">
                            <div class="col-md-12">
                                <button
                                    type="button"
                                    id="button_save__ds_condition"
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

        <script id="__ds_condition-rule-template-footer" type="text/html">
            <input class="form-control condition-rule-constant"
                    type="text"
                    id="%id%_constant"
                    data-rule-name="%id%" />
        </script>
        <script id="__ds_condition-rule-template-header" type="text/html">
            <select class="form-control condition-rule-type" id="%id%_type" data-rule-name="%id%">
                <option value="1" selected>Constant</option>
                <option value="2">Variable</option>
            </select>
            <select class="form-control d-none condition-rule-variable"
                    id="%id%_variable"
                    data-rule-name="%id%">
        </script>
        <script id="__ds_condition-rule-template-footer" type="text/html">
            </select>
            <input class="form-control condition-rule-constant"
                    type="text"
                    id="%id%_constant"
                    data-rule-name="%id%" />
        </script>

        <script id="__ds_condition-template" type="text/html">
            <td class="condition-html-container" id="__guid__-html">__condition_html__</td>
            <td width="70">
                <button type="button" title="Edit Condition" class="btn-icon btn-icon-primary btn-edit-condition" data-guid="__guid__">
                    <span class="btn-label btn-label-right"><i class="fas fa-pen"></i></span>
                </button>
                <button type="button" title="Remove Condition" class="btn-icon btn-icon-danger btn-remove-condition" data-guid="__guid__">
                    <span class="btn-label btn-label-right"><i class="fas fa-times"></i></span>
                </button>
            </td>
        </script>
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
            current_fields: [],
            custom_variable_options: [],
            page: {
                is_ready: false,
                has_server_error: false,
                is_post_success: false,
                is_custom_variable_options_loading: false,
                is_custom_variable_options_loaded: false,
                external_files: [
                    ("/js/wisilo/jsquerybuilder/css/query-builder.default.min-custom.css"),
                    ("/js/wisilo/jsquerybuilder/js/query-builder.standalone.min.js"),
                    ("/js/wisilo/bootstrap-switch/js/bootstrap-switch.js"),
                    ("/js/wisilo/bootstrap-iconpicker/css/bootstrap-iconpicker.min.css"),
                    ("/js/wisilo/bootstrap-iconpicker/js/iconset/fontawesome5-3-1.min.js"),
                    ("/js/wisilo/bootstrap-iconpicker/js/bootstrap-iconpicker.min.js"),
                    ("/js/wisilo/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css"),
                    ("/js/wisilo/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"),
                    ("/js/wisilo/select2/dist/js/select2.min.js"),
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

            if (!self.page.is_custom_variable_options_loaded) {
                self.$Progress.start();
                self.load_custom_variable_options();
            } else {
                self.initializePage();
            }
        },
        initializePage: function() {
            var self = this;
            $(".textarea.vue-editor").summernote({
                "font-styles": false,
                "height": 150,
                codemirror: {
                    theme: "monokai"
                },
                callbacks: {
                    onBlur: function() {
                        this.dispatchEvent(new Event('input'));
                    }
                }
            });

            $(".color-picker").colorpicker();

            $(".color-picker").on("colorpickerChange", function(event) {
                var colorHex = event.color.toString();
                var elAppend = document.getElementById("__cv_field__colorpickerappend")
                elAppend.style.background = colorHex;
                elAppend.style.borderColor = colorHex;
            });

            var iconPickerOptions = {
                searchText: "...",
                labelHeader: "{0} / {1}",
                cols: 4, rows: 4, 
                footer: false, 
                iconset: "fontawesome5"
            };

            var iconPicker = $(".icon-picker").iconpicker(iconPickerOptions);
            iconPicker.on("change", function (e) {
                document.getElementById("__cv_field__iconpicker-value").value = e.icon;
            });
        },
        load_custom_variable_options: function() {
            var custom_variables = window.__custom_variables.list
            var options = [];

            for (let index = 0; index < custom_variables.length; index++) {
                const element = custom_variables[index];
                let option = {
                    "id" : "CustomVariables/" + element.name,
                    "text": element.title,
                    "database_id": element.id
                }

                options.push(option);
            }

            this.custom_variable_options = options;

            this.refreshFilters();

            return;
        },
        showConditionDialog: function() {
            document.getElementById("button_save__ds_condition").setAttribute("data-guid", "");
            this.initializeJSQueryBuilder(document.getElementById("__ds_condition_builder_container"), null);
            $("#__ds_conditionDialog").modal();
        },
        saveCondition: function() {
            var self = this;
            var instance_id = window.mainLayoutInstance.current_editing_widget_instance_id;
            var newCondition = false;
            var guid = document.getElementById("button_save__ds_condition").getAttribute("data-guid");
            if ("" == guid) {
                newCondition = true;
                guid = WisiloHelper.generateGUID("condition");
            }
            var conditionContainer = document.getElementById(instance_id + "__ds_simple_conditions");
            var conditionData = $("#__ds_condition_builder_container").queryBuilder("getRules", { allow_invalid: true });

            if (newCondition) {
                const conditionTemplateHTML = document.getElementById("__ds_condition-template").innerHTML;
                const conditionHTML = conditionTemplateHTML.replace(/__guid__/g, guid).replace(/__condition_html__/g, self.getConditionBeautyHTML(conditionData));

                const tr = document.createElement("tr");
                tr.innerHTML = conditionHTML;
                tr.id = (guid + "-tr");
                tr.setAttribute("data-guid", guid);

                conditionContainer.appendChild(tr);

                $(document.getElementById(guid + "-tr")).data("condition_data", conditionData);

                $(".btn-edit-condition", conditionContainer).off("click").on("click", function () {
                    self.doEditCondition(this);
                });

                $(".btn-remove-condition", conditionContainer).off("click").on("click", function () {
                    self.doRemoveCondition(this);
                });

                $(".btn-edit-field", conditionContainer).off("click").on("click", function () {
                    self.doEditField(this);
                });
            } else {
                $(document.getElementById(guid + "-tr")).data("condition_data", conditionData);
                document.getElementById(guid + "-html").innerHTML = self.getConditionBeautyHTML(conditionData);
            }

            $("#__ds_conditionDialog").modal("hide");
        },
        doEditCondition: function(elButton) {
            var guid = elButton.getAttribute("data-guid");

            document.getElementById("button_save__ds_condition").setAttribute("data-guid", guid);

            var conditionData = $(document.getElementById(guid + "-tr")).data("condition_data");
            this.initializeJSQueryBuilder(document.getElementById("__ds_condition_builder_container"), conditionData);

            $("#__ds_conditionDialog").modal();
        },
        doRemoveCondition: function(elButton) {
            var guid = elButton.getAttribute("data-guid");
            document.getElementById(guid + "-tr").remove();
        },

        getCurrentFieldVariableIds: function() {
            var ids = [];

            var instance_id = window.mainLayoutInstance.current_editing_widget_instance_id;

            if (undefined === instance_id || "" == instance_id) {
                return ids;
            }
            
            var arrTR = $("tr", document.getElementById(instance_id + "__ds_simple_fields"));
            var ids = [];

            for (let index = 0; index < arrTR.length; index++) {
                let field_data = $(arrTR[index]).data("field_data");
                ids.push(parseInt(field_data.customvariable));
            }

            return ids;
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

            // Custom Variables
            var currentFieldVariableIds = self.getCurrentFieldVariableIds();
            options = self.custom_variable_options;
            var variableCount = options.length;
            for (var i = 0; i < variableCount; i++) {
                if (currentFieldVariableIds.includes(options[i]["database_id"])) {
                    filters.push({
                        "id": options[i]["database_id"],
                        "label": options[i]["text"],
                        "type": "string",
                        "operators": operators,
                        "input": self.inputJSQueryBuilder,
                        "valueSetter": self.setJSQueryBuilderInputValue,
                        "valueGetter": self.getJSQueryBuilderInputValue,
                        "input_event": ""
                    });
                }
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
            options.push.apply(options, self.custom_variable_options);

            var templateHeaderHTML = document.getElementById("__ds_condition-rule-template-header").innerHTML;
            var templateFooterHTML = document.getElementById("__ds_condition-rule-template-footer").innerHTML;
            var variableOptionsHTML = "";

            templateHeaderHTML = templateHeaderHTML.replace(/%id%/gi, name);
            templateFooterHTML = templateFooterHTML.replace(/%id%/gi, name);

            options = self.custom_variable_options;
            variableCount = options.length;

            for (var i = 0; i < variableCount; i++) {
                variableOptionsHTML += "<option value=\""
                        + options[i]["database_id"]
                        + "\">"
                        + options[i]["text"]
                        + "</option>";
            }
            variableOptionsHTML += '</optgroup>';

            return (templateHeaderHTML + variableOptionsHTML + templateFooterHTML);
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
            $(".condition-rule-type", sender).off("change").on("change", function () {
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

            // Custom Variables
            options = self.custom_variable_options;
            var variableCount = options.length;

            if (0 == variableCount) {
                return;
            }

            for (var i = 0; i < variableCount; i++) {
                filters.push({
                    "id": options[i]["id"],
                    "label": options[i]["text"],
                    "type": "string",
                    "operators": operators,
                    "input": self.inputJSQueryBuilder,
                    "valueSetter": self.setJSQueryBuilderInputValue,
                    "valueGetter": self.getJSQueryBuilderInputValue,
                    "input_event": ""
                });
            }

            $("#__ds_condition_builder_container").queryBuilder("setFilters", filters);
        },
        showCustomVariableList: function() {
            $("#modalCustomVariableList").modal();
        },
        getConditionBeautyHTML: function(conditionRule) {
            var conditionHTML = "";
            var rules = conditionRule.rules;
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
        },
        getConditionData: function(instance_id) {
            var arrTR = $("tr", document.getElementById(instance_id + "__ds_simple_conditions"));
            var conditions = [];

            for (let index = 0; index < arrTR.length; index++) {
                let condition_data = $(arrTR[index]).data("condition_data");

                let conditionData = {
                    "guid" : arrTR[index].getAttribute("data-guid"),
                    "condition" : condition_data
                }

                conditions.push(conditionData);
            }

            return conditions;
        },
        renderConditions: function(instance_id, conditions) {
            var self = this;
            var conditionsContainer = document.getElementById(instance_id + "__ds_simple_conditions");
            conditionsContainer.innerHTML = "";
            var conditionTemplateHTML = document.getElementById("__ds_condition-template").innerHTML;

            for (let index = 0; index < conditions.length; index++) {
                const condition_data = conditions[index];
                const guid = condition_data.guid;
                const condition = condition_data.condition;

                const conditionHTML = conditionTemplateHTML.replace(/__guid__/g, guid).replace(/__condition_html__/g, self.getConditionBeautyHTML(condition));
                const tr = document.createElement("tr");
                tr.innerHTML = conditionHTML;
                tr.id = (guid + "-tr");
                tr.setAttribute("data-guid", guid);

                conditionsContainer.appendChild(tr);

                $(document.getElementById(guid + "-tr")).data("condition_data", condition);
            }

            $(".btn-edit-condition", conditionsContainer).off("click").on("click", function () {
                self.doEditCondition(this);
            });

            $(".btn-remove-condition", conditionsContainer).off("click").on("click", function () {
                self.doRemoveCondition(this);
            });
        }
    },
    mounted() {
        this.processLoadQueue();
        window.__ds_simple__condition = this;
        WisiloHelper.loadExternalFiles(this.page.external_files);
    }
}
</script>