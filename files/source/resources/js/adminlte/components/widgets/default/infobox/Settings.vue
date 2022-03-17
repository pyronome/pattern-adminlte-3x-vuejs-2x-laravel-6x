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
                                <div class="form-group col-3">
                                    <label :for="instance_id + 'icon'" class="detail-label">{{ $t('Icon') }}</label>
                                    <button type="button" :id="instance_id + 'icon_picker'" class="btn btn-outline-secondary icon-picker">
                                    </button>
                                    <input type="hidden" :id="instance_id + 'icon'" class="item-widget">
                                </div>
                                <div class="form-group col-9">
                                    <label :for="instance_id + 'iconbackground'" class="detail-label">{{ $t('Icon Background') }}</label>
                                    <input type="text"
                                        class="form-control color-picker"
                                        :id="instance_id + 'iconbackground'">
                                </div>
                                <div class="form-group col-lg-12">
                                    <label :for="instance_id + 'title'" class="detail-label">
                                        {{ $t('Title') }}
                                        <insert-variable-button 
                                            :variable_options="['query_result_fields','custom_variables','global_parameters','user_parameters','url_parameters','request_parameters']" 
                                            :target="instance_id + 'title'">
                                        </insert-variable-button>
                                    </label>
                                    <input type="text" class="form-control " :id="instance_id + 'title'">
                                </div>
                                <div class="form-group col-lg-12">
                                    <label :for="instance_id + 'infobox_value'" class="detail-label">
                                        {{ $t('Infobox Value') }}
                                        <insert-variable-button 
                                            :variable_options="['query_result_fields','global_parameters','user_parameters','url_parameters','request_parameters']" 
                                            :target="instance_id + 'infobox_value'">
                                        </insert-variable-button>
                                    </label>
                                    <input type="text" class="form-control " :id="instance_id + 'infobox_value'">
                                </div>
                                <div class="form-group col-lg-12">
                                    <label :for="instance_id + 'redirectURL'" class="detail-label">{{ $t('Redirect URL') }}</label>
                                    <input type="text" class="form-control " :id="instance_id + 'redirectURL'">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <h4 class="card-title w-100">
                            <a class="d-block w-100" data-toggle="collapse" :href="'#' + instance_id + '-accordion-query'">
                                Data Source
                            </a>
                        </h4>
                    </div>
                    <div class="collapse"
                        :id="instance_id + '-accordion-query'"
                        :data-parent="'#' + instance_id + '-accordion'">
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-6 clearfix">
                                    <div class="icheck-primary d-inline">
                                        <input type="radio" 
                                            :id="instance_id + 'calculation_type1'" 
                                            :name="instance_id + 'calculation_type'" 
                                            value="simple"
                                            checked>
                                        <label class="detail-label" :for="instance_id + 'calculation_type1'">Simple</label>
                                    </div>
                                </div>
                                <div class="form-group col-6 clearfix">
                                    <div class="icheck-primary d-inline">
                                        <input type="radio" 
                                            :id="instance_id + 'calculation_type2'" 
                                            :name="instance_id + 'calculation_type'"
                                            value="advanced">
                                        <label class="detail-label" :for="instance_id + 'calculation_type2'">SQL Query</label>
                                    </div>
                                </div>
                            </div>
                            <div v-show="show_calculation_type_simple" class="row">
                                <div class="form-group col-lg-12">
                                    <label :for="instance_id + 'function'" class="detail-label">{{ $t('Function') }}</label>
                                    <select :id="instance_id + 'function'" class="form-control">
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
                                    <label :for="instance_id + 'model'" class="detail-label">{{ $t('Model') }}</label>
                                    <select :id="instance_id + 'model'"  class="form-control">
                                        <option v-for="(model, index) in model_options" :key="index" :value="model.id">
                                            {{model.text}}
                                        </option>
                                    </select>
                                </div>
                                <div class="form-group col-lg-12">
                                    <label :for="instance_id + 'property'" class="detail-label">{{ $t('Property') }}</label>
                                    <select :id="instance_id + 'property'"  class="form-control">
                                        <option v-for="(property, index) in property_options" :key="index" :value="property.id">
                                            {{property.text}}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div v-show="!show_calculation_type_simple" class="row">
                                <div class="form-group col-lg-12 col-md-12 col-sm-12">
                                    <label :for="instance_id + 'query'" class="detail-label">
                                        {{ $t('SQL Query') }}
                                        <insert-variable-button 
                                            :variable_options="['global_parameters','user_parameters','url_parameters','request_parameters']" 
                                            :target="instance_id + 'query'">
                                        </insert-variable-button>
                                    </label>
                                    <textarea rows="5"
                                        :id="instance_id + 'query'"
                                        :name="instance_id + 'query'"
                                        class="form-control"
                                        style="font-family: monospace;font-size: 15px;"></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-striped table-bordered table-hover table-sm">
                                        <thead>
                                            <tr>
                                                <th><span>Variables</span>&nbsp;</th> 
                                                <th style="width: 70px;"></th>
                                            </tr>
                                        </thead>
                                        <tbody id="tbodyVariableList">
                                            <tr>
                                                <td>
                                                    <span>Product Price (productPrice)</span>
                                                </td> 
                                                <td>
                                                    <button type="button" class="btn-icon btn-icon-primary" style="margin-bottom: 0px;">
                                                        <span class="btn-label btn-label-right"><i class="fas fa-pen"></i></span>
                                                    </button> 
                                                    <button type="button" class="btn-icon btn-icon-danger">
                                                        <span class="btn-label btn-label-right"><i class="fas fa-times"></i></span>
                                                    </button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><span>Product Name (productName)</span></td>
                                                <td>
                                                    <button type="button" class="btn-icon btn-icon-primary" style="margin-bottom: 0px;">
                                                        <span class="btn-label btn-label-right"><i class="fas fa-pen"></i></span>
                                                    </button>
                                                    <button type="button" class="btn-icon btn-icon-danger">
                                                        <span class="btn-label btn-label-right"><i class="fas fa-times"></i></span>
                                                    </button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <widget-conditional-settings :instance_id="instance_id" :conditional_fields="conditional_fields"></widget-conditional-settings>
            </div>
        </widget-settings-dialog>
    </div>
