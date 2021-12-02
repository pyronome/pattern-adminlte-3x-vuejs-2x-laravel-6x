<template>
    <div class="content-wrapper">
        <server-error v-if="page.has_server_error" ></server-error>
        <permission-error v-else-if="!page.is_authorized" :type="page.unauthorized_type"></permission-error>
        <div v-else>
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>{{ $t("Form") }}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="home">{{ $t('Home') }}</a></li>
                            <li class="breadcrumb-item active">{{ $t("Configuration Parameters") }}</li>
                        </ol>
                    </div>
                    </div>
                </div>
            </section>
            <section class="content">
                <div class="container-fluid">
                    <div class="card recordlist-card">
                        <div class="card-header">
                            <!-- <h3 class="card-title">Configuration Form</h3>
                            <div class="card-tools">
                            </div> -->
                            <div class="">
                                <div class="input-group input-group-sm divSearchBar float-right" style="">
                                    <input type="text"
                                        id="searchText" name="searchText"
                                        @keyup="search_list" v-model="search_text"
                                        class="form-control float-right inputSearchBar"
                                        v-bind:placeholder="$t('Search')" autocomplete="off">
                                    <div class="input-group-append labelSearchBar">
                                        <button type="button" class="btn btn-default ">
                                            <img class="imgLoader" src="/img/adminlte/loader.svg" width="14" height="14"/>
                                            <i class="fas fa-search text-primary"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            
                            <div class="table-responsive row" id="AdminLTEConfigFormContainer">
                                
                            </div>
                        </div>
                        <div class="card-footer bg-white">
                            <div class="col-lg-12 col-md-12 col-xs-12">
                                <button type="button"
                                    id="saveConfigForm" 
                                    @click="submitConfigForm"
                                    class="btn btn-success btn-md btn-on-card float-right sticky-btn">
                                    {{ $t('Save') }}
                                </button>
                            </div>
                        </div>            
                    </div>  
                </div>
            </section>
        </div>
        <input type="hidden" id="controller" value="adminlteconfig">
        <script type="text/html" id="groupTemplate">
            <div class="col-lg-12 toggle-able" data-key="__group_key__">
                <label style="font-size: 1.1rem;font-weight: 400;">{{ $t('__group_title__') }}</label>
                <div class="col-lg-12 mb-30" id="groupContainer__group_key__">
                </div>
            </div>
        </script>
        
        <script type="text/html" id="checkboxTemplate">
            <div class="col-lg-12 mb-20 toggle-able" data-key="__field_key__">
                <div class="icheck-primary d-inline">
                    <input type="checkbox"
                        id="__field_key__"
                        name="__field_key__"
                        class="config-parameter__delete__"
                        data-type="checkbox">
                    <label for="__field_key__" class="detail-label">
                        {{ $t('__field_title__') }} <span class="__required_class__">*</span>
                        <button type="button"
                            class="use-parameter-default-value__delete__"
                            data-type="checkbox"
                            data-key="__field_key__"
                            default-value="__default_value__">
                            <span>{{ $t('Use Default Value') }}</span>
                        </button>
                    </label>
                </div>
            </div>
        </script>

        <script type="text/html" id="colorpickerTemplate">
            <div class="col-lg-12 mb-20 toggle-able" data-key="__field_key__">
                <label for="__field_key__" class="detail-label">
                    {{ $t('__field_title__') }} <span class="__required_class__">*</span>
                    <button type="button"
                        class="use-parameter-default-value__delete__"
                        data-type="colorpicker"
                        data-key="__field_key__"
                        default-value="__default_value__">
                        <span>{{ $t('Use Default Value') }}</span>
                    </button>
                </label>
                <div class="input-group">
                    <input type="text"
                        class="form-control color-picker__delete__ config-parameter__delete__"
                        data-type="colorpicker"
                        id="__field_key__"
                        name="__field_key__">
                    <div class="input-group-append">
                        <span class="input-group-text" id="__field_key__append" style="padding-left:100px;"></span>
                    </div>
                </div>
            </div>
        </script>

        <script type="text/html" id="datepickerTemplate">
            <div class="col-lg-12 mb-20 toggle-able" data-key="__field_key__">
                <label for="__field_key__" class="detail-label">
                    {{ $t('__field_title__') }} <span class="__required_class__">*</span>
                    <button type="button"
                        class="use-parameter-default-value__delete__"
                        data-type="datepicker"
                        data-key="__field_key__"
                        default-value="__default_value__">
                        <span>{{ $t('Use Default Value') }}</span>
                    </button>
                </label>
                <input type="date"
                    class="form-control config-parameter__delete__"
                    data-type="datepicker"
                    id="__field_key__"
                    name="__field_key__">
            </div>
        </script>

        <script type="text/html" id="datetimepickerTemplate">
            <div class="col-lg-12 mb-20 toggle-able" data-key="__field_key__">
                <label for="__field_key__" class="detail-label">
                    {{ $t('__field_title__') }} <span class="__required_class__">*</span>
                    <button type="button"
                        class="use-parameter-default-value__delete__"
                        data-type="datetimepicker"
                        data-key="__field_key__"
                        default-value="__default_value__">
                        <span>{{ $t('Use Default Value') }}</span>
                    </button>
                </label>
                <input type="datetime-local"
                    class="form-control config-parameter__delete__"
                    data-type="datetimepicker"
                    id="__field_key__"
                    name="__field_key__">
            </div>
        </script>

        <script type="text/html" id="dropdownTemplate">
            <div class="col-lg-12 mb-20 toggle-able" data-key="__field_key__">
                <label for="__field_key__" class="detail-label">
                    {{ $t('__field_title__') }} <span class="__required_class__">*</span>
                    <button type="button"
                        class="use-parameter-default-value__delete__"
                        data-type="dropdown"
                        data-key="__field_key__"
                        default-value="__default_value__"
                        data-multiple="__multiple__">
                        <span>{{ $t('Use Default Value') }}</span>
                    </button>
                </label>
                <select __multiple__
                    id="__field_key__"
                    name="__field_key__"
                    class="form-control configselect2__delete__ config-parameter__delete__"
                    data-type="dropdown"
                    data-key="__field_key__"
                    style="width:100%;">
                </select>
            </div>
        </script>

        <script type="text/html" id="fileTemplate">
            <div class="col-lg-12 mb-20 toggle-able" data-key="__field_key__">
                <label for="__field_key__" class="detail-label">{{ $t('__field_title__') }}  </label>
                <div class="input-field">
                    <input type="file" id="__field_key__" name="__field_key__" 
                        accept="__file_types__"
                        data-type="file" 
                        class="form-input config-file__delete__ config-parameter__delete__"
                        style="display:block;">
                    <input type="hidden" id="__field_key__-file_name">
                    <input type="hidden" id="__field_key__-file_value">
                    <button type="button" class="text-btn file_download__delete__"
                        data-key="__field_key__">
                        <span>{{ $t('__value__') }}</span>
                    </button>
                </div>
            </div>
        </script>

        <script type="text/html" id="htmlEditorTemplate">
            <div class="col-lg-12 mb-20 toggle-able" data-key="__field_key__">
                <label for="__field_key__" class="detail-label">
                    {{ $t('__field_title__') }} <span class="__required_class__">*</span>
                    <button type="button"
                        class="use-parameter-default-value__delete__"
                        data-type="html_editor"
                        data-key="__field_key__"
                        default-value="__default_value__">
                        <span>{{ $t('Use Default Value') }}</span>
                    </button>
                </label>
                <textarea id="__field_key__"
                    name="__field_key__"
                    data-type="html_editor"
                    class="textarea vue-editor__delete__ config-parameter__delete__"
                    rows="5"></textarea>
            </div>
        </script>

        <script type="text/html" id="iconPickerTemplate">
            <div class="col-lg-12 mb-20 toggle-able" data-key="__field_key__">
                <label for="__field_key__" class="detail-label">
                    {{ $t('__field_title__') }} <span class="__required_class__">*</span>
                    <button type="button"
                        class="use-parameter-default-value__delete__"
                        data-type="iconpicker"
                        data-key="__field_key__"
                        default-value="__default_value__">
                        <span>{{ $t('Use Default Value') }}</span>
                    </button>
                </label>
                <button type="button" id="__field_key__" class="btn btn-outline-secondary icon-picker__delete__"></button>
                <input type="hidden" id="__field_key__-value" name="__field_key__-value" data-key="__field_key__" data-type="iconpicker" class="item-widget config-parameter__delete__">
            </div>
        </script>
        
        <script type="text/html" id="integerTemplate">
            <div class="col-lg-12 mb-20 toggle-able" data-key="__field_key__">
                <label for="__field_key__" class="detail-label">
                    {{ $t('__field_title__') }} <span class="__required_class__">*</span>
                    <button type="button"
                        class="use-parameter-default-value__delete__"
                        data-type="shorttext"
                        data-key="__field_key__"
                        default-value="__default_value__">
                        <span>{{ $t('Use Default Value') }}</span>
                    </button>
                </label>
                <input type="number"
                    class="form-control config-parameter__delete__"
                    data-type="shorttext"
                    id="__field_key__"
                    name="__field_key__"
                    min="__min__"
                    max="__max__"
                    step="__step__">
            </div>
        </script>
        <script type="text/html" id="link_buttonTemplate">
            <div class="col-lg-12 mb-20 toggle-able" data-key="__field_key__">
                <a class="btn btn-primary btn-md btn-on-card text-white"
                    target="_blank"
                    href="__url__">
                    <span>{{ $t('__field_title__') }}</span>
                </a>
            </div>
        </script>

        <script type="text/html" id="link_textTemplate">
            <div class="col-lg-12 mb-20 toggle-able" data-key="__field_key__">
                <a class=""
                    target="_blank"
                    href="__url__">
                    <span>{{ $t('__field_title__') }}</span>
                </a>
            </div>
        </script>
        <script type="text/html" id="numberTemplate">
            <div class="col-lg-12 mb-20 toggle-able" data-key="__field_key__">
                <label for="__field_key__" class="detail-label">
                    {{ $t('__field_title__') }} <span class="__required_class__">*</span>
                    <button type="button"
                        class="use-parameter-default-value__delete__"
                        data-type="shorttext"
                        data-key="__field_key__"
                        default-value="__default_value__">
                        <span>{{ $t('Use Default Value') }}</span>
                    </button>
                </label>
                <input type="number"
                    class="form-control config-parameter__delete__"
                    data-type="shorttext"
                    id="__field_key__"
                    name="__field_key__"
                    min="__min__"
                    max="__max__"
                    step="__step__">
            </div>
        </script>
        <script type="text/html" id="passwordTemplate">
            <div class="col-lg-12 mb-20 toggle-able" data-key="__field_key__">
                <label for="__field_key__" class="detail-label">
                    {{ $t('__field_title__') }} <span class="__required_class__">*</span>
                    <button type="button"
                        class="use-parameter-default-value__delete__"
                        data-type="shorttext"
                        data-key="__field_key__"
                        default-value="__default_value__">
                        <span>{{ $t('Use Default Value') }}</span>
                    </button>
                </label>
                <input type="password"
                    class="form-control config-parameter__delete__"
                    data-type="shorttext"
                    id="__field_key__"
                    name="__field_key__">
            </div>
        </script>
        <script type="text/html" id="radioTemplate">
            <div class="col-lg-12 mb-20 toggle-able" data-key="__field_key__">
                <label class="detail-label col-form-label col-lg-12">
                    {{ $t('__field_title__') }} <span class="__required_class__">*</span>
                    <button type="button"
                        class="use-parameter-default-value__delete__"
                        data-type="radio"
                        data-key="__field_key__"
                        default-value="__default_value__">
                        <span>{{ $t('Use Default Value') }}</span>
                    </button>
                </label>
                <div id="container_radio___field_key__" 
                    class="form-group clearfix config-parameter__delete__"
                    data-type="radio"   
                    data-key="__field_key__">
                    __radio_options_html__
                </div>
            </div>
        </script>
        <script type="text/html" id="radioOptionTemplate">
            <div class="icheck-primary">
                <input type="radio"
                    id="__field_key____option_index__"
                    name="__field_key__"
                    class="blue-style"
                    value="__option_value__">
                <label for="__field_key____option_index__" class="detail-label">{{ $t('__option_title__') }}</label>
            </div>
        </script>

        <script type="text/html" id="readonly_contentTemplate">
            <div class="col-lg-12 mb-20 toggle-able" data-key="__field_key__">
                <h6>{{ $t('__field_title__') }}</h6>
                <p>{{ $t('__content__') }}</p>
            </div>
        </script>
        
        <script type="text/html" id="shorttextTemplate">
            <div class="col-lg-12 mb-20 toggle-able" data-key="__field_key__">
                <label for="__field_key__" class="detail-label">
                    {{ $t('__field_title__') }} <span class="__required_class__">*</span>
                    <button type="button"
                        class="use-parameter-default-value__delete__"
                        data-type="shorttext"
                        data-key="__field_key__"
                        default-value="__default_value__">
                        <span>{{ $t('Use Default Value') }}</span>
                    </button>
                </label>
                <input type="text"
                    class="form-control config-parameter__delete__"
                    data-type="shorttext"
                    id="__field_key__"
                    name="__field_key__">
            </div>
        </script>
        <script type="text/html" id="switchTemplate">
            <div class="col-lg-12 mb-20 toggle-able" data-key="__field_key__">
                <input type="checkbox"
                    id="__field_key__"
                    name="__field_key__"
                    class="vue-switch__delete__ config-parameter__delete__"
                    data-type="switch"
                    data-key="__field_key__"
                    data-bootstrap-switch>
                <label for="__field_key__" class="switch-label">
                    <div class="bootstrap-switch bootstrap-switch-wrapper fake-switch-container" style="width: 88px;">
                        <div class="bootstrap-switch-container" style="width: 129px; margin-left: 0px;">
                            <span class="bootstrap-switch-handle-on bootstrap-switch-primary" style="width: 43px;">{{ $t('ON') }}</span>
                            <span class="bootstrap-switch-label" style="width: 43px;">&nbsp;</span>
                            <span class="bootstrap-switch-handle-off bootstrap-switch-default" style="width: 43px;">{{ $t('OFF') }}</span>
                        </div>
                    </div>
                    {{ $t('__field_title__') }}
                    <button type="button"
                        class="use-parameter-default-value__delete__"
                        data-type="switch"
                        data-key="__field_key__"
                        default-value="__default_value__">
                        <span>{{ $t('Use Default Value') }}</span>
                    </button>
                </label>
            </div>
        </script>
        <script type="text/html" id="textareaTemplate">
            <div class="col-lg-12 mb-20 toggle-able" data-key="__field_key__">
                <label for="__field_key__" class="detail-label">
                    {{ $t('__field_title__') }} <span class="__required_class__">*</span>
                    <button type="button"
                        class="use-parameter-default-value__delete__"
                        data-type="textarea"
                        data-key="__field_key__"
                        default-value="__default_value__">
                        <span>{{ $t('Use Default Value') }}</span>
                    </button>
                </label>
                <textarea rows="5"
                    id="__field_key__"
                    name="__field_key__"
                    class="form-control config-parameter__delete__"
                    data-type="textarea"></textarea>
            </div>
        </script>
        <script type="text/html" id="timepickerTemplate">
            <div class="col-lg-12 mb-20 toggle-able" data-key="__field_key__">
                <label for="__field_key__" class="detail-label">
                    {{ $t('__field_title__') }} <span class="__required_class__">*</span>
                    <button type="button"
                        class="use-parameter-default-value__delete__"
                        data-type="timepicker"
                        data-key="__field_key__"
                        default-value="__default_value__">
                        <span>{{ $t('Use Default Value') }}</span>
                    </button>
                </label>
                <input type="time"
                    class="form-control config-parameter__delete__"
                    data-type="timepicker"
                    id="__field_key__"
                    name="__field_key__">
            </div>
        </script>
        <script type="text/html" id="toggleTemplate">
            <div class="col-lg-12 mb-20 toggle-able" data-key="__field_key__">
                <input type="checkbox"
                    id="__field_key__"
                    name="__field_key__"
                    class="vue-switch__delete__ config-toggle__delete__"
                    data-toggle-elements="__toggle_elements__"
                    data-bootstrap-switch>
                <label for="__field_key__" class="switch-label">
                    <div class="bootstrap-switch bootstrap-switch-wrapper fake-switch-container" style="width: 88px;">
                        <div class="bootstrap-switch-container" style="width: 129px; margin-left: 0px;">
                            <span class="bootstrap-switch-handle-on bootstrap-switch-primary" style="width: 43px;">{{ $t('ON') }}</span>
                            <span class="bootstrap-switch-label" style="width: 43px;">&nbsp;</span>
                            <span class="bootstrap-switch-handle-off bootstrap-switch-default" style="width: 43px;">{{ $t('OFF') }}</span>
                        </div>
                    </div>
                    {{ $t('__field_title__') }}
                </label>
            </div>
        </script>

        <body-loader :body_loader_active="body_loader_active" class="content-wrapper bodyLoader"></body-loader>
    </div>
