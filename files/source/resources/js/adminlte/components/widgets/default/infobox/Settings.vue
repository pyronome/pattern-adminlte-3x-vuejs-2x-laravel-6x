<template>
    <div>
        <widget-settings-dialog :instance_id="instance_id">
            <div :id="instance_id + '-accordion'">
                <div class="card card-primary">
                    <div class="card-header">
                        <h4 class="card-title w-100">
                            <a class="d-block w-100" data-toggle="collapse" :href="'#' + instance_id + '-accordion-style'">
                                Style
                            </a>
                        </h4>
                    </div>
                    <div class="collapse show"
                        :id="instance_id + '-accordion-style'"
                        :data-parent="'#' + instance_id + '-accordion'">
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-lg-12">
                                    <label :for="instance_id + 'title'" class="detail-label">{{ $t('Title') }}</label>
                                    <input type="text"
                                        class="form-control "
                                        :id="instance_id + 'title'">
                                </div>
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
                                    <label :for="instance_id + 'redirectURL'" class="detail-label">{{ $t('Redirect URL') }}</label>
                                    <input type="text"
                                        class="form-control "
                                        :id="instance_id + 'redirectURL'">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card card-primary">
                    <div class="card-header">
                        <h4 class="card-title w-100">
                            <a class="d-block w-100" data-toggle="collapse" :href="'#' + instance_id + '-accordion-value'">
                                Value
                            </a>
                        </h4>
                    </div>
                    <div class="collapse"
                        :id="instance_id + '-accordion-value'"
                        :data-parent="'#' + instance_id + '-accordion'">
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-12">
                                    <span>Calculation Type</span>
                                </div>
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
                                        <label class="detail-label" :for="instance_id + 'calculation_type2'">Advanced</label>
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
                                    <label :for="instance_id + 'query'" class="detail-label">{{ $t('SQL Query') }}</label>
                                    <textarea rows="5"
                                        :id="instance_id + 'query'"
                                        :name="instance_id + 'query'"
                                        class="form-control"
                                        style="font-family: monospace;font-size: 15px;"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </widget-settings-dialog>
    </div>
</template>

<script>
    export default {
        props: ["instance_id"],
        data() {
            return {
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
                var selectorText = 'input[name="' + instance_id + 'calculation_type"][value="' + data.content.calculation_type + '"]';
                $(selectorText).prop('checked', true);

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
            }
        },
        mounted() {
            window.mainLayoutInstance.pageWidgets[this.instance_id].content_settings = this;
            this.processLoadQueue();
        }
    }
</script>