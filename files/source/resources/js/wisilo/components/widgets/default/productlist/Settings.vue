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
                                    <label :for="instance_id + 'title'" class="detail-label">
                                        {{ $t('Record List Title') }}
                                    </label>
                                    <input type="text" class="form-control " :id="instance_id + 'record_list_title'">
                                </div>

                                <div class="form-group col-lg-12">
                                    <div class="">
                                        <div class="icheck-primary d-inline">
                                            <input type="checkbox"
                                                :id="instance_id + 'show_pagination'"
                                                :name="instance_id + 'show_pagination'"
                                                class="item-menu">
                                            <label :for="instance_id + 'show_pagination'" class="">
                                                {{ $t('Pagination') }}  
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group col-lg-12">
                                    <input type="hidden" :id="instance_id + 'columns'" class="item-widget">
                                    <table class="table table-bordered table-hover table-sm condition-table">
                                        <thead>
                                            <tr>
                                                <th>
                                                    {{ $t('Columns') }}
                                                    <button type="button" title="Add Column" class="btn-icon btn-icon-primary float-right btn-add-column"
                                                        :id="instance_id + 'addColumn'" @click="addNewColumn">
                                                        <span class="btn-label btn-label-right"><i class="fas fa-plus"></i></span>
                                                    </button>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody :id="instance_id + 'columnList'"></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <data-source :instance_id="instance_id"></data-source>
                <variable-mapping-list :instance_id="instance_id" :local_variables="local_variables"></variable-mapping-list>
                <widget-conditional-settings :instance_id="instance_id" :conditional_fields="conditional_fields"></widget-conditional-settings>
            </div>
        </widget-settings-dialog>

        <div :id="instance_id + 'column_dialog'" class="modal level2 fade" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{ $t('Column') }}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <input type="hidden" class="form-control " :id="instance_id + 'column_guid'">
                            <!-- <input type="hidden" class="form-control " :id="instance_id + 'conditional_data_json'"> -->

                            <div class="form-group col-lg-12">
                                <div class="icheck-primary d-inline">
                                    <input type="checkbox" :id="instance_id + 'visible'">
                                    <label :for="instance_id + 'visible'" class="detail-label">
                                        <span>Visible</span>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="form-group col-lg-12">
                                <label :for="instance_id + 'type'" class="detail-label">
                                    Type
                                </label>
                                <select class="form-control" :id="instance_id + 'type'" style="width:100%;">
                                    <option value="boolean">Boolean</option>
                                    <option value="button">Button</option>
                                    <option value="date">Date</option>
                                    <option value="integer">Integer</option>
                                    <option value="number">Number</option>
                                    <option value="text">Text</option>
                                </select>
                            </div>

                            <div class="form-group col-lg-12">
                                <label :for="instance_id + 'title'" class="detail-label">
                                    Title
                                    <insert-custom-variable-button :target="instance_id + 'title'"></insert-custom-variable-button>
                                </label>
                                <textarea class="textarea vue-editor" :id="instance_id + 'title'" rows="5"></textarea>
                            </div>

                            <div class="form-group col-lg-12">
                                <label :for="instance_id + 'name'" class="detail-label">
                                    Name
                                </label>
                                <input type="text" class="form-control " :id="instance_id + 'name'">
                            </div>

                            <div class="form-group col-lg-12">
                                <label :for="instance_id + 'value'" class="detail-label">
                                    Value
                                    <insert-custom-variable-button :target="instance_id + 'value'"></insert-custom-variable-button>
                                </label>
                                <textarea class="textarea vue-editor" :id="instance_id + 'value'" rows="5"></textarea>
                            </div>

                            <div class="form-group col-lg-12">
                                <label :for="instance_id + 'style'" class="detail-label">
                                    Style
                                </label>
                                <textarea class="form-control" :id="instance_id + 'style'" rows="5"></textarea>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-lg-12">
                                <span class="error invalid-feedback" :id="instance_id + 'errors'" style="display:none"></span>
                            </div>
                        </div>
                    </div>
                    <div class="modalfooter justify-content-between show_by_permission_must_update">
                        <div class="row">
                            <div class="col-md-12">
                                <button
                                    type="button"
                                    :id="instance_id + 'buttonSave'"
                                    @click="doSaveColumn"
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

        <script id="column-row-template" type="text/html">
            <td class="">
                <span id="__guid__-title">__label__</span>
                <button type="button" title="Remove Column" class="btn-icon btn-icon-danger float-right btn-remove-column">
                    <span class="btn-label btn-label-right"><i class="fas fa-times"></i></span>
                </button>
                <button type="button" title="Edit Column" class="btn-icon btn-icon-primary float-right btn-edit-column" data-conditional-edit="false">
                    <span class="btn-label btn-label-right"><i class="fas fa-pen"></i></span>
                </button>
            </td>
        </script>

        
    </div>
