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
                                        <insert-custom-variable-button :target="instance_id + 'title'"></insert-custom-variable-button>
                                    </label>
                                    <input type="text" class="form-control " :id="instance_id + 'title'">
                                </div>
                                <div class="form-group col-lg-12">
                                    <label :for="instance_id + 'infobox_value'" class="detail-label">
                                        {{ $t('Infobox Value') }}
                                        <insert-custom-variable-button :target="instance_id + 'infobox_value'"></insert-custom-variable-button>
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
                <data-source :instance_id="instance_id"></data-source>
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
            initializePage: function() {
                var self = this;
                var instance_id = self.instance_id;

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
            },
            getWidgetFormValues: function() {
                var instance_id = this.instance_id;

                // content
                return {
                    "title" : document.getElementById(instance_id + "title").value,
                    "icon" : document.getElementById(instance_id + "icon").value,
                    "iconbackground" : document.getElementById(instance_id + "iconbackground").value,
                    "redirectURL" : document.getElementById(instance_id + "redirectURL").value,
                    "infobox_value" : document.getElementById(instance_id + "infobox_value").value,
                };
            }
        },
        mounted() {
            window.mainLayoutInstance.pageWidgets[this.instance_id].content_settings = this;
            this.initializePage();
        }
    }
</script>