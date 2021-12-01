<template>
    <div class="content-wrapper">
        <server-error v-if="page.has_server_error" ></server-error>
        <permission-error v-else-if="!page.is_authorized" :type="page.unauthorized_type"></permission-error>
        <div v-else>
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark">{{ $t('Settings') }}</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="home">{{ $t('Home') }}</a></li>
                                <li class="breadcrumb-item active">{{ $t('Configuration Parameters') }}</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <section class="content" v-show="page.is_ready">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <form id="formConfiguration" @submit.prevent="submitForm" @keydown="form.onKeydown($event)">
                                    <div class="card-body">
                                        <div class="row mb-10 ">
                                            <div class="col-lg-12">
                                                <a id="buttonNewMenuItem" class="btn btn-primary btn-md btn-on-card float-right" href="javascript:void(0);">
                                                    <i class="fa fa-plus"></i> <span class="hidden-xxs">{{ $t('New') }}</span>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12" id="configEditorContainer">
                                                <ul id="ulConfigEditor" class="list-group">
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer ">
                                        <button :disabled="form.busy"
                                            type="submit"
                                            class="btn btn-success btn-md btn-on-table float-right">
                                            {{ $t('Save') }}
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <div class="modal fade" id="modalMenuItem">
                <div class="modal-dialog modal-md">
                    <form id="formMenuItem" name="formMenuItem" method="post" class="form-horizontal">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">{{ $t('Config Parameter') }}</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" id="currentKey" value="">
                                <input type="hidden" id="currentParent" value="">
                                <input type="hidden" id="item_data" v-model="item_data" @click="updateForm" value="">
                                <input type="hidden" id="exception_key" v-model="exception_key" @click="refreshListByKey" value="">
                                <div class="form-group col-lg-12">
                                    <div class="">
                                        <div class="icheck-primary d-inline">
                                            <input type="checkbox"
                                                id="enabled"
                                                name="enabled"
                                                class="item-menu"
                                                v-model="AdminLTEConfigForm.enabled">
                                            <label for="enabled" class="">
                                                {{ $t('Enabled') }}  
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group col-lg-12" 
                                    v-show="(
                                        ('group' != AdminLTEConfigForm.type)
                                        && ('toggle' != AdminLTEConfigForm.type)
                                        && ('link_button' != AdminLTEConfigForm.type)
                                        && ('link_text' != AdminLTEConfigForm.type)
                                        && ('readonly_content' != AdminLTEConfigForm.type)
                                    )">
                                    <div class="">
                                        <div class="icheck-primary d-inline">
                                            <input type="checkbox"
                                                id="required"
                                                name="required"
                                                class="item-menu"
                                                v-model="AdminLTEConfigForm.required">
                                            <label for="required" class="">
                                                {{ $t('Required') }}  
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group col-lg-12">
                                    <label for="type">{{ $t('Type') }}</label>
                                    <select name="type" id="type" class="form-control item-menu" v-model="AdminLTEConfigForm.type">
                                        <option value="group">{{ $t('Group') }}</option>
                                        <option value="toggle">{{ $t('Toggle') }}</option>
                                        <option value="checkbox">{{ $t('Checkbox') }}</option>
                                        <option value="colorpicker">{{ $t('Color Picker') }}</option>
                                        <option value="datepicker">{{ $t('Date Picker') }}</option>
                                        <option value="datetimepicker">{{ $t('Date Time Picker') }}</option>
                                        <option value="dropdown">{{ $t('Dropdown') }}</option>
                                        <option value="file">{{ $t('File') }}</option>
                                        <option value="html_editor">{{ $t('HTML Editor') }}</option>
                                        <option value="iconpicker">{{ $t('Icon Picker') }}</option>
                                        <option value="integer">{{ $t('Integer') }}</option>
                                        <option value="link_button">{{ $t('Link (Button)') }}</option>
                                        <option value="link_text">{{ $t('Link (Text)') }}</option>
                                        <option value="number">{{ $t('Number') }}</option>
                                        <option value="password">{{ $t('Password') }}</option>
                                        <option value="radio">{{ $t('Radio') }}</option>
                                        <option value="readonly_content">{{ $t('Readonly Content') }}</option>
                                        <option value="shorttext">{{ $t('Shorttext') }}</option>
                                        <option value="switch">{{ $t('Switch') }}</option>
                                        <option value="textarea">{{ $t('Textarea') }}</option>
                                        <option value="timepicker">{{ $t('Time Picker') }}</option>
                                    </select>
                                    <span class="text-muted d-none" id="groupTypeWarning">
                                        {{ $t('This element type cannot be changed because its type is a group.') }}
                                    </span>
                                </div>

                                <div class="form-group col-lg-12" v-show="('dropdown' == AdminLTEConfigForm.type)">
                                    <div class="">
                                        <div class="icheck-primary d-inline">
                                            <input type="checkbox"
                                                id="multiple"
                                                name="multiple"
                                                class="item-menu"
                                                v-model="AdminLTEConfigForm.multiple">
                                            <label for="multiple" class="">
                                                {{ $t('Multiple') }}  
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group col-lg-12">
                                    <label for="__parent">{{ $t('Parent') }}</label>
                                    <select2-element class="select2-element item-menu"
                                        data-placeholder=""
                                        id="__parent"
                                        name="__parent"
                                        :options="parentlist"
                                        allowClear="true"
                                        v-model="AdminLTEConfigForm.__parent">
                                        <option></option>
                                    </select2-element>
                                </div>

                                <div class="form-group col-lg-12">
                                    <label for="title">{{ $t('Title') }}</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control item-menu" name="title" id="title" v-model="AdminLTEConfigForm.title">
                                        <input type="hidden" class="form-control item-menu" name="__key" id="__key" v-model="AdminLTEConfigForm.__key">
                                    </div>
                                </div>

                                <div class="form-group col-lg-6"
                                    v-show="(
                                        ('dropdown' == AdminLTEConfigForm.type)
                                        || ('radio' == AdminLTEConfigForm.type)
                                    )">
                                    <label for="option_titles">{{ $t('Option Titles') }}  </label>
                                    <textarea rows="5"
                                        id="option_titles"
                                        name="option_titles"
                                        v-model="AdminLTEConfigForm.option_titles"
                                        class="form-control item-menu"></textarea>
                                </div>
                                <div class="form-group col-lg-6"
                                    v-show="(
                                        ('dropdown' == AdminLTEConfigForm.type)
                                        || ('radio' == AdminLTEConfigForm.type)
                                    )">
                                    <label for="option_values">{{ $t('Option Values') }}  </label>
                                    <textarea rows="5"
                                        id="option_values"
                                        name="option_values"
                                        v-model="AdminLTEConfigForm.option_values"
                                        class="form-control item-menu"></textarea>
                                </div>

                                <div class="form-group col-lg-12" v-show="('toggle' == AdminLTEConfigForm.type)">
                                    <label for="toggle_elements">
                                        {{  $t('Toggle Elements') }}
                                    </label>
                                    <select2-element multiple="multiple"
                                        id="toggle_elements"
                                        name="toggle_elements"
                                        :options="toggle_elements_options"
                                        v-model="AdminLTEConfigForm.toggle_elements"
                                        class="select2-element item-menu">
                                    </select2-element>
                                    <input type="hidden" id="toggle_elements_data" value="1" :selected-data="AdminLTEConfigForm.toggle_elements">
                                </div>

                                <div class="form-group col-lg-12" v-show="(('link_button' == AdminLTEConfigForm.type) || ('link_text' == AdminLTEConfigForm.type))">
                                    <label for="url">{{ $t('URL') }}</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control item-menu" name="url" id="url" v-model="AdminLTEConfigForm.url">
                                    </div>
                                </div>

                                <div class="form-group col-lg-12" v-show="('file' == AdminLTEConfigForm.type)">
                                    <label for="file_types">{{ $t('Acceptable File Types') }}</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control item-menu" name="file_types" id="file_types" v-model="AdminLTEConfigForm.file_types">
                                        <span class="text-muted">
                                            {{ $t('This string is a comma-separated list of acceptable file extensions. For example: .jpg, .pdf, .doc') }}
                                        </span>
                                    </div>
                                </div>

                                <div class="form-group col-lg-4"
                                    v-show="(
                                        ('integer' == AdminLTEConfigForm.type)
                                        || ('number' == AdminLTEConfigForm.type)
                                    )">
                                    <label for="min">{{ $t('Min') }}</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control item-menu" name="min" id="min" v-model="AdminLTEConfigForm.min">
                                    </div>
                                </div>

                                <div class="form-group col-lg-4"
                                    v-show="(
                                        ('integer' == AdminLTEConfigForm.type)
                                        || ('number' == AdminLTEConfigForm.type)
                                    )">
                                    <label for="max">{{ $t('Max') }}</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control item-menu" name="max" id="max" v-model="AdminLTEConfigForm.max">
                                    </div>
                                </div>

                                <div class="form-group col-lg-4"
                                    v-show="(
                                        ('integer' == AdminLTEConfigForm.type)
                                        || ('number' == AdminLTEConfigForm.type)
                                    )">
                                    <label for="step">{{ $t('Step') }}</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control item-menu" name="step" id="step" v-model="AdminLTEConfigForm.step">
                                    </div>
                                </div>

                                <div class="form-group col-lg-12" 
                                    v-show="('readonly_content' == AdminLTEConfigForm.type)">
                                    <label for="content">{{ $t('Content') }}  </label>
                                    <textarea rows="5"
                                        v-model="AdminLTEConfigForm.content"
                                        id="content"
                                        name="content"
                                        class="form-control item-menu"></textarea>
                                </div>

                                <div class="form-group col-lg-12" 
                                    v-show="(
                                        ('group' != AdminLTEConfigForm.type)
                                        && ('file' != AdminLTEConfigForm.type)
                                        && ('link_button' != AdminLTEConfigForm.type)
                                        && ('link_text' != AdminLTEConfigForm.type)
                                        && ('readonly_content' != AdminLTEConfigForm.type)
                                    )">
                                    <label for="default_value">{{ $t('Default Value') }}  </label>
                                    <select id="default_value_checkbox" class="form-control"
                                        v-show="(
                                            ('toggle' == AdminLTEConfigForm.type)
                                            || ('checkbox' == AdminLTEConfigForm.type)
                                            || ('switch' == AdminLTEConfigForm.type)
                                        )">
                                        <option value="on">{{ $t('On') }}</option>
                                        <option value="off">{{ $t('Off') }}</option>
                                    </select>
                                    <div class="input-group"
                                        v-show="(
                                            ('dropdown' == AdminLTEConfigForm.type)
                                            || ('password' == AdminLTEConfigForm.type)
                                            || ('radio' == AdminLTEConfigForm.type)
                                            || ('shorttext' == AdminLTEConfigForm.type)
                                        )">
                                        <input type="text" class="form-control" id="default_value_text">
                                    </div>
                                    <div class="input-group"
                                        v-show="('integer' == AdminLTEConfigForm.type) || ('number' == AdminLTEConfigForm.type)">
                                        <input type="number" class="form-control" id="default_value_number">
                                    </div>
                                    <span class="text-muted" v-show="('dropdown' == AdminLTEConfigForm.type) && (!AdminLTEConfigForm.multiple)">
                                        {{ $t('This string should be one of option values.') }}
                                    </span>
                                    <span class="text-muted" v-show="('dropdown' == AdminLTEConfigForm.type) && (AdminLTEConfigForm.multiple)">
                                        {{ $t('This string is a comma-separated list of option values.') }}
                                    </span>
                                    <span class="text-muted" v-show="('radio' == AdminLTEConfigForm.type)">
                                        {{ $t('This string should be one of option values.') }}
                                    </span>
                                    <div class="input-group" v-show="('datepicker' == AdminLTEConfigForm.type)">
                                        <input type="date" class="form-control" id="default_value_datepicker">
                                    </div>
                                    <div class="input-group" v-show="('datetimepicker' == AdminLTEConfigForm.type)">
                                        <input type="datetime-local" class="form-control" id="default_value_datetimepicker">
                                    </div>
                                    <div class="input-group" v-show="('timepicker' == AdminLTEConfigForm.type)">
                                        <input type="time" class="form-control" id="default_value_timepicker">
                                    </div>
                                    <div v-show="('textarea' == AdminLTEConfigForm.type)">
                                        <textarea
                                            id="default_value_textarea"
                                            class="form-control"
                                            rows="5"></textarea>
                                    </div>
                                    <div v-show="('html_editor' == AdminLTEConfigForm.type)">
                                        <textarea 
                                            id="default_value_html_editor"
                                            class="form-control vue-editor"
                                            rows="5"></textarea>
                                    </div>
                                    <div class="input-group" v-show="('colorpicker' == AdminLTEConfigForm.type)">
                                        <input type="text"
                                            class="form-control color-picker"
                                            id="default_value_colorpicker">
                                        <div class="input-group-append">
                                            <span class="input-group-text" id="default_value_colorpicker_append" style="padding-left:100px;"></span>
                                        </div>
                                    </div>
                                    <button type="button" id="default_value_iconpicker_picker" class="btn btn-outline-secondary icon-picker"
                                        v-show="('iconpicker' == AdminLTEConfigForm.type)">
                                    </button>
                                    <input type="hidden" id="default_value_iconpicker" class="item-widget">

                                    <input type="hidden" class="form-control item-menu" 
                                        name="default_value" id="default_value" 
                                        v-model="AdminLTEConfigForm.default_value">
                                    <input type="hidden" class="form-control item-menu" 
                                        name="value" id="value" 
                                        v-model="AdminLTEConfigForm.value">
                                </div>
                            </div>
                            <div class="modalfooter justify-content-between ">
                                <div class="row">
                                    <div class="col col-lg-12">
                                        <button type="button"
                                            id="buttonAddMenuItem"
                                            name="buttonAddMenuItem"
                                            class="btn btn-success float-right"
                                            style="display:none;">
                                            {{ $t('Save') }}
                                        </button>
                                        <button type="button"
                                            id="buttonUpdateMenuItem"
                                            name="buttonUpdateMenuItem"
                                            class="btn btn-success float-right"
                                            style="display:none;">
                                            {{ $t('Save') }}
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary float-right" data-dismiss="modal" style="margin-right:10px;">{{ $t('Cancel') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal fade" id="modalMenuItemDelete">
                <div class="modal-dialog modal-md">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">{{ $t('Menu Item Delete') }}</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <p id="deleteWarning">{{ $t('The selected item will be deleted. Do you confirm?') }}</p>
                                <p id="groupDeleteWarning" class="d-none">{{ $t('The selected item will be deleted along with its children. Do you confirm?') }}</p>
                            </div>
                        </div>
                        <div class="modalfooter justify-content-between ">
                            <div class="row">
                                <div class="col col-lg-12">
                                    <button type="button" class="btn btn-outline-secondary float-left" data-dismiss="modal">{{ $t('Cancel') }}</button>
                                    <button type="button"
                                        id="buttonDeleteMenuItem"
                                        name="buttonDeleteMenuItem"
                                        class="btn btn-danger float-right">
                                        {{ $t('Continue') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <body-loader :body_loader_active="body_loader_active" class="content-wrapper bodyLoader"></body-loader>
    </div>
</template>

<script>
export default {
    data() {
        return {
            main_folder: '',
            pagename: '',
            form: new Form({
                'config_json': ''
            }),
            listByKey: [],
            item_data: [],
            exception_key: '',
            AdminLTEConfigForm: new Form({
                'debug_mode': false,
                'id': this.id,
				'enabled': false,
				'required': false,
                'multiple': false,
                'type': '',
                '__key': '',
                'title': '',
                '__parent': 'xxxxx',
                'option_titles': '',
                'option_values': '',
                'toggle_elements': [],
                'url': '',
                'min': 0,
                'max': 0,
                'step': 0,
                'file_types': '',
                'content': '',
                'default_value': '',
                'value': '',
            }),
            parentlist: [],
            toggle_elements_options: [],
            page: {
                is_ready: false,
                has_server_error: false,
                variables: [],
                is_authorized: true,
                unauthorized_type: '',
                is_variables_loading: false,
                is_variables_loaded: false,
                is_listbykey_loading: false,
                is_listbykey_loaded: false,
                is_parentlist_loading: false,
                is_parentlist_loaded: false,
                is_toggle_elements_options_loading: false,
                is_toggle_elements_options_loaded: false,
                has_post_error: false,
                post_error_msg: '',
                is_data_loading: false,
                is_data_loaded: false,
                is_post_success: false,
                external_files: [
                    ("/js/adminlte/config_editor.js"),
                    ("/js/adminlte/bootstrap-iconpicker/css/bootstrap-iconpicker.min.css"),
                    ("/js/adminlte/bootstrap-iconpicker/js/iconset/fontawesome5-3-1.min.js"),
                    ("/js/adminlte/bootstrap-iconpicker/js/bootstrap-iconpicker.min.js"),
                    ("/js/adminlte/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css"),
                    ("/js/adminlte/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"),
                ],
                editor: null
            },
            body_loader_active: false,
        };
    },
    methods: {
        updateForm: function() {
            var current_data = $("#item_data").data("current_data");
            this.AdminLTEConfigForm = current_data;

            this.AdminLTEConfigForm.enabled = current_data.enabled;
            document.getElementById("enabled").checked = current_data.enabled;

            this.AdminLTEConfigForm.required = current_data.required;
            document.getElementById("required").checked = current_data.required;

            this.AdminLTEConfigForm.multiple = current_data.multiple;
            document.getElementById("multiple").checked = current_data.multiple;

            /* if ("toggle" == current_data.type) {
                this.AdminLTEConfigForm.toggle_elements = current_data.toggle_elements.split(",");
            } */

            var type = current_data.type;
            var val = current_data.default_value;

            if (
                ("checkbox" == type)
                || ("switch" == type)
                || ("toggle" == type)
                ) {
                document.getElementById("default_value_checkbox").value = val;
            } else if (
                ("dropdown" == type)
                || ("password" == type)
                || ("radio" == type)
                || ("shorttext" == type)
                ) {
                document.getElementById("default_value_text").value = val;
            } else if (("integer" == type) || ("number" == type)){
                document.getElementById("default_value_number").value = val;
            } else if ("datepicker" == type){
                document.getElementById("default_value_datepicker").value = val;
            } else if ("datetimepicker" == type){
                document.getElementById("default_value_datetimepicker").value = val;
            } else if ("timepicker" == type){
                document.getElementById("default_value_timepicker").value = val;
            } else if ("html_editor" == type) {
                $(document.getElementById("default_value_html_editor")).summernote("code", val);
            } else if ("textarea" == type) {
                $(document.getElementById("default_value_textarea")).val(val);
            } else if ("colorpicker" == type) {
                var colorPicker = document.getElementById("default_value_colorpicker");
                $(colorPicker).val(val);
                $(colorPicker).trigger('change');
            } else if ("iconpicker" == type) {
                if ("" == val || undefined === val) {
                    $(document.getElementById("default_value_iconpicker_picker")).iconpicker('setIcon', 'empty');
                } else{
                    $(document.getElementById("default_value_iconpicker_picker")).iconpicker('setIcon', val);
                }
            }
            
        },
        processLoadQueue: function () {
            if (this.page.has_server_error) {
                this.$Progress.finish();
                this.page.is_ready = true;
                return;
            }

            if (!this.page.is_authorized) {
                this.$Progress.finish();
                this.page.is_ready = true;
                return;
            }

            if (!this.page.is_variables_loaded 
                && !this.page.is_data_loaded 
                && !this.page.is_listbykey_loaded 
                && !this.page.is_parentlist_loaded
                && !this.page.is_toggle_elements_options_loaded) {
                this.$Progress.start();
            }
            
            if (!this.page.is_variables_loaded) {
                this.loadPageVariables();
            } else {
                if (!this.page.is_listbykey_loaded) {
                    this.load_listbykey();
                }

                if (!this.page.is_parentlist_loaded) {
                    this.load_parentlist();
                }

                if (!this.page.is_toggle_elements_options_loaded) {
                    this.load_toggle_elements_options();
                }

                if (this.page.is_data_loaded) {
                    this.$Progress.finish();
                    this.page.is_ready = true;

                    this.$nextTick(function () {
                        this.renderSelectElements();
                        this.updateMenuEditor();
                    });
                } else {
                    this.loadData();
                }
            }
        },
        loadPageVariables: function () {
            var self = this;

            if (self.page.is_variables_loading) {
                return;
            }

            self.page.is_variables_loading = true;

            axios.get(AdminLTEHelper.getAPIURL("adminlte/get_page_variables/" + self.pagename))
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
                    AdminLTEHelper.initializePermissions(self.page.variables, false);
                    let authorize = AdminLTEHelper.isUserAuthorized(self.page.variables, self.pagename);
                    self.page.is_authorized = authorize.status;
                    self.page.unauthorized_type = authorize.type;
                    self.processLoadQueue();
                });
        },
        load_parentlist: function () {
            if (this.page.is_parentlist_loading) {
                return;
            }

            this.page.is_parentlist_loading = true;
            
            axios.get(AdminLTEHelper.getAPIURL("adminlteconfig/get_parentlist/" + this.id))
                .then(({ data }) => {
                    this.page.is_parentlist_loaded = true;
                    this.page.is_parentlist_loading = false;
                    this.parentlist = data.list;
                    this.processLoadQueue();
                }).catch(({ data }) => {
                    this.page.is_parentlist_loaded = true;
                    this.page.is_parentlist_loading = false;
                    this.$Progress.fail();
                    this.processLoadQueue();
                });
        },
        load_toggle_elements_options: function () {
            if (this.page.is_toggle_elements_options_loading) {
                return;
            }

            this.page.is_toggle_elements_options_loading = true;
            
            axios.get(AdminLTEHelper.getAPIURL("adminlteconfig/get_toggle_elements_options/" + this.id))
                .then(({ data }) => {
                    this.page.is_toggle_elements_options_loaded = true;
                    this.page.is_toggle_elements_options_loading = false;
                    this.toggle_elements_options = data.list;
                    this.processLoadQueue();
                }).catch(({ data }) => {
                    this.page.is_toggle_elements_options_loaded = true;
                    this.page.is_toggle_elements_options_loading = false;
                    this.$Progress.fail();
                    this.processLoadQueue();
                });
        },
        loadData: function () {
            if (this.page.is_data_loading) {
                return;
            }

            this.page.is_data_loading = true;

            axios.get(AdminLTEHelper.getAPIURL("adminlteconfig/get_json"))
                .then(({ data }) => {
                    this.page.is_data_loaded = true;
                    this.page.is_data_loading = false;
                    this.form.config_json = data;
                    this.processLoadQueue();
                }).catch(({ data }) => {
                    this.page.is_data_loaded = true;
                    this.page.is_data_loading = false;
                    this.$Progress.fail();
                    this.page.has_server_error = true;
                    this.processLoadQueue();
                });
        },
        load_listbykey: function () {
            if (this.page.is_listbykey_loading) {
                return;
            }

            this.page.is_listbykey_loading = true;

            axios.get(AdminLTEHelper.getAPIURL("adminlteconfig/get_recordlist"))
                .then(({ data }) => {
                    this.page.is_listbykey_loaded = true;
                    this.page.is_listbykey_loading = false;
                    this.listByKey = data.list;
                    this.processLoadQueue();
                }).catch(({ data }) => {
                    this.page.is_listbykey_loaded = true;
                    this.page.is_listbykey_loading = false;
                    this.$Progress.fail();
                    this.page.has_server_error = true;
                    this.processLoadQueue();
                });
        },
        submitForm: function () {
            var self = this;
            self.$Progress.start();
            var str = self.page.editor.getString();
            self.form.config_json = str;

            self.form.post(AdminLTEHelper.getAPIURL("adminlteconfig/post_json"))
                .then(({ data }) => {
                    self.$Progress.finish();
                    self.page.has_post_error = data.has_error;
                    self.page.post_error_msg = data.error_msg;
                    self.page.has_server_error = false;
                }).catch(({ data }) => {
                    self.$Progress.fail();
                    let errors = (self.form.errors.errors);
                    if (undefined !== errors.error) {
                        self.page.has_server_error = true;
                    } else {
                        self.page.has_post_error = true;
                        self.page.post_error_msg = self.$t("Your changes could not be saved. Please check your details and try again.");
                    }
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
                                /* onClose: () => {
                                    window.location.reload()
                                } */
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
        updateMenuEditor: function () {
            var self = this;
            if (!self.page.is_ready) {
                return;
            }

            $("#buttonNewMenuItem").off("click").on("click", function () {
                self.AdminLTEConfigForm.enabled = false;
                self.AdminLTEConfigForm.required = false;
                self.AdminLTEConfigForm.multiple = false;
                self.AdminLTEConfigForm.__parent = "";
                self.AdminLTEConfigForm.type = "";
                self.AdminLTEConfigForm.title = "";
                self.AdminLTEConfigForm.option_titles = "";
                self.AdminLTEConfigForm.option_values = "";
                self.AdminLTEConfigForm.toggle_elements = "";
                self.AdminLTEConfigForm.url = "";
                self.AdminLTEConfigForm.min = 0;
                self.AdminLTEConfigForm.max = 0;
                self.AdminLTEConfigForm.step = 0;
                self.AdminLTEConfigForm.file_types = "";
                self.AdminLTEConfigForm.content = "";
                self.AdminLTEConfigForm.default_value = "";
                self.AdminLTEConfigForm.value = "";
                
                $("#buttonUpdateMenuItem").hide();
                $("#buttonAddMenuItem").show();
    
                $("#modalMenuItem").modal();
            });

            /* var rawMenuJSON = decodeURIComponent(this.form.config_json);
            var menuJSON = JSON.parse(rawMenuJSON); */
            
            var sortableListOptions = {
                placeholderCss: {"background-color": "#cccccc"}
            };

            self.page.editor = new MenuEditor(
                    "ulConfigEditor",
                    {
                        listOptions: sortableListOptions
                    }
            );
            self.page.editor.setForm($("#formMenuItem"));
            self.page.editor.setUpdateButton($("#buttonUpdateMenuItem"));
            self.page.editor.setData(self.form.config_json);

            $("#buttonUpdateMenuItem").off("click").on("click", function(){
                if (self.isValid()) {
                    self.page.editor.update();
                    $("#modalMenuItem").modal('hide');
                }
            });

            $("#buttonAddMenuItem").off("click").on("click", function(){
                document.getElementById("currentKey").value = "";
                document.getElementById("currentParent").value = "";

                if (self.isValid()) {
                    self.page.editor.add();
                    $("#modalMenuItem").modal('hide');
                }
            });

            $( "#ulConfigEditor" ).sortable();
            $( "#ulConfigEditor" ).disableSelection();

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
                var elAppend = document.getElementById("default_value_colorpicker_append");
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
                document.getElementById("default_value_iconpicker").value = e.icon;
            });

            setTimeout(function() {
                self.body_loader_active = false;
            }, 500);
        },
        isValid: function() {
            var self = this;
            var currentKey = document.getElementById("currentKey").value;
            var title = self.AdminLTEConfigForm.title;
            var key = self.convertTitleToConfigName(title);
            var __parent = self.AdminLTEConfigForm.__parent;

            if ("" != __parent) {
                key = __parent + "." + key;
            }

            if ((key != currentKey) && self.listByKey.hasOwnProperty(key)) {
                Vue.swal.fire({
                    position: 'top-end',
                    title: self.$t("This element is in use. Please try different title."),
                    icon: 'error',
                    showConfirmButton: false,
                    timer: 10000,
                    timerProgressBar: true
                });
                return false;
            }

            if ("" == self.AdminLTEConfigForm.type) {
                Vue.swal.fire({
                    position: 'top-end',
                    title: self.$t("Please select a type."),
                    icon: 'error',
                    showConfirmButton: false,
                    timer: 10000,
                    timerProgressBar: true
                });
                return false;
            }

            this.AdminLTEConfigForm.__key = key;
            return true;
        },
        refreshListByKey: function() {
            var exceptionKey = this.exceptionKey;

            this.listByKey = {};
            var listByKey = {};
            var elements = $(".list-group-item");

            for (let index = 0; index < elements.length; index++) {
                const data = $(elements[index]).data();

                if (exceptionKey != data.__key) {
                    const element = {};
                    element.enabled = data.enabled;
                    element.type = data.type;
                    element.title = data.title;
                    listByKey[data.__key] = element;
                }
            }

            this.listByKey = listByKey;

            this.renderSelectElements();
        },
        renderSelectElements: function() {
            if (0 == this.listByKey.length) {
                return;
            }

            // parent selection
            var parentList = [];
            var option = {};

            for (var __key in this.listByKey) {
                if ("group" == this.listByKey[__key].type) {
                    option = {};
                    option["id"] = __key;
                    option["text"] = this.getOptionTitle(__key);
                    parentList.push(option);
                }
            }

            this.parentlist = parentList;

            // toggle selection
            var toggleList = [];

            for (var __key in this.listByKey) {
                const option = {};
                option["id"] = __key;
                option["text"] = this.getOptionTitle(__key);
                toggleList.push(option);
            }

            this.toggle_elements_options = toggleList;

            if ($("#toggle_elements").find('option').get(0)) {
                $("#toggle_elements").find('option').get(0).remove();
            }
        },
        getOptionTitle: function(key) {
            var self = this;
            var option_title = '';
            var parts = key.split('.');
            var parent_key = '';

            parts.forEach(part => {
                if ('' != parent_key) {
                    parent_key += '.';
                }

                parent_key += part;

                let title = self.getElementTitle(parent_key);

                if ('' != option_title) {
                    option_title += ' / ';
                }

                option_title += title;
            });

            return option_title;
        },
        getElementTitle: function(parent_key) {
            var parent = this.listByKey[parent_key];
            return parent["title"];
        },
        convertTitleToConfigName: function(strName) {
            var strReturnValue = strName;

            strReturnValue = strReturnValue.replace(/Ç/g, 'c');
            strReturnValue = strReturnValue.replace(/ç/g, 'c');
            strReturnValue = strReturnValue.replace(/Ý/g, 'i');
            strReturnValue = strReturnValue.replace(/ý/g, 'i');
            strReturnValue = strReturnValue.replace(/I/g, 'i');
            strReturnValue = strReturnValue.replace(/İ/g, 'i');
            strReturnValue = strReturnValue.replace(/ı/g, 'i');
            strReturnValue = strReturnValue.replace(/Ð/g, 'g');
            strReturnValue = strReturnValue.replace(/ð/g, 'g');
            strReturnValue = strReturnValue.replace(/Ğ/g, 'g');
            strReturnValue = strReturnValue.replace(/ğ/g, 'g');
            strReturnValue = strReturnValue.replace(/Ö/g, 'o');
            strReturnValue = strReturnValue.replace(/ö/g, 'o');
            strReturnValue = strReturnValue.replace(/Þ/g, 's');
            strReturnValue = strReturnValue.replace(/þ/g, 's');
            strReturnValue = strReturnValue.replace(/ş/g, 's');
            strReturnValue = strReturnValue.replace(/Ş/g, 's');
            strReturnValue = strReturnValue.replace(/Ü/g, 'u');
            strReturnValue = strReturnValue.replace(/ü/g, 'u');
            strReturnValue = strReturnValue.replace(/"/g, '');
            strReturnValue = strReturnValue.replace(/\'/g, '');
            strReturnValue = strReturnValue.replace(/\?/g, '');
            strReturnValue = strReturnValue.replace(/:/g, '');
            strReturnValue = strReturnValue.replace(/\//g, '');
            strReturnValue = strReturnValue.replace(/!/g, '');
            strReturnValue = strReturnValue.replace(/,/g, '');
            strReturnValue = strReturnValue.replace(/\(/g, '');
            strReturnValue = strReturnValue.replace(/\)/g, '');
            strReturnValue = strReturnValue.replace(/-/g, '');
            strReturnValue = strReturnValue.replace(/\./g, '');
            strReturnValue = strReturnValue.replace(/\+/g, '');
            strReturnValue = strReturnValue.replace(/\*/g, '');
            strReturnValue = strReturnValue.replace(/#/g, '');
            strReturnValue = strReturnValue.replace(/ /g, '');
            strReturnValue = strReturnValue.replace(/__/g, '');
            
            return strReturnValue.toLowerCase();
        }
    },
    mounted() {
        this.body_loader_active = true;
        this.main_folder = AdminLTEHelper.getMainFolder();
        this.pagename = AdminLTEHelper.getPagename();
        this.page.is_ready = false;
        AdminLTEHelper.loadExternalFiles(
                this.page.external_files,
                this.processLoadQueue);
    }
}
</script>