</template>

<script>
    export default {
        props: ["instance_id"],
        data() {
            return {
                conditional_fields: [
                    {
                        "type": "shorttext",
                        "id": "record_list_title",
                        "label": "Record List Title",
                        "value": ""
                    },
                    {
                        "type": "array",
                        "id": this.instance_id,
                        "label": "Columns",
                        "item_label_field": "title",
                        "value": "",
                        "items": "",
                        "get_items_function": "getItems",
                        "edit_item_values_function": "doEditColumn"
                    }
                ],
                local_variables: [
                    {
                        "id" : "__list_search_box_value",
                        "label" : "Search Box Value"
                    },
                    {
                        "id" : "__list_page_number",
                        "label" : "Page Number"
                    }
                ],
                page: {
                    is_ready: false,
                    has_server_error: false,
                    is_model_options_loading: false,
                    is_model_options_loaded: false,
                    is_property_options_loading: false,
                    is_property_options_loaded: false,
                    external_files: [
                        ("/js/wisilo/bootstrap-iconpicker/css/bootstrap-iconpicker.min.css"),
                        ("/js/wisilo/bootstrap-iconpicker/js/iconset/fontawesome5-3-1.min.js"),
                        ("/js/wisilo/bootstrap-iconpicker/js/bootstrap-iconpicker.min.js"),
                        ("/js/wisilo/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css"),
                        ("/js/wisilo/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"),
                    ],
                },
            };
        },
        methods: {
            editConditionalColumn: function() {
                console.log("editConditionalColumn")
            },
            getItems: function() {
                var self = this;
                var instance_id = self.instance_id;
                var columns = $("tr", document.getElementById(instance_id + 'columnList'));
                var items = [];

                for (let index = 0; index < columns.length; index++) {
                    const tr = columns[index];
                    const columnJSON = tr.getAttribute("data-json");
                    items.push(JSON.parse(columnJSON));
                }

                return items;
            },
            initializePage: function() {
                var self = this;
                var instance_id = self.instance_id;

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
            },
            setWidgetFormValues: function() {
                var self = this;
                var instance_id = self.instance_id;
                var data = window.mainLayoutInstance.pageWidgets[instance_id].data;

                document.getElementById(instance_id + "record_list_title").value = data.content.record_list_title;

                if (undefined !== data.content.show_pagination) {
                    document.getElementById(instance_id + "show_pagination").checked = (1 == data.content.show_pagination);
                }

                if (data.content.columns.length > 0) {
                    self.renderColumnList(data.content.columns);
                }
            },
            renderColumnList: function(columns) {
                var self = this;
                var instance_id = self.instance_id;
                var columnsContainer = document.getElementById(instance_id + "columnList");
                columnsContainer.innerHTML = "";
                var trTemplateHTML = document.getElementById("column-row-template").innerHTML;

                for (let index = 0; index < columns.length; index++) {
                    let column_data = columns[index];
                    let column_guid = WisiloHelper.generateGUID("column");

                    let trHTML = trTemplateHTML.replace(/__guid__/g, column_guid).replace(/__label__/g, column_data.title);

                    let tr = document.createElement("tr");
                    tr.innerHTML = trHTML;
                    tr.id = (column_guid + "-tr");
                    tr.setAttribute("data-guid", column_guid);

                    let style = ("on" == column_data.visible) ? "" : "opacity:0.5";
                    tr.style = style;

                    columnsContainer.appendChild(tr);

                    $(document.getElementById(column_guid + "-tr")).data("column_data", column_data);
                }

                $(".btn-edit-column", columnsContainer).off("click").on("click", function () {
                    self.doEditColumn(this.parentNode.parentNode.getAttribute("data-guid"), this.getAttribute("data-conditional-edit"));
                });

                $(".btn-remove-column", columnsContainer).off("click").on("click", function () {
                    self.doRemoveColumn(this);
                });

                $(columnsContainer).sortable();
                $(columnsContainer).disableSelection();



                /* var liTemplate = document.getElementById("column-row-template").innerHTML;
                var ulInnerHTML = "";
                var liHTML = "";

                for (let index = 0; index < columns.length; index++) {
                    const columnJSON = columns[index];
                    const column = JSON.parse(columnJSON);
                    const column_guid = WisiloHelper.generateGUID("column");
                    const style = ("on" == column["visible"]) ? "" : "opacity:0.5";
                    liHTML = liTemplate
                        .replace(/__data_column_json__/g, columnJSON)
                        .replace(/__guid__/g, column_guid)
                        .replace(/__label__/g, column["title"])
                        .replace(/__style__/g, style);
                    ulInnerHTML += liHTML;
                }

                var columnsContainer = document.getElementById(instance_id + "columnList");
                columnsContainer.innerHTML = ulInnerHTML;

                $(".btn-edit-column", columnsContainer).off("click").on("click", function () {
                    self.doEditColumn(this.parentNode.parentNode.getAttribute("data-guid"), this.getAttribute("data-conditional-edit"));
                });

                $(".btn-remove-column", columnsContainer).off("click").on("click", function () {
                    self.doRemoveColumn(this);
                });

                $(columnsContainer).sortable();
                $(columnsContainer).disableSelection(); */
            },
            addNewColumn: function() {
                var instance_id = this.instance_id;
                document.getElementById(instance_id + "column_guid").value = WisiloHelper.generateGUID("column");

                document.getElementById(instance_id + "visible").checked = true;
                $(document.getElementById(instance_id + "type")).val("").trigger('change');
                $(document.getElementById(instance_id + "title")).summernote("reset");
                $(document.getElementById(instance_id + "title")).summernote("code", "");
                document.getElementById(instance_id + "name").value = "";
                $(document.getElementById(instance_id + "value")).summernote("reset");
                $(document.getElementById(instance_id + "value")).summernote("code", "");
                $(document.getElementById(instance_id + "style")).val("");
                /* document.getElementById(instance_id + "conditional_data_json").value = ""; */

                $(document.getElementById(instance_id + "column_dialog")).modal();
            },
            doEditColumn: function(guid, conditional_edit) {
                var instance_id = this.instance_id;
                document.getElementById(instance_id + "column_guid").value = guid;

                var elTR = document.getElementById(guid + "-tr");
                var column_data = $(elTR).data("column_data");
                
                if ("on" == column_data["visible"]) {
                    document.getElementById(instance_id + "visible").checked = true;
                } else {
                    document.getElementById(instance_id + "visible").checked = false;
                }

                $(document.getElementById(instance_id + "type")).val(column_data["type"]).trigger('change');
                $(document.getElementById(instance_id + "title")).summernote("code", column_data["title"]);
                document.getElementById(instance_id + "name").value = column_data["name"];
                $(document.getElementById(instance_id + "value")).summernote("code", column_data["value"]);
                $(document.getElementById(instance_id + "style")).val(column_data["style"]);
                /*  document.getElementById(instance_id + "conditional_data_json").value = data["conditional_data_json"]; */

                if ("true" == conditional_edit) {
                    $(document.getElementById(instance_id + "visible").parentNode.parentNode).addClass("d-none");
                    $(document.getElementById(instance_id + "type").parentNode).addClass("d-none");
                    $(document.getElementById(instance_id + "title").parentNode).addClass("d-none");
                    $(document.getElementById(instance_id + "name").parentNode).addClass("d-none");
                } else {
                    $(document.getElementById(instance_id + "visible").parentNode.parentNode).removeClass("d-none");
                    $(document.getElementById(instance_id + "type").parentNode).removeClass("d-none");
                    $(document.getElementById(instance_id + "title").parentNode).removeClass("d-none");
                    $(document.getElementById(instance_id + "name").parentNode).removeClass("d-none");
                }

                $(document.getElementById(instance_id + "column_dialog")).modal();
            },
            doSaveColumn: function() {
                var self = this;
                var instance_id = self.instance_id; 
                var guid = document.getElementById(instance_id + "column_guid").value;

                document.getElementById(instance_id + "errors").style.display = "none";

                var column_name = document.getElementById(instance_id + "name").value.toLowerCase();

                if (!self.isValidColumnName(guid, column_name)) {
                    document.getElementById(instance_id + "errors").style.display = "block";
                    return;
                }

                var columnData = {
                    "visible" : document.getElementById(instance_id + "visible").checked ? "on" : "off",
                    "type" : document.getElementById(instance_id + "type").value,
                    "title" : $(document.getElementById(instance_id + "title")).summernote("code"),
                    "name" : column_name,
                    "value" : $(document.getElementById(instance_id + "value")).summernote("code"),
                    "style" : document.getElementById(instance_id + "style").value,
                }

                var trStyle = ("on" == columnData.visible) ? "" : "opacity:0.5";

                if (document.getElementById(guid + "-title")) {
                    // edit column
                    document.getElementById(guid + "-title").innerHTML = columnData.title;
                    document.getElementById(guid + "-tr").setAttribute("style", trStyle);
                    $(document.getElementById(guid + "-tr")).data("column_data", columnData);
                } else {
                    var columnsContainer = document.getElementById(instance_id + "columnList");

                    // new column
                    var trTemplateHTML = document.getElementById("column-row-template").innerHTML;
                    var trHTML = trTemplateHTML.replace(/__guid__/g, guid).replace(/__label__/g, columnData.title);

                    const tr = document.createElement("tr");
                    tr.innerHTML = trHTML;
                    tr.id = (guid + "-tr");
                    tr.setAttribute("data-guid", guid);
                    tr.style = trStyle;

                    columnsContainer.appendChild(tr);

                    $(document.getElementById(guid + "-tr")).data("column_data", columnData);

                    $(".btn-edit-column", columnsContainer).off("click").on("click", function () {
                        self.doEditColumn(this.parentNode.parentNode.getAttribute("data-guid"), this.getAttribute("data-conditional-edit"));
                    });

                    $(".btn-remove-column", columnsContainer).off("click").on("click", function () {
                        self.doRemoveColumn(this);
                    });

                    $(columnsContainer).sortable();
                    $(columnsContainer).disableSelection();
                }

                $(document.getElementById(instance_id + "column_dialog")).modal("hide");
            },
            isValidColumnName: function(guid, column_name) {
                var self = this;
                var instance_id = self.instance_id;

                var className = document.getElementById(instance_id + "name").parentNode.className;
                if (className.includes("d-none")){
                    return true;
                }

                if ("" == column_name) {
                    document.getElementById(instance_id + "errors").innerHTML = self.$t("Column name is required.");
                    return false;
                }

                if (!self.isNameValid(column_name)) {
                    document.getElementById(instance_id + "errors").innerHTML = self.$t("Column name must contain only English letters or '_'. You cannot be use special characters and numbers.");
                    return false;
                }

                var instance_id = self.instance_id
                var columnList = $("tr", document.getElementById(instance_id + 'columnList'));
                var columnNameInUse = false;
                for (let index = 0; index < columnList.length; index++) {
                    let tr = columnList[index];
                    if (guid != tr.getAttribute("data-guid")) {
                        let column_data = $(tr).data("column_data");
                        if (column_name == column_data["name"]) {
                            columnNameInUse = true;
                        }
                    }
                }

                if (columnNameInUse) {
                    document.getElementById(instance_id + "errors").innerHTML = self.$t("This column name is in use. Please try different name.");
                    return false;
                }

                return true;
            },
            isNameValid: function(column_name) {
                var valid = true;

                const alphabet = [
                    "a","b","c","d","e","f",
                    "g","h","i","j","k","l",
                    "m","n","o","p","q","r",
                    "s","t","u","v","w","x","y","z","_"];

                for (let i = 0; i < column_name.length; i++) {
                    let char = column_name.charAt(i);
                    
                    if (!alphabet.includes(char)) {
                        valid = false;
                        break;
                    }                
                }
                
                return valid;
            },
            doRemoveColumn: function(sender) {
                sender.parentNode.parentNode.remove();
            },
            collectColumnData: function() {
                var instance_id = this.instance_id

                var arrTR = $("tr", document.getElementById(instance_id + "columnList"));
                var columns = [];

                for (let index = 0; index < arrTR.length; index++) {
                    let column_data = $(arrTR[index]).data("column_data");

                    let columnData = {
                        "visible" : column_data.visible,
                        "type" : column_data.type,
                        "title" : column_data.title,
                        "name" : column_data.name,
                        "value" : column_data.value,
                        "style" : column_data.style
                    }

                    columns.push(columnData);
                }

                return columns;
            },

            getWidgetFormValues: function() {
                var self = this;
                var instance_id = this.instance_id;

                // content
                return {
                    "record_list_title" : document.getElementById(instance_id + "record_list_title").value,
                    "show_pagination": document.getElementById(instance_id + "show_pagination").checked,
                    "columns" : self.collectColumnData(),
                };
            }
        },
        mounted() {
            window.mainLayoutInstance.pageWidgets[this.instance_id].content_settings = this;
            this.initializePage();
        }
    }
</script>