</template>

<script>
    export default {
        props: ["instance_id"],
        data() {
            return {
                conditional_fields: [
                    {
                        "type": "iconpicker",
                        "id": "icon",
                        "label": "Icon",
                        "value": ""
                    },
                    {
                        "type": "colorpicker",
                        "id": "iconbackground",
                        "label": "Icon Background",
                        "value": ""
                    },
                    {
                        "type": "shorttext",
                        "id": "title",
                        "label": "Title",
                        "value": ""
                    },
                    
                    /* {
                        "type": "checkbox",
                        "id": "checkbox",
                        "label": "Checkbox",
                        "value": ""
                    },
                    {
                        "type": "colorpicker",
                        "id": "colorpicker",
                        "label": "Colorpicker",
                        "value": ""
                    },
                    {
                        "type": "datepicker",
                        "id": "datepicker",
                        "label": "Datepicker",
                        "value": ""
                    },
                    {
                        "type": "datetimepicker",
                        "id": "datetimepicker",
                        "label": "Datetimepicker",
                        "value": ""
                    },
                    {
                        "type": "file",
                        "id": "file",
                        "label": "File",
                        "value": ""
                    },
                    {
                        "type": "selection",
                        "input_data": {
                            "multiple": true,
                            "options": {
                                // "value": "title"
                                "a": "A", 
                                "b": "B", 
                                "c": "C"
                            }
                        },
                        "id": "selection",
                        "label": "Selection",
                        "value": ""
                    },
                    {
                        "type": "htmleditor",
                        "id": "htmleditor",
                        "label": "htmleditor",
                        "value": ""
                    },
                    {
                        "type": "iconpicker",
                        "id": "iconpicker",
                        "label": "iconpicker",
                        "value": ""
                    },
                    {
                        "type": "integer",
                        "id": "integer",
                        "label": "integer",
                        "value": ""
                    },
                    {
                        "type": "number",
                        "id": "number",
                        "label": "number",
                        "value": ""
                    },
                    {
                        "type": "password",
                        "id": "password",
                        "label": "password",
                        "value": ""
                    },
                    {
                        "type": "shorttext",
                        "id": "shorttext",
                        "label": "shorttext",
                        "value": ""
                    },
                    {
                        "type": "textarea",
                        "id": "textarea",
                        "label": "textarea",
                        "value": ""
                    },
                    {
                        "type": "timepicker",
                        "id": "timepicker",
                        "label": "timepicker",
                        "value": ""
                    }, */
                ],
                model_options: [],
                property_options: [],
                page: {
                    is_ready: false,
                    has_server_error: false,
                    is_model_options_loading: false,
                    is_model_options_loaded: false,
                    is_property_options_loading: false,
                    is_property_options_loaded: false,
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

                if (!self.page.is_model_options_loaded) {
                    self.load_model_options();
                } else {
                    self.initializePage();
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
                    }).catch(({ data }) => {
                        self.page.is_property_options_loaded = true;
                        self.page.is_property_options_loading = false;
                        self.$Progress.fail();
                        self.processLoadQueue();
                    }).finally(function() {
                        let elSelect = document.getElementById(self.instance_id + "property");
                        $(elSelect).val(elSelect.getAttribute("val")).trigger('change');
                        self.processLoadQueue();
                    });
            },
            initializePage: function() {
                var self = this;
                var instance_id = self.instance_id;

                var selectorText = 'input[name="' + instance_id + 'calculation_type"]';
                $(selectorText).change(function() {
                    if (this.value == 'simple') {
                        self.show_calculation_type_simple = true;
                    } else if (this.value == 'advanced') {
                        self.show_calculation_type_simple = false;
                    }
                });

                $(document.getElementById(instance_id + "model")).off("change").on("change", function (e) {
                    self.load_property_options(this.value);
                });

                var iconPickerOptions = {
                    search: true,
                    searchText: "...",
                    labelHeader: "{0} / {1}",
                    cols: 4, rows: 4, 
                    footer: false, 
                    iconset: "fontawesome5"
                };

                var iconPicker = $(document.getElementById(instance_id + "icon_picker")).iconpicker(iconPickerOptions);
                iconPicker.on("change", function (e) {
                    document.getElementById(instance_id + "icon").value = e.icon;
                });

                $(document.getElementById(instance_id + "iconbackground")).colorpicker();

                $(document.getElementById(instance_id + "iconbackground")).on("colorpickerChange", function(event) {
                    var colorHex = event.color.toString();
                    $(document.getElementById(instance_id + "icon_picker")).css("background", colorHex);
                    $(document.getElementById(instance_id + "icon_picker")).css("border", colorHex);
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
                document.getElementById(instance_id + "infobox_value").value = data.content.infobox_value;

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
                    "infobox_value" : document.getElementById(instance_id + "infobox_value").value,
                    "calculation_type" : $(radioSelectorText).val(),
                    "model" : document.getElementById(instance_id + "model").value,
                    "property" : document.getElementById(instance_id + "property").value,
                    "function" : document.getElementById(instance_id + "function").value,
                    "query" : document.getElementById(instance_id + "query").value,
                };
            }
        },
        mounted() {
            window.mainLayoutInstance.pageWidgets[this.instance_id].content_settings = this;
            this.processLoadQueue();
        }
    }
</script>