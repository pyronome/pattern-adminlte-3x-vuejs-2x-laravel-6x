<template>
    <div>
        <div id="divWidgetConditionDialog" class="modal level2 fade" tabindex="-1" role="dialog">
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

        <script id="condition-rule-template-footer" type="text/html">
            <input class="form-control condition-rule-constant"
                    type="text"
                    id="%id%_constant"
                    data-rule-name="%id%" />
        </script>
        <script id="condition-rule-template-header" type="text/html">
            <select class="form-control condition-rule-type" id="%id%_type" data-rule-name="%id%">
                <option value="1" selected>Constant</option>
                <option value="2">Variable</option>
            </select>
            <select class="form-control d-none condition-rule-variable"
                    id="%id%_variable"
                    data-rule-name="%id%">
        </script>
        <script id="condition-rule-template-footer" type="text/html">
            </select>
            <input class="form-control condition-rule-constant"
                    type="text"
                    id="%id%_constant"
                    data-rule-name="%id%" />
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
                <td id="__field_guid__-html">__value_html__</td>
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
                        <div class="row cv-field-container d-none" id="__cv_field_container__array">
                            <table class="table table-bordered table-hover table-sm condition-table">
                                <thead>
                                    <tr>
                                        <th>
                                            <span id="__cv_field_label__array"></span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="__cv_field__array_list"></tbody>
                            </table>
                        </div>

                        <div class="row cv-field-container d-none" id="__cv_field_container__checkbox">
                            <div class="icheck-primary d-inline">
                                <input type="checkbox"
                                    id="__cv_field__checkbox"
                                    name="__cv_field__checkbox">
                                <label for="__cv_field__checkbox" class="detail-label">
                                    <span id="__cv_field_label__checkbox"></span>
                                </label>
                            </div>
                        </div>

                        <div class="row cv-field-container d-none" id="__cv_field_container__colorpicker">
                            <label for="__cv_field__colorpicker" class="detail-label">
                                <span id="__cv_field_label__colorpicker"></span>
                            </label>
                            <div class="input-group">
                                <input type="text"
                                    class="form-control color-picker"
                                    id="__cv_field__colorpicker"
                                    name="__cv_field__colorpicker">
                                <div class="input-group-append">
                                    <span class="input-group-text" id="__cv_field__colorpickerappend" style="padding-left:100px;"></span>
                                </div>
                            </div>
                        </div>

                        <div class="row cv-field-container d-none" id="__cv_field_container__datepicker">
                            <label for="__cv_field__datepicker" class="detail-label">
                                <span id="__cv_field_label__datepicker"></span>
                            </label>
                            <input type="date"
                                class="form-control"
                                data-type="datepicker"
                                id="__cv_field__datepicker"
                                name="__cv_field__datepicker">
                        </div>
                        
                        <div class="row cv-field-container d-none" id="__cv_field_container__datetimepicker">
                            <label for="__cv_field__datetimepicker" class="detail-label">
                                <span id="__cv_field_label__datetimepicker"></span>
                            </label>
                            <input type="datetime-local"
                                class="form-control"
                                id="__cv_field__datetimepicker"
                                name="__cv_field__datetimepicker">
                        </div>

                        <div class="row cv-field-container d-none" id="__cv_field_container__file">
                            <label for="__cv_field__file" class="detail-label" style="width:100%;">
                                <span id="__cv_field_label__file"></span>
                            </label>
                            <select2-element multiple
                                class="select2-element"
                                id="__cv_field__file"
                                name="__cv_field__file"
                                :options="file_options">
                            </select2-element>
                        </div>

                        <div class="row cv-field-container d-none" id="__cv_field_container__htmleditor">
                            <label for="__cv_field__htmleditor" class="detail-label">
                                <span id="__cv_field_label__htmleditor"></span>
                            </label>
                            <textarea id="__cv_field__htmleditor"
                                name="__cv_field__htmleditor"
                                class="textarea vue-editor"
                                rows="5"></textarea>
                        </div>

                        <div class="row cv-field-container d-none" id="__cv_field_container__iconpicker">
                            <label for="__cv_field__iconpicker" class="detail-label" style="width:100%;">
                                <span id="__cv_field_label__iconpicker"></span>
                            </label>
                            <button type="button" id="__cv_field__iconpicker" class="btn btn-outline-secondary icon-picker"></button>
                            <input type="hidden" id="__cv_field__iconpicker-value" name="__cv_field__iconpicker-value">
                        </div>

                        <div class="row cv-field-container d-none" id="__cv_field_container__integer">
                            <div class="form-group col-lg-12">
                                <label for="__cv_field__integer" class="detail-label">
                                    <span id="__cv_field_label__integer"></span>
                                </label>
                                <input type="number" step="1" class="form-control" id="__cv_field__integer">
                            </div>
                        </div>

                        <div class="row cv-field-container d-none" id="__cv_field_container__number">
                            <div class="form-group col-lg-12">
                                <label for="__cv_field__number" class="detail-label">
                                    <span id="__cv_field_label__number"></span>
                                </label>
                                <input type="number" step="0.01" class="form-control " id="__cv_field__number">
                            </div>
                        </div>

                        <div class="row cv-field-container d-none" id="__cv_field_container__password">
                            <div class="form-group col-lg-12">
                                <label for="__cv_field__password" class="detail-label">
                                    <span id="__cv_field_label__password"></span>
                                </label>
                                <input type="password" class="form-control " id="__cv_field__password">
                            </div>
                        </div>

                        <div class="row cv-field-container d-none" id="__cv_field_container__selection">
                            <div class="form-group col-lg-12">
                                <label for="__cv_field__selection" class="detail-label">
                                    <span id="__cv_field_label__selection"></span>
                                </label>
                                <select class="form-control"
                                    id="__cv_field__selection"
                                    name="__cv_field__selection"
                                    style="width:100%;">
                                </select>
                            </div>
                        </div>
                        
                        <div class="row cv-field-container d-none" id="__cv_field_container__shorttext">
                            <div class="form-group col-lg-12">
                                <label for="__cv_field__shorttext" class="detail-label">
                                    <span id="__cv_field_label__shorttext"></span>
                                    <insert-custom-variable-button target="__cv_field__shorttext"></insert-custom-variable-button>
                                </label>
                                <input type="text" class="form-control " id="__cv_field__shorttext">
                            </div>
                        </div>

                        <div class="row cv-field-container d-none" id="__cv_field_container__textarea">
                            <label for="__cv_field__textarea" class="detail-label">
                                <span id="__cv_field_label__textarea"></span>
                                <insert-custom-variable-button target="__cv_field__textarea"></insert-custom-variable-button>
                            </label>
                            <textarea rows="5"
                                id="__cv_field__textarea"
                                name="__cv_field__textarea"
                                class="form-control"></textarea>
                        </div>

                        <div class="row cv-field-container d-none" id="__cv_field_container__timepicker">
                            <label for="__cv_field__timepicker" class="detail-label">
                                <span id="__cv_field_label__timepicker"></span>
                            </label>
                            <input type="time"
                                class="form-control"
                                data-type="timepicker"
                                id="__cv_field__timepicker"
                                name="__cv_field__timepicker">
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

        <script id="field-html-template-checkbox"  type="text/html">
            <span class="text-success spanIcon spanIconEnabled__state__">
                <i class="far fa-check-circle"></i>
            </span>
        </script>

        <script id="field-html-template-colorpicker"  type="text/html">
            <span class="spanColorpickerHTML" style="background:__color__;"></span><span>__color__</span>
        </script>

        <script id="field-html-template-iconpicker"  type="text/html">
            <span class="spanIconpickerHTML"><i class="__icon__"></i>__icon__</span>
        </script>

        <script id="field-html-template-file"  type="text/html">
            <button type="button" class="text-btn p-0 file_download__delete__" data-file-id="__file_id__">
                <span>{{ $t('__file_name__') }}</span>
            </button>
        </script>
        <script id="conditional-item-row-template" type="text/html">
            <tr id="__guid__-tr" 
                data-json='__data_json__' 
                data-guid="__guid__" 
                style= "__style__">
                <td class="">
                    <span id="__guid__-title">__label__</span>
                    <button type="button" title="Edit Item" class="btn-icon btn-icon-primary float-right btn-edit-item" data-conditional-edit="true">
                        <span class="btn-label btn-label-right"><i class="fas fa-pen"></i></span>
                    </button>
                </td>
            </tr>
        </script>

        <div id="div_conditional_items_dialog" class="modal level1 fade" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="conditional_items_dialog-title"></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-lg-12">
                                <table class="table table-bordered table-hover table-sm condition-table">
                                    <tbody id="conditionalItemList"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modalfooter justify-content-between">
                        <div class="row">
                            <div class="col-md-12">
                                <button
                                    type="button"
                                    id="buttonSaveConditionalItems"
                                    @click="doSaveConditionalItems"
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
            conditional_fields: [],
            custom_variable_options: [],
            file_options: [],
            page: {
                is_ready: false,
                has_server_error: false,
                is_post_success: false,
                is_custom_variable_options_loading: false,
                is_custom_variable_options_loaded: false,
                is_file_options_loading: false,
                is_file_options_loaded: false,
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

            if (!self.page.is_custom_variable_options_loaded
                && !self.page.is_file_options_loaded) {
                self.$Progress.start();
            }

            if (!self.page.is_custom_variable_options_loaded) {
                self.load_custom_variable_options();
            }

            if (!self.page.is_file_options_loaded) {
                self.load_file_options(function(){});
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
                    "text": element.title
                }

                options.push(option);
            }

            this.custom_variable_options = options;

            this.refreshFilters();

            return;

            var self = this;
            if (self.page.is_custom_variable_options_loading) {
                return;
            }

            self.page.is_custom_variable_options_loading = true;
            
            axios.get(WisiloHelper.getAPIURL("wisilo/get_custom_variable_options"))
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
        load_file_options: function(callback) {
            var self = this;
            if (self.page.is_file_options_loading) {
                return;
            }

            self.page.is_file_options_loading = true;
            
            axios.get(WisiloHelper.getAPIURL("wisilomedia/get_file_select_options"))
                .then(({ data }) => {
                    self.page.is_file_options_loaded = true;
                    self.page.is_file_options_loading = false;
                    self.file_options = data.list;
                    self.processLoadQueue();
                }).catch(({ data }) => {
                    self.page.is_file_options_loaded = true;
                    self.page.is_file_options_loading = false;
                    self.$Progress.fail();
                    self.page.has_server_error = true;
                    self.processLoadQueue();
                }).finally(function() {
                    if (!self.page.has_server_error) {
                        callback();
                    }
                });
        },
        showConditionDialog: function(conditional_fields) {
            this.conditional_fields = conditional_fields;

            document.getElementById("buttonSaveWidgetCondition").setAttribute("data-guid", "");

            this.initializeJSQueryBuilder(document.getElementById("widgetConditionBuilderContainer"), null);

            $("#divWidgetConditionDialog").modal();
        },
        saveCondition: function() {
            var self = this;
            var instance_id = window.mainLayoutInstance.current_editing_widget_instance_id;
            var newCondition = false;
            var guid = document.getElementById("buttonSaveWidgetCondition").getAttribute("data-guid");
            if ("" == guid) {
                newCondition = true;
                guid = WisiloHelper.generateGUID("condition");
            }

            var conditionJSON = JSON.stringify($("#widgetConditionBuilderContainer").queryBuilder("getRules", { allow_invalid: true }));

            if (newCondition) {
                var tableTemplateHTML = document.getElementById("condition-table-template").innerHTML;
                var tbodyHTML = "";
                var trTemplateHTML = document.getElementById("conditional-field-row-template").innerHTML;
                let fieldValues = window.mainLayoutInstance.pageWidgets[instance_id].content_settings.getWidgetFormValues();

                self.conditional_fields.forEach(field => {
                    field.value = fieldValues[field.id];

                    let field_guid = WisiloHelper.generateGUID("field");

                    let trHTML = trTemplateHTML
                        .replace(/__field_guid__/g, field_guid)
                        .replace(/__label__/g, field.label)
                        .replace(/__value_html__/g, self.getFieldValueHTML(field))
                        .replace(/__field_json__/g, JSON.stringify(field));
                        
                    tbodyHTML += trHTML;
                });

                var tableHTML = tableTemplateHTML
                    .replace(/__guid__/g, guid)
                    .replace(/__condition_json__/g, conditionJSON)
                    .replace(/__condition_html__/g, self.getConditionBeautyHTML(conditionJSON))
                    .replace(/__conditional_fields_html__/g, tbodyHTML);

                document.getElementById(instance_id + "-conditionlist").innerHTML += tableHTML;

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

            $(".file_download").off("click").on("click", function(e){
                self.downloadFile(this.getAttribute("data-file-id"));
            });

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
        show_array_dialog: function(btnEditField, objectField) {
            var instance_id = window.mainLayoutInstance.current_editing_widget_instance_id;

            if (!(objectField.hasOwnProperty("get_items_function"))) {
                return;
            }

            document.getElementById("conditional_items_dialog-title").innerHTML = objectField.label;

            var get_items_function = objectField.get_items_function;
            var edit_item_values_function = objectField.edit_item_values_function;

            var items = [];

            var fieldHasItems = false;
            if (objectField.hasOwnProperty("items") && ("" != objectField.items)) {
                items = objectField.items;
                fieldHasItems = true;
            } else {
                items = window.mainLayoutInstance.pageWidgets[instance_id].content_settings[get_items_function]();
            }

            var itemLabelField = objectField.item_label_field;

            var liTemplate = document.getElementById("conditional-item-row-template").innerHTML;
            var ulInnerHTML = "";
            var liHTML = "";

            for (let index = 0; index < items.length; index++) {
                const item_guid = WisiloHelper.generateGUID("item");
                const item = items[index];
                const itemJSON = JSON.stringify(item)

                liHTML = liTemplate
                    .replace(/__data_json__/g, itemJSON)
                    .replace(/__guid__/g, item_guid)
                    .replace(/__label__/g, item[itemLabelField]);
                ulInnerHTML += liHTML;
            }

            if (!fieldHasItems) {
                objectField.items = items;
            }

            btnEditField.setAttribute("data-field-json", JSON.stringify(objectField));

            var columnsContainer = document.getElementById("conditionalItemList");
            columnsContainer.innerHTML = ulInnerHTML;

            $(".btn-edit-item", columnsContainer).off("click").on("click", function () {
                window.mainLayoutInstance.pageWidgets[instance_id].content_settings[edit_item_values_function](
                    this.parentNode.parentNode.getAttribute("data-guid"), 
                    JSON.parse(this.parentNode.parentNode.getAttribute("data-json")),
                    this.getAttribute("data-conditional-edit")
                );
            });

            document.getElementById("buttonSaveConditionalItems").setAttribute("data-field-guid" , btnEditField.getAttribute("data-field-guid"));
            $("#buttonSaveConditionalItems").data("field_data", objectField);

            $("#div_conditional_items_dialog").modal();

        },
        doSaveConditionalItems: function() {
            var guid = document.getElementById("buttonSaveConditionalItems").getAttribute("data-field-guid");
            var field_data = $("#buttonSaveConditionalItems").data("field_data");

            var itemList = $("#conditionalItemList > tr");
            var items = [];

            for (let index = 0; index < itemList.length; index++) {
                const tr = itemList[index];
                items.push(JSON.parse(tr.getAttribute("data-json")))
            }

            field_data["items"] = items;

            document.getElementById(guid + "-btn").setAttribute("data-field-json", JSON.stringify(field_data));

            $("#div_conditional_items_dialog").modal("hide");
        },
        doEditField: function(elButton) {
            console.log("doEditfield")
            var guid = elButton.getAttribute("data-field-guid");

            document.getElementById("buttonSaveField").setAttribute("data-field-guid", guid);

            var fieldJSON = elButton.getAttribute("data-field-json");
            var objectField = JSON.parse(fieldJSON);
            var fieldType = objectField.type;

            if ("array" == fieldType) {
                this.show_array_dialog(elButton, objectField);
                return;
            }

            $(".cv-field-container").addClass("d-none");
            $(document.getElementById("__cv_field_container__" + fieldType)).removeClass("d-none");
            document.getElementById("__cv_field_label__" + fieldType).innerHTML = objectField.label;

            this.setFieldValue(objectField);

            $("#divEditFieldDialog").modal();
        },
        setFieldValue: function(objectField) {
            var self = this;
            var fieldInput = document.getElementById("__cv_field__" + objectField.type);
            var fieldType = objectField.type;
            var val = objectField.value;

            if ("checkbox" == fieldType) {
                if ("on" == val) {
                    fieldInput.checked = true;
                }
            } else if (("colorpicker" == fieldType ) && ("" != val)) {
                var colorPicker = fieldInput;
                $(colorPicker).val(val);
                $(colorPicker).trigger('change');
            } else if ("datepicker" == fieldType) {
                fieldInput.value = val;
            } else if ("datetimepicker" == fieldType) {
                fieldInput.value = val;
            } else if ("file" == fieldType) {
                if (undefined !== val) {
                    $("#__cv_field__file").val(val.split(",")).trigger('change');
                }
            } else if ("selection" == fieldType) {
                self.setDropdownOptions(objectField);

                if (undefined !== val) {
                    if (objectField.input_data.multiple) {
                        $("#__cv_field__selection").val(val.split(",")).trigger('change');
                    } else {
                        $("#__cv_field__selection").val(val).trigger('change');
                    }
                }
            } else if ("htmleditor" == fieldType) {
                $(fieldInput).summernote("code", val);
            } else if ("iconpicker" == fieldType) {
                if ("" == val || undefined === val) {
                    console.log("empty icon")
                    /* $(document.getElementById(elementKey)).iconpicker('setIcon', 'empty'); */
                } else{
                    $(fieldInput).iconpicker('setIcon', val);
                }
            } else if ("integer" == fieldType) {
                fieldInput.value = val;
            } else if ("number" == fieldType) {
                fieldInput.value = val;
            } else if ("password" == fieldType) {
                fieldInput.value = val;
            } else if ("shorttext" == fieldType) {
                fieldInput.value = val;
            } else if ("textarea" == fieldType) {
                $(fieldInput).val(val);
            } else if ("timepicker" == fieldType) {
                fieldInput.value = val;
            }
        },
        setDropdownOptions(objectField) {
            var select = document.getElementById("__cv_field__selection");
            select.innerHTML = "";
            
            var options = objectField.input_data.options;

            for (var key in options) {
                select.innerHTML += '<option value="' + key + '">' + options[key] + '</option>'
            }

            if (objectField.input_data.multiple) {
                $("#__cv_field__selection").select2({multiple: true});
            } else {
                $("#__cv_field__selection").select2({multiple: false});
            }
        },
        saveField: function() {
            var self = this;
            var guid = document.getElementById("buttonSaveField").getAttribute("data-field-guid");
            var elEditButton = document.getElementById(guid + "-btn");

            var fieldJSON = elEditButton.getAttribute("data-field-json");
            var objectField = JSON.parse(fieldJSON);
            var fieldType = objectField.type;
            var fieldInput = document.getElementById("__cv_field__" + objectField.type);

            if ("checkbox" == fieldType) {
                objectField.value = fieldInput.checked ? 'on' : 'off';
            } else if ("colorpicker" == fieldType) {
                objectField.value = fieldInput.value;
            } else if ("file" == fieldType) {
                objectField.value = $("#__cv_field__file").val().join(",");
            } else if ("htmleditor" == fieldType) {
                objectField.value = $(fieldInput).summernote('code');
            } else if ("iconpicker" == fieldType) {
                objectField.value = document.getElementById("__cv_field__iconpicker-value").value;
            } else if ("selection" == fieldType) {
                objectField.value = $("#__cv_field__selection").val();
                   
                var attr = $("#__cv_field__selection").attr('multiple');
                if (typeof attr !== 'undefined' && attr !== false) {
                    objectField.value = objectField.value.join(",");
                }
            } else {
                objectField.value = fieldInput.value;
            }
            
            fieldJSON = JSON.stringify(objectField);
            elEditButton.setAttribute("data-field-json", fieldJSON);

            document.getElementById(guid + "-html").innerHTML = self.getFieldValueHTML(objectField);

            $(".file_download").off("click").on("click", function(e){
                self.downloadFile(this.getAttribute("data-file-id"));
            });

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

            // Custom Variables
            options = self.custom_variable_options;
            var variableCount = options.length;
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

            var templateHeaderHTML = document.getElementById("condition-rule-template-header").innerHTML;
            var templateFooterHTML = document.getElementById("condition-rule-template-footer").innerHTML;
            var variableOptionsHTML = "";

            templateHeaderHTML = templateHeaderHTML.replace(/%id%/gi, name);
            templateFooterHTML = templateFooterHTML.replace(/%id%/gi, name);

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

            $(".file_download").off("click").on("click", function(e){
                self.downloadFile(this.getAttribute("data-file-id"));
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

                let field_guid = WisiloHelper.generateGUID("field");

                let trHTML = trTemplateHTML
                    .replace(/__field_guid__/g, field_guid)
                    .replace(/__label__/g, field.label)
                    .replace(/__value_html__/g, self.getFieldValueHTML(field))
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
        getFieldValueHTML: function(field) {
            var self = this;
            var fieldType = field.type;
            var fieldValue = field.value;
            var fieldLabel = field.label;
            var templateHTML = "";
            var fieldHTML = "";

            if (("array" == fieldType ) && ("" != fieldValue)) {
                fieldHTML = "";
            } else if ("checkbox" == fieldType) {
                let state = 0;
                if ("on" == fieldValue) {
                    state = 1;
                }

                templateHTML = document.getElementById("field-html-template-checkbox").innerHTML;
                fieldHTML = templateHTML.replace(/__state__/g, state);
            } else if (("colorpicker" == fieldType ) && ("" != fieldValue)) {
                templateHTML = document.getElementById("field-html-template-colorpicker").innerHTML;
                fieldHTML = templateHTML.replace(/__color__/g, fieldValue);
            } else if ("datepicker" == fieldType) {
                fieldHTML = fieldValue;
            } else if ("datetimepicker" == fieldType) {
                fieldHTML = fieldValue;
            } else if ("file" == fieldType) {
                if (undefined !== fieldValue) {
                    templateHTML = document.getElementById("field-html-template-file").innerHTML;

                    var fileIds = fieldValue.split(",");
                    for (let index = 0; index < fileIds.length; index++) {
                        const fileId = fileIds[index];
                        const fileData = self.getFileData(fileId);
                        fieldHTML = fieldHTML 
                            + templateHTML.replace(/__delete__/g, "").replace(/__file_id__/g, fileId).replace(/__file_name__/g, fileData.text);
                    }
                }
            } else if ("selection" == fieldType) {
                if ("" == fieldValue || undefined === fieldValue) {
                    fieldHTML = "";
                } else {
                    var options = field.input_data.options;
                    var values = fieldValue.split(",");
                    for (let index = 0; index < values.length; index++) {
                        const key = values[index];
                        
                        if ("" != fieldHTML) {
                            fieldHTML += ", ";
                        }

                        fieldHTML += options[key];
                    }
                }
            } else if ("htmleditor" == fieldType) {
                fieldHTML = fieldValue;
            } else if ("iconpicker" == fieldType) {
                if ("" == fieldValue || undefined === fieldValue) {
                    fieldHTML = "";
                } else {
                    templateHTML = document.getElementById("field-html-template-iconpicker").innerHTML;
                    fieldHTML = templateHTML.replace(/__icon__/g, fieldValue);
                }
            } else if ("integer" == fieldType) {
                fieldHTML = fieldValue;
            } else if ("number" == fieldType) {
                fieldHTML = fieldValue;
            } else if ("password" == fieldType) {
                fieldHTML = "*****";
            } else if ("shorttext" == fieldType) {
                fieldHTML = fieldValue;
            } else if ("textarea" == fieldType) {
                fieldHTML = fieldValue;
            } else if ("timepicker" == fieldType) {
                fieldHTML = fieldValue;
            } else {
                fieldHTML = fieldValue;
            }

            return fieldHTML;
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
        },
        getFileData: function(fileId) {
            var options = this.file_options;
            for (let index = 0; index < options.length; index++) {
                const element = options[index];
                if (element.id == fileId) {
                    return element;
                }
            }

            return {};
        },
        downloadFile: function (file_id) {
            axios.get(WisiloHelper.getAPIURL("wisilomedia/download_file/" + file_id))
                .then(({ data }) => {
                    var a = document.createElement("a"); //Create <a>
                    a.href = data.url; //Image Base64 Goes here
                    a.download = data.filename; //File name Here
                    a.click(); //Downloaded file
                    URL.revokeObjectURL(a.href)
                }).catch(({ data }) => {
                    console.log("error!")
                });
        },
    },
    mounted() {
        this.processLoadQueue();
        window.__condition_dialog = this;
        WisiloHelper.loadExternalFiles(this.page.external_files);

        $("#divEditFieldDialog").off("shown.bs.modal").on('shown.bs.modal', function (e) { 
            $(document).off('focusin.modal'); 
        });
    }
}
</script>