</template>

<script>

export default {
    data() {
        return {
            main_folder: '',
            list: [],
            search_text: '',
            current_page: 1,
            show_pagination: false,
            sort_variable: 'id',
            sort_direction: 'desc',  
            formConfig: new Form({
                'config_data': []
            }),
            uploadedFiles: {},                  
            formDelete: new Form({
                'selected_ids': []
            }),
            delete_form: {
                has_error: false,
                error_msg: ''
            },
            init_toggles:false,
            page: {
                is_ready: false,
                has_server_error: false,
                variables: [],
                is_authorized: true,
                unauthorized_type: '',
                is_variables_loading: false,
                is_variables_loaded: false,
                is_configlist_loading: false,
                is_configlist_loaded: false,
                external_files: [
                    ("/js/adminlte/bootstrap-switch/js/bootstrap-switch.js"),
                    ("/js/adminlte/bootstrap-iconpicker/css/bootstrap-iconpicker.min.css"),
                    ("/js/adminlte/bootstrap-iconpicker/js/iconset/fontawesome5-3-1.min.js"),
                    ("/js/adminlte/bootstrap-iconpicker/js/bootstrap-iconpicker.min.js"),
                    ("/js/adminlte/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css"),
                    ("/js/adminlte/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"),
                    ("/js/adminlte/select2/dist/js/select2.min.js"),
                ],
            },
            body_loader_active: false,
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

            if (!self.page.is_authorized) {
                self.$Progress.finish();
                self.page.is_ready = true;
                return;
            }

            if (!self.page.is_variables_loaded && !self.page.is_configlist_loaded) {
                self.$Progress.start();
            }

            if (!self.page.is_variables_loaded) {
                self.loadPageVariables();
            } else {
                if (self.page.is_configlist_loaded) {
                    self.$Progress.finish();
                    self.page.is_ready = true;
                } else {
                    self.loadData(
                        function() {
                            AdminLTEHelper.initializePermissions(self.page.variables, true);
                            self.renderForm();
                        }
                    );
                }
            }
        },
        renderForm: function() {
            document.getElementById("AdminLTEConfigFormContainer").innerHTML = "";

            
            var self = this;
            var elementHTML = "";
            var parentKey = "";
            self.list.forEach(element => {
                elementHTML = "";
                if ("group" == element.type) {
                    elementHTML = self.getGroupHTML(element);

                    if (element.__key.includes(".")) {
                        parentKey = self.getParentKey(element.__key);
                        document.getElementById("groupContainer" + parentKey).innerHTML += elementHTML;
                    } else {
                        document.getElementById("AdminLTEConfigFormContainer").innerHTML += elementHTML;
                    }
                } else if ("checkbox" == element.type) {
                    elementHTML = self.getCheckboxHTML(element);

                    if (element.__key.includes(".")) {
                        parentKey = self.getParentKey(element.__key);
                        document.getElementById("groupContainer" + parentKey).innerHTML += elementHTML;
                    } else {
                        document.getElementById("AdminLTEConfigFormContainer").innerHTML += elementHTML;
                    }
                } else if ("colorpicker" == element.type) {
                    elementHTML = self.getColorPickerHTML(element);

                    if (element.__key.includes(".")) {
                        parentKey = self.getParentKey(element.__key);
                        document.getElementById("groupContainer" + parentKey).innerHTML += elementHTML;
                    } else {
                        document.getElementById("AdminLTEConfigFormContainer").innerHTML += elementHTML;
                    }
                } else if ("datepicker" == element.type) {
                    elementHTML = self.getDatePickerHTML(element);

                    if (element.__key.includes(".")) {
                        parentKey = self.getParentKey(element.__key);
                        document.getElementById("groupContainer" + parentKey).innerHTML += elementHTML;
                    } else {
                        document.getElementById("AdminLTEConfigFormContainer").innerHTML += elementHTML;
                    }
                } else if ("datetimepicker" == element.type) {
                    elementHTML = self.getDateTimePickerHTML(element);

                    if (element.__key.includes(".")) {
                        parentKey = self.getParentKey(element.__key);
                        document.getElementById("groupContainer" + parentKey).innerHTML += elementHTML;
                    } else {
                        document.getElementById("AdminLTEConfigFormContainer").innerHTML += elementHTML;
                    }
                } else if ("dropdown" == element.type) {
                    elementHTML = self.getDropdownHTML(element);

                    if (element.__key.includes(".")) {
                        parentKey = self.getParentKey(element.__key);
                        document.getElementById("groupContainer" + parentKey).innerHTML += elementHTML;
                    } else {
                        document.getElementById("AdminLTEConfigFormContainer").innerHTML += elementHTML;
                    }

                    self.setDropdownOptions(element);
                } else if ("file" == element.type) {
                    elementHTML = self.getFileHTML(element);

                    if (element.__key.includes(".")) {
                        parentKey = self.getParentKey(element.__key);
                        document.getElementById("groupContainer" + parentKey).innerHTML += elementHTML;
                    } else {
                        document.getElementById("AdminLTEConfigFormContainer").innerHTML += elementHTML;
                    }
                } else if ("html_editor" == element.type) {
                    elementHTML = self.getHTMLEditorHTML(element);

                    if (element.__key.includes(".")) {
                        parentKey = self.getParentKey(element.__key);
                        document.getElementById("groupContainer" + parentKey).innerHTML += elementHTML;
                    } else {
                        document.getElementById("AdminLTEConfigFormContainer").innerHTML += elementHTML;
                    }
                } else if ("iconpicker" == element.type) {
                    elementHTML = self.getIconPickerHTML(element);

                    if (element.__key.includes(".")) {
                        parentKey = self.getParentKey(element.__key);
                        document.getElementById("groupContainer" + parentKey).innerHTML += elementHTML;
                    } else {
                        document.getElementById("AdminLTEConfigFormContainer").innerHTML += elementHTML;
                    }
                } else if ("integer" == element.type) {
                    elementHTML = self.getIntegerHTML(element);

                    if (element.__key.includes(".")) {
                        parentKey = self.getParentKey(element.__key);
                        document.getElementById("groupContainer" + parentKey).innerHTML += elementHTML;
                    } else {
                        document.getElementById("AdminLTEConfigFormContainer").innerHTML += elementHTML;
                    }
                } else if ("link_button" == element.type) {
                    elementHTML = self.getLinkButtonHTML(element);

                    if (element.__key.includes(".")) {
                        parentKey = self.getParentKey(element.__key);
                        document.getElementById("groupContainer" + parentKey).innerHTML += elementHTML;
                    } else {
                        document.getElementById("AdminLTEConfigFormContainer").innerHTML += elementHTML;
                    }
                } else if ("link_text" == element.type) {
                    elementHTML = self.getLinkTextHTML(element);

                    if (element.__key.includes(".")) {
                        parentKey = self.getParentKey(element.__key);
                        document.getElementById("groupContainer" + parentKey).innerHTML += elementHTML;
                    } else {
                        document.getElementById("AdminLTEConfigFormContainer").innerHTML += elementHTML;
                    }
                } else if ("number" == element.type) {
                    elementHTML = self.getNumberHTML(element);

                    if (element.__key.includes(".")) {
                        parentKey = self.getParentKey(element.__key);
                        document.getElementById("groupContainer" + parentKey).innerHTML += elementHTML;
                    } else {
                        document.getElementById("AdminLTEConfigFormContainer").innerHTML += elementHTML;
                    }
                } else if ("password" == element.type) {
                    elementHTML = self.getPasswordHTML(element);

                    if (element.__key.includes(".")) {
                        parentKey = self.getParentKey(element.__key);
                        document.getElementById("groupContainer" + parentKey).innerHTML += elementHTML;
                    } else {
                        document.getElementById("AdminLTEConfigFormContainer").innerHTML += elementHTML;
                    }
                } else if ("radio" == element.type) {
                    elementHTML = self.getRadioHTML(element);

                    if (element.__key.includes(".")) {
                        parentKey = self.getParentKey(element.__key);
                        document.getElementById("groupContainer" + parentKey).innerHTML += elementHTML;
                    } else {
                        document.getElementById("AdminLTEConfigFormContainer").innerHTML += elementHTML;
                    }
                } else if ("readonly_content" == element.type) {
                    elementHTML = self.getReadonlyContentHTML(element);

                    if (element.__key.includes(".")) {
                        parentKey = self.getParentKey(element.__key);
                        document.getElementById("groupContainer" + parentKey).innerHTML += elementHTML;
                    } else {
                        document.getElementById("AdminLTEConfigFormContainer").innerHTML += elementHTML;
                    }
                } else if ("shorttext" == element.type) {
                    elementHTML = self.getShorttextHTML(element);

                    if (element.__key.includes(".")) {
                        parentKey = self.getParentKey(element.__key);
                        document.getElementById("groupContainer" + parentKey).innerHTML += elementHTML;
                    } else {
                        document.getElementById("AdminLTEConfigFormContainer").innerHTML += elementHTML;
                    }
                } else if ("switch" == element.type) {
                    elementHTML = self.getSwitchHTML(element);

                    if (element.__key.includes(".")) {
                        parentKey = self.getParentKey(element.__key);
                        document.getElementById("groupContainer" + parentKey).innerHTML += elementHTML;
                    } else {
                        document.getElementById("AdminLTEConfigFormContainer").innerHTML += elementHTML;
                    }
                } else if ("textarea" == element.type) {
                    elementHTML = self.getTextareaHTML(element);

                    if (element.__key.includes(".")) {
                        parentKey = self.getParentKey(element.__key);
                        document.getElementById("groupContainer" + parentKey).innerHTML += elementHTML;
                    } else {
                        document.getElementById("AdminLTEConfigFormContainer").innerHTML += elementHTML;
                    }
                } else if ("timepicker" == element.type) {
                    elementHTML = self.getTimePickerHTML(element);

                    if (element.__key.includes(".")) {
                        parentKey = self.getParentKey(element.__key);
                        document.getElementById("groupContainer" + parentKey).innerHTML += elementHTML;
                    } else {
                        document.getElementById("AdminLTEConfigFormContainer").innerHTML += elementHTML;
                    }
                } else if ("toggle" == element.type) {
                    elementHTML = self.getToggleHTML(element);

                    if (element.__key.includes(".")) {
                        parentKey = self.getParentKey(element.__key);
                        document.getElementById("groupContainer" + parentKey).innerHTML += elementHTML;
                    } else {
                        document.getElementById("AdminLTEConfigFormContainer").innerHTML += elementHTML;
                    }
                }  
            });

            var switchInputs = self.$el.querySelectorAll(".vue-switch");
            switchInputs.forEach(switchInput => {
                AdminLTEHelper.updateSwitch(switchInput);
            });

            $(".config-toggle").off('switchChange.bootstrapSwitch').on('switchChange.bootstrapSwitch', function (event, state) {
                self.doConfigToggleChange(this, state);
            });

            self.initToggleElements();

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
                var elAppend = document.getElementById(this.id + "append")
                elAppend.style.background = colorHex;
                elAppend.style.borderColor = colorHex;
            });
            
            //initialize ederken
            /* $("#iconbackgroundPicker").val(data.iconbackground);
            $("#iconbackgroundPicker").trigger('change');
            $("#ulWidgetEditor_icon").css("background", data.iconbackground); */

            var iconPickerOptions = {
                searchText: "...",
                labelHeader: "{0} / {1}",
                cols: 4, rows: 4, 
                footer: false, 
                iconset: "fontawesome5"
            };

            var iconPicker = $(".icon-picker").iconpicker(iconPickerOptions);
            iconPicker.on("change", function (e) {
                document.getElementById(this.id + "-value").value = e.icon;
            });

            $(".config-file").on('change', function(e){
                self.updateFile(this); 
            });

            $(".file_download").on('click', function(e){
                self.downloadFile(this.getAttribute("data-key"));
            });

            self.initailizeSelect2();

            setTimeout(function(){
                $(".use-parameter-default-value").on('click', function(e){
                    self.setDefaultValue(this);
                });

                self.setValues();
            }, 300);
        },
        getGroupHTML: function(element) {
            var templateHTML = document.getElementById("groupTemplate").innerHTML;
            return templateHTML
                    .replace(/__group_title__/g, element.title)
                    .replace(/__group_key__/g, element.__key);
        },
        getCheckboxHTML: function(element) {
            var requiredClass = "d-none";
            if (element.required) {
                requiredClass = "required";
            }

            var templateHTML = document.getElementById("checkboxTemplate").innerHTML;
            return templateHTML
                    .replace(/__field_title__/g, element.title)
                    .replace(/__field_key__/g, element.__key)
                    .replace(/__delete__/g, "")
                    .replace(/__required_class__/g, requiredClass)
                    .replace(/__default_value__/g, element.default_value);
        },
        getColorPickerHTML: function(element) {
            var requiredClass = "d-none";
            if (element.required) {
                requiredClass = "required";
            }

            var templateHTML = document.getElementById("colorpickerTemplate").innerHTML;
            return templateHTML
                    .replace(/__field_title__/g, element.title)
                    .replace(/__field_key__/g, element.__key)
                    .replace(/__delete__/g, "")
                    .replace(/__required_class__/g, requiredClass)
                    .replace(/__default_value__/g, element.default_value);
        },
        getDatePickerHTML: function(element) {
            var requiredClass = "d-none";
            if (element.required) {
                requiredClass = "required";
            }

            var templateHTML = document.getElementById("datepickerTemplate").innerHTML;
            return templateHTML
                    .replace(/__field_title__/g, element.title)
                    .replace(/__field_key__/g, element.__key)
                    .replace(/__delete__/g, "")
                    .replace(/__required_class__/g, requiredClass)
                    .replace(/__default_value__/g, element.default_value);
        },
        getDateTimePickerHTML: function(element) {
            var requiredClass = "d-none";
            if (element.required) {
                requiredClass = "required";
            }

            var templateHTML = document.getElementById("datetimepickerTemplate").innerHTML;
            return templateHTML
                    .replace(/__field_title__/g, element.title)
                    .replace(/__field_key__/g, element.__key)
                    .replace(/__delete__/g, "")
                    .replace(/__required_class__/g, requiredClass)
                    .replace(/__default_value__/g, element.default_value);
        },
        getDropdownHTML: function(element) {
            var requiredClass = "d-none";
            if (element.required) {
                requiredClass = "required";
            }

            var multiple = "";
            if (element.multiple) {
                multiple = "multiple";
            }
            
            var templateHTML = document.getElementById("dropdownTemplate").innerHTML;
            return templateHTML
                    .replace(/__multiple__/g, multiple)
                    .replace(/__field_title__/g, element.title)
                    .replace(/__field_key__/g, element.__key)
                    .replace(/__field_key_converted__/g, element.__key.replace(/\./g, ""))
                    .replace(/__delete__/g, "")
                    .replace(/__required_class__/g, requiredClass)
                    .replace(/__default_value__/g, element.default_value);
        },
        setDropdownOptions(element) {
            //var selectId = element.__key.replace(/\./g, "");
            var selectId = element.__key;
            var values = element.option_values.split("\n");
            
            var titles = element.option_titles.split("\n");
            var length = values.length;

            for (let index = 0; index < length; index++) {
                document.getElementById(selectId).innerHTML +=
                '<option value="' + values[index] + '">' + titles[index] + '</option>'
            }
        },
        getDropdownOptions(element) {
            var options = [];
            var option = {};
            var values = element.option_values.split("\n");
            
            var titles = element.option_titles.split("\n");
            var length = values.length;

            for (let index = 0; index < length; index++) {
                option["id"] = values[index];
                option["text"] = titles[index];
                options.push(option);
            }

            return options;
        },
        initailizeSelect2(){
            $(".configselect2").select2();

            /* $(document.getElementById(selectId)).select2({
                ajax: {
                    url: "ajaxfile.php",
                    type: "post",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            searchTerm: params.term // search term
                        };
                    },
                    processResults: function (response) {
                        return {
                            results: response
                        };
                    },
                    cache: true
                }
            }); */
        },
        getFileHTML: function(element) {
            var requiredClass = "d-none";
            if (element.required) {
                requiredClass = "required";
            }

            var fileTypes = "";
            if ("" != element.file_types) {
                fileTypes = element.file_types;
            }

            var templateHTML = document.getElementById("fileTemplate").innerHTML;
            return templateHTML
                    .replace(/__field_title__/g, element.title)
                    .replace(/__field_key__/g, element.__key)
                    .replace(/__value__/g, element.value)
                    .replace(/__delete__/g, "")
                    .replace(/__required_class__/g, requiredClass)
                    .replace(/__file_types__/g, fileTypes);
        },
        getHTMLEditorHTML: function(element) {
            var requiredClass = "d-none";
            if (element.required) {
                requiredClass = "required";
            }

            var templateHTML = document.getElementById("htmlEditorTemplate").innerHTML;
            return templateHTML
                    .replace(/__field_title__/g, element.title)
                    .replace(/__field_key__/g, element.__key)
                    .replace(/__delete__/g, "")
                    .replace(/__required_class__/g, requiredClass)
                    .replace(/__default_value__/g, element.default_value);
        },
        getIconPickerHTML: function(element) {
            var requiredClass = "d-none";
            if (element.required) {
                requiredClass = "required";
            }

            var templateHTML = document.getElementById("iconPickerTemplate").innerHTML;
            return templateHTML
                    .replace(/__field_title__/g, element.title)
                    .replace(/__field_key__/g, element.__key)
                    .replace(/__delete__/g, "")
                    .replace(/__required_class__/g, requiredClass)
                    .replace(/__default_value__/g, element.default_value);
        },
        getIntegerHTML: function(element) {
            var requiredClass = "d-none";
            if (element.required) {
                requiredClass = "required";
            }

            var templateHTML = document.getElementById("integerTemplate").innerHTML;
            return templateHTML
                    .replace(/__field_title__/g, element.title)
                    .replace(/__field_key__/g, element.__key)
                    .replace(/__delete__/g, "")
                    .replace(/__required_class__/g, requiredClass)
                    .replace(/__min__/g, element.min)
                    .replace(/__max__/g, element.max)
                    .replace(/__step__/g, element.step)
                    .replace(/__default_value__/g, element.default_value);
        },
        getLinkButtonHTML: function(element) {
            var templateHTML = document.getElementById("link_buttonTemplate").innerHTML;
            return templateHTML
                    .replace(/__field_title__/g, element.title)
                    .replace(/__field_key__/g, element.__key)
                    .replace(/__url__/g, element.url)
                    .replace(/__delete__/g, "");
        },
        getLinkTextHTML: function(element) {
            var templateHTML = document.getElementById("link_textTemplate").innerHTML;
            return templateHTML
                    .replace(/__field_title__/g, element.title)
                    .replace(/__field_key__/g, element.__key)
                    .replace(/__url__/g, element.url)
                    .replace(/__delete__/g, "");
        },
        getNumberHTML: function(element) {
            var requiredClass = "d-none";
            if (element.required) {
                requiredClass = "required";
            }

            var templateHTML = document.getElementById("numberTemplate").innerHTML;
            return templateHTML
                    .replace(/__field_title__/g, element.title)
                    .replace(/__field_key__/g, element.__key)
                    .replace(/__delete__/g, "")
                    .replace(/__required_class__/g, requiredClass)
                    .replace(/__min__/g, element.min)
                    .replace(/__max__/g, element.max)
                    .replace(/__step__/g, element.step)
                    .replace(/__default_value__/g, element.default_value);
        },
        getPasswordHTML: function(element) {
            var requiredClass = "d-none";
            if (element.required) {
                requiredClass = "required";
            }

            var templateHTML = document.getElementById("passwordTemplate").innerHTML;
            return templateHTML
                    .replace(/__field_title__/g, element.title)
                    .replace(/__field_key__/g, element.__key)
                    .replace(/__delete__/g, "")
                    .replace(/__required_class__/g, requiredClass)
                    .replace(/__default_value__/g, element.default_value);
        },
        getRadioHTML: function(element) {
            var requiredClass = "d-none";
            if (element.required) {
                requiredClass = "required";
            }
            
            var radioOptionsHTML = this.getRadioOptionsHTML(element);
            var templateHTML = document.getElementById("radioTemplate").innerHTML;
            return templateHTML
                    .replace(/__field_title__/g, element.title)
                    .replace(/__field_key__/g, element.__key)
                    .replace(/__radio_options_html__/g, radioOptionsHTML)
                    .replace(/__delete__/g, "")
                    .replace(/__required_class__/g, requiredClass)
                    .replace(/__default_value__/g, element.default_value);
        },
        getRadioOptionsHTML: function(element) {
            var optionsHTML = "";
            var optionTemplateHTML = document.getElementById("radioOptionTemplate").innerHTML;
            var templateHTML = "";

            var values = element.option_values.split("\n");
            var titles = element.option_titles.split("\n");
            var length = values.length;

            for (let index = 0; index < length; index++) {
                templateHTML = optionTemplateHTML;
                templateHTML = templateHTML
                    .replace(/__field_key__/g, element.__key)
                    .replace(/__option_index__/g, index)
                    .replace(/__option_value__/g, values[index])
                    .replace(/__option_title__/g, titles[index]);

                optionsHTML += templateHTML;
            }
            
            return optionsHTML;
        },
        getReadonlyContentHTML: function(element) {
            var templateHTML = document.getElementById("readonly_contentTemplate").innerHTML;
            return templateHTML
                    .replace(/__field_title__/g, element.title)
                    .replace(/__field_key__/g, element.__key)
                    .replace(/__content__/g, element.content)
                    .replace(/__delete__/g, "");
        },
        getShorttextHTML: function(element) {
            var requiredClass = "d-none";
            if (element.required) {
                requiredClass = "required";
            }

            var templateHTML = document.getElementById("shorttextTemplate").innerHTML;
            return templateHTML
                    .replace(/__field_title__/g, element.title)
                    .replace(/__field_key__/g, element.__key)
                    .replace(/__delete__/g, "")
                    .replace(/__required_class__/g, requiredClass)
                    .replace(/__default_value__/g, element.default_value);
        },
        getSwitchHTML: function(element) {
            var templateHTML = document.getElementById("switchTemplate").innerHTML;
            return templateHTML
                    .replace(/__field_title__/g, element.title)
                    .replace(/__field_key__/g, element.__key)
                    .replace(/__delete__/g, "")
                    .replace(/__default_value__/g, element.default_value);
        },
        getTextareaHTML: function(element) {
            var requiredClass = "d-none";
            if (element.required) {
                requiredClass = "required";
            }

            var templateHTML = document.getElementById("textareaTemplate").innerHTML;
            return templateHTML
                    .replace(/__field_title__/g, element.title)
                    .replace(/__field_key__/g, element.__key)
                    .replace(/__delete__/g, "")
                    .replace(/__required_class__/g, requiredClass)
                    .replace(/__default_value__/g, element.default_value);
        },
        getTimePickerHTML: function(element) {
            var requiredClass = "d-none";
            if (element.required) {
                requiredClass = "required";
            }

            var templateHTML = document.getElementById("timepickerTemplate").innerHTML;
            return templateHTML
                    .replace(/__field_title__/g, element.title)
                    .replace(/__field_key__/g, element.__key)
                    .replace(/__delete__/g, "")
                    .replace(/__required_class__/g, requiredClass)
                    .replace(/__default_value__/g, element.default_value);
        },
        getToggleHTML: function(element) {
            this.init_toggles = true;

            var templateHTML = document.getElementById("toggleTemplate").innerHTML;
            return templateHTML
                    .replace(/__field_title__/g, element.title)
                    .replace(/__field_key__/g, element.__key)
                    .replace(/__toggle_elements__/g, element.toggle_elements)
                    .replace(/__delete__/g, "");
        },
        initToggleElements: function() {
            if (!this.init_toggles) {
                return;
            }

            var toggleElements = $(".config-toggle");

            for (let index = 0; index < toggleElements.length; index++) {
                const toggle = toggleElements[index];
                
                toggle.getAttribute("data-toggle-elements").split(",").forEach(element => {
                    $(".toggle-able[data-key='" + element + "']").addClass("d-none")
                });
            }
        },
        getParentKey: function(elementKey) {
            var parts = elementKey.split(".");
            parts.pop();
            return parts.join(".");
        },
        doConfigToggleChange: function(toggleButton, state) {
            var toggle_elements = toggleButton.getAttribute("data-toggle-elements").split(",");

            if (state) {
                toggle_elements.forEach(element => {
                   $(".toggle-able[data-key='" + element + "']").removeClass("d-none")
                });
            } else {
                toggle_elements.forEach(element => {
                   $(".toggle-able[data-key='" + element + "']").addClass("d-none")
                });
            }
        },
        setDefaultValue: function(btn) {
            var type = btn.getAttribute("data-type");
            var elementKey = btn.getAttribute("data-key");
            var val = btn.getAttribute("default-value");

            if ("checkbox" == type) {
                if ("on" == val) {
                    document.getElementById(elementKey).checked = true;
                }
            } else if ("colorpicker" == type) {
                var colorPicker = document.getElementById(elementKey);
                $(colorPicker).val(val);
                $(colorPicker).trigger('change');
            } else if ("datepicker" == type) {
                document.getElementById(elementKey).value = val;
            } else if ("datetimepicker" == type) {
                document.getElementById(elementKey).value = val;
            } else if ("dropdown" == type) {
                if ("multiple" == btn.getAttribute("data-multiple")) {
                    $(document.getElementById(elementKey)).val(val.split(",")).trigger('change');
                } else {
                    $(document.getElementById(elementKey)).val(val).trigger('change');
                }
            } /* else if ("file" == type) {
            } */ else if ("html_editor" == type) {
                $(document.getElementById(elementKey)).summernote("code", val);
            } else if ("iconpicker" == type) {
                if ("" == val || undefined === val) {
                    $(document.getElementById(elementKey)).iconpicker('setIcon', 'empty');
                } else{
                    $(document.getElementById(elementKey)).iconpicker('setIcon', val);
                }
            } else if ("integer" == type) {
                document.getElementById(elementKey).value = val;
            } /* else if ("link_button" == type) {
            } else if ("link_text" == type) {
            } */ else if ("number" == type) {
                document.getElementById(elementKey).value = val;
            } else if ("password" == type) {
                document.getElementById(elementKey).value = val;
            } else if ("radio" == type) {
                var selectorText = 'input[name="' + elementKey + '"][value="' + val + '"]';
                $(selectorText).prop('checked', true);

            }/*  else if ("readonly_content" == type) {
            } */ else if ("shorttext" == type) {
                document.getElementById(elementKey).value = val;
            } else if ("switch" == type) {
                if ("on" == val) {
                    $(document.getElementById(elementKey)).bootstrapSwitch("state", true);
                } else {
                    $(document.getElementById(elementKey)).bootstrapSwitch("state", false);
                }
            } else if ("textarea" == type) {
                $(document.getElementById(elementKey)).val(val);
            } else if ("timepicker" == type) {
                document.getElementById(elementKey).value = val;
            } else if ("toggle" == type) {
                if ("on" == val) {
                    $(document.getElementById(elementKey)).bootstrapSwitch("state", val).trigger("change");
                }
            }  
        },

        resetValues: function() {
            
        },
        setValues: function() {
            var self = this;
            self.resetValues();

            var elementKey = "";
            var val = "";
            self.list.forEach(element => {
                elementKey = element.__key;

                if ("checkbox" == element.type) {
                    val = element.default_value;

                    if ("" != element.value) {
                        val = element.value;
                    }

                    if ("on" == val) {
                        document.getElementById(elementKey).checked = true;
                    }
                } else if ("colorpicker" == element.type) {
                    val = element.default_value;

                    if ("" != element.value) {
                        val = element.value;
                    }

                    var colorPicker = document.getElementById(elementKey);
                    $(colorPicker).val(val);
                    $(colorPicker).trigger('change');
                } else if ("datepicker" == element.type) {
                    val = element.default_value;

                    if ("" != element.value) {
                        val = element.value;
                    }

                    document.getElementById(elementKey).value = val;
                } else if ("datetimepicker" == element.type) {
                    val = element.default_value;

                    if ("" != element.value) {
                        val = element.value;
                    }

                    document.getElementById(elementKey).value = val;
                } else if ("dropdown" == element.type) {
                    val = element.default_value;

                    if ("" != element.value) {
                        val = element.value;
                    }

                    if (element.multiple) {
                        $(document.getElementById(elementKey)).val(val.split(",")).trigger('change');
                    } else {
                        $(document.getElementById(elementKey)).val(val).trigger('change');
                    }
                } /* else if ("file" == element.type) {
                } */ else if ("html_editor" == element.type) {
                    val = element.default_value;

                    if ("" != element.value) {
                        val = element.value;
                    }

                    $(document.getElementById(elementKey)).summernote("code", val);
                } else if ("iconpicker" == element.type) {
                    val = element.default_value;

                    if ("" != element.value) {
                        val = element.value;
                    }

                    if ("" == val || undefined === val) {
                        $(document.getElementById(elementKey)).iconpicker('setIcon', 'empty');
                    } else{
                        $(document.getElementById(elementKey)).iconpicker('setIcon', val);
                    }
                } else if ("integer" == element.type) {
                    val = element.default_value;

                    if ("" != element.value) {
                        val = element.value;
                    }

                    document.getElementById(elementKey).value = val;
                } /* else if ("link_button" == element.type) {
                } else if ("link_text" == element.type) {
                } */ else if ("number" == element.type) {
                    val = element.default_value;

                    if ("" != element.value) {
                        val = element.value;
                    }

                    document.getElementById(elementKey).value = val;
                } else if ("password" == element.type) {
                    val = element.default_value;

                    if ("" != element.value) {
                        val = element.value;
                    }

                    document.getElementById(elementKey).value = val;
                } else if ("radio" == element.type) {
                    val = element.default_value;

                    if ("" != element.value) {
                        val = element.value;
                    }

                    var selectorText = 'input[name="' + elementKey + '"][value="' + val + '"]';
                    $(selectorText).prop('checked', true);

                }/*  else if ("readonly_content" == element.type) {
                } */ else if ("shorttext" == element.type) {
                    val = element.default_value;

                    if ("" != element.value) {
                        val = element.value;
                    }

                    document.getElementById(elementKey).value = val;
                } else if ("switch" == element.type) {
                    val = element.default_value;

                    if ("" != element.value) {
                        val = element.value;
                    }

                    if ("on" == val) {
                        $(document.getElementById(elementKey)).bootstrapSwitch("state", true);
                    } else {
                        $(document.getElementById(elementKey)).bootstrapSwitch("state", false);
                    }
                } else if ("textarea" == element.type) {
                    val = element.default_value;

                    if ("" != element.value) {
                        val = element.value;
                    }

                    $(document.getElementById(elementKey)).val(val);
                } else if ("timepicker" == element.type) {
                    val = element.default_value;

                    if ("" != element.value) {
                        val = element.value;
                    }

                    document.getElementById(elementKey).value = val;
                } else if ("toggle" == element.type) {
                    val = element.default_value;

                    if ("" != element.value) {
                        val = element.value;
                    }

                    if ("on" == val) {
                        $(document.getElementById(elementKey)).bootstrapSwitch("state", val).trigger("change");
                        /* sender.dispatchEvent(new Event('change')); */
                    }
                }  
            });

            setTimeout(function() {
                self.body_loader_active = false;
            }, 500);
        },
        loadPageVariables: function () {
            var self = this;

            if (self.page.is_variables_loading) {
                return;
            }

            self.page.is_variables_loading = true;

            axios.get(AdminLTEHelper.getAPIURL("adminlte/get_page_variables/adminlteconfig"))
                .then(({ data }) => {
                    self.page.is_variables_loaded = true;
                    self.page.is_variables_loading = false;
                    self.page.variables = data;
                }).catch(({ data }) => {
                    self.page.is_variables_loaded = true;
                    self.page.is_variables_loading = false;
                    self.$Progress.fail();
                    self.page.has_server_error = true;
                    self.processLoadQueue();
                }).finally(function() {
                   AdminLTEHelper.initializePermissions(self.page.variables, true);
                   let authorize = AdminLTEHelper.isUserAuthorized(self.page.variables, "adminlteconfig");
                   self.page.is_authorized = authorize.status;
                   self.page.unauthorized_type = authorize.type;
                   self.processLoadQueue();
                });
        },
        downloadFile: function (__key) {
            var self = this;

            if (self.page.is_variables_loading) {
                return;
            }

            self.page.is_variables_loading = true;

            axios.get(AdminLTEHelper.getAPIURL("adminlteconfig/download_file/" + __key))
                .then(({ data }) => {
                    var a = document.createElement("a"); //Create <a>
                    a.href = data.url; //Image Base64 Goes here
                    a.download = data.filename; //File name Here
                    a.click(); //Downloaded file
                }).catch(({ data }) => {
                    console.log("hata var")
                }).finally(function() {
                   self.processLoadQueue();
                });
        },
        loadData: function (callback) {
            var self = this;

            if (self.page.is_configlist_loading) {
                return;
            }

            var query = AdminLTEHelper.getURLQuery(self);
            axios.get(AdminLTEHelper.getAPIURL("adminlteconfig/getlist/" + query))
                .then(({ data }) => {
                    self.page.is_configlist_loaded = true;
                    self.page.is_configlist_loading = false;
                    self.data = data;
                    self.list = data.data.list;
                    self.show_pagination = data.show_pagination;
                }).catch(({ data }) => {
                    self.page.is_configlist_loaded = true;
                    self.page.is_configlist_loading = false;
                    self.$Progress.fail();
                     self.page.has_server_error = true;
                    self.processLoadQueue();
                }).finally(function() {
                    callback();
                    AdminLTEHelper.cleanCheckedBoxes('AdminLTEConfig');
                });
        },
        search_list: _.debounce(function (e) {
            var self = this;
            var search_input = e.target;
            
            AdminLTEHelper.activateSearchLoader(search_input);

            self.search_text = search_input.value;
            self.current_page = 1;

            self.loadData(function(){
                AdminLTEHelper.deactivateSearchLoader(search_input);
                self.renderForm();
            });
        }, 1000),
        paginate: function (page = 1) {
            this.current_page = page;
            this.loadData(function(){});
        },
        sort: function (variable) {
            AdminLTEHelper.activateSortLoader(`button_sort_AdminLTEConfig_${variable}`);

            this.sort_variable = variable;
            this.sort_direction = ('asc' == this.sort_direction) ? 'desc' : 'asc';
            this.current_page = 1;

            var self = this;
            this.loadData(function() {
                AdminLTEHelper.deactivateSortLoader(`button_sort_AdminLTEConfig_${self.sort_variable}`, self.sort_direction)
            });
        },
        select_all_row: function(target) {
            AdminLTEHelper.doCheckAllCheckboxClick(target);
        },
        select_row: function(target) {
            AdminLTEHelper.doCheckboxClick(target);
        },
        deleteSelectedRows: function(sender, model) {
            var self = this;
            Vue.swal.fire({
                title: self.$t("Selected records will be deleted."),
                text: self.$t("Do you confirm?"),
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: self.$t("Continue"),
                cancelButtonText: self.$t("Cancel")
            }).then((result) => {
                if (result.isConfirmed) {
                    self.formDelete.selected_ids = AdminLTEHelper.getTableSelectedRowIds("AdminLTEConfig");
                    self.submitDeleteForm();
                }
            });
        },
        submitDeleteForm: function () {
            var self = this;
            self.$Progress.start();
            self.formDelete.post(AdminLTEHelper.getAPIURL("adminlteconfig/delete"))
                .then(({ data }) => {
                    self.$Progress.finish();
                    self.delete_form.has_error = data.has_error;
                    self.delete_form.error_msg = data.error_msg;
                }).catch(({ data }) => {
                    self.$Progress.fail();
                }).finally(function(){
                    if (!self.delete_form.has_error) {
                        self.loadData(function(){
                            Vue.swal.fire({
                                position: 'top-end',
                                title: self.$t("Selected records have been deleted."),
                                icon: 'success',
                                showConfirmButton: false,
                                timer: 2000,
                                timerProgressBar: true,
                            });
                        });
                    } else {
                        Vue.swal.fire({
                            position: 'top-end',
                            title: self.delete_form.error_msg,
                            icon: 'error',
                            showConfirmButton: false,
                            timer: 10000,
                            timerProgressBar: true
                        });
                    }
                });
        },
        submitConfigForm: function () {
            var self = this;

            let formData = new FormData();
            
            self.collectConfigData(formData);
            
            formData.append('config_data', JSON.stringify(this.formConfig.config_data));

            self.$Progress.start();

            axios.post( 
                    AdminLTEHelper.getAPIURL("adminlteconfig/post_config_data"),
                    formData,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }
                )
                .then(({ data }) => {
                    self.$Progress.finish();
                    self.id = data.id;
                    self.page.has_post_error = data.has_error;
                    self.page.post_error_msg = data.error_msg;
                    self.page.has_server_error = false;
                }).catch(({ data }) => {
                    self.$Progress.fail();
                    self.page.has_server_error = true;
                }).finally(function() {
                    if (!self.page.has_server_error) {
                        if (!self.page.has_post_error) {
                            Vue.swal.fire({
                                position: 'top-end',
                                title: self.$t("Your changes have been saved!"),
                                icon: 'success',
                                showConfirmButton: false,
                                timer: 2000,
                                timerProgressBar: true,
                                onClose: () => {
                                    window.location.reload()
                                }
                            });
                        } else {
                            Vue.swal.fire({
                                position: 'top-end',
                                title: self.page.post_error_msg,
                                icon: 'error',
                                showConfirmButton: false,
                                timer: 10000,
                                timerProgressBar: true
                            });
                        }
                    }
                });
        },
        collectConfigData: function(formData) {
            var self = this;
            var config_data = [];

            self.$el.querySelectorAll(".config-parameter").forEach(element => {
                let parameter_data = {};

                if ("radio" == element.getAttribute("data-type")) {
                    let id = element.getAttribute("data-key");
                    parameter_data["type"] = "radio";
                    parameter_data["key"] = id;
                    let selectorText = 'input[name="' + id + '"]:checked';
                    parameter_data["val"] = $(selectorText).val();

                } else if ("dropdown" == element.getAttribute("data-type")) {
                    parameter_data["type"] = "dropdown";
                    parameter_data["key"] = element.getAttribute("data-key");
                    parameter_data["val"] = $(element).val();
                   
                    var attr = $(element).attr('multiple');
                    if (typeof attr !== 'undefined' && attr !== false) {
                        parameter_data["val"] = $(element).val().join(",");
                    }
                } else if ("file" == element.getAttribute("data-type")) {
                    parameter_data["type"] = "file";
                    parameter_data["key"] = element.id;

                    let file_data = {};
                    file_data["file_name"] = document.getElementById(element.id + "-file_name").value;
                    file_data["file_value"] = document.getElementById(element.id + "-file_value").value;

                    parameter_data["val"] = element.id.replace(/\./g, "");

                    formData.append(parameter_data["val"], self.uploadedFiles[element.id])
                    
                } else if ("iconpicker" == element.getAttribute("data-type")) {
                    parameter_data["type"] = "iconpicker";
                    parameter_data["key"] = element.getAttribute("data-key");
                    parameter_data["val"] = element.value;
                } else if ("switch" == element.getAttribute("data-type")) {
                    parameter_data["type"] = "switch";
                    parameter_data["key"] = element.getAttribute("data-key");
                    parameter_data["val"] = element.checked ? 'on' : 'off';
                } else {
                    parameter_data["type"] = "other";
                    parameter_data["key"] = element.id;
                    parameter_data["val"] = element.value;
                }

                console.log(parameter_data)

                config_data.push(parameter_data);
            });
            

            this.formConfig.config_data = config_data;
        },
        updateFile(e) {
            var self = this;
            let __key = e.id;
            let file = e.files[0];

            /* let extension = file.name.split('.').pop();

            let acceptableTypeCSV = e.getAttribute("accept");
            let acceptableTypes = acceptableTypeCSV.split(",");

            if (!acceptableTypes.includes(extension)) {
                Vue.swal.fire({
                    position: 'top-end',
                    title: self.$t("Acceptable file types: " + acceptableTypeCSV),
                    icon: 'error',
                    showConfirmButton: false,
                    timer: 10000,
                    timerProgressBar: true
                });
            } */

            self.uploadedFiles[__key] = file;

            document.getElementById(__key + "-file_name").value = file.name;

            let reader = new FileReader();

            let limit = 1024 * 1024 * 2;
            if(file['size'] > limit){
                swal({
                    type: 'error',
                    title: 'Oops...',
                    text: 'You are uploading a large file',
                })
                return false;
            }

            reader.onloadend = (file) => {
                document.getElementById(__key + "-file_value").value = reader.result;
            }

            reader.readAsDataURL(file);
        },
    },
    mounted() {
        var self = this;
        self.body_loader_active = true;
        self.main_folder = AdminLTEHelper.getMainFolder();
        self.page.is_ready = false;
        AdminLTEHelper.loadExternalFiles(self.page.external_files, self.processLoadQueue());
    }
}
</script>