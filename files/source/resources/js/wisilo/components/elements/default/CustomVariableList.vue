<template>
    <div>
        <div class="modal level3 fade" id="modalCustomVariableList" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <form>
                        <div class="modal-header">
                            <h4 class="modal-title">{{ $t('Custom Variables') }}</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body" style="padding-top:0px;">
                            <div class="row">
                                <div class="col-6 mt-3 mb-3">
                                    <button type="button" class="btn btn-primary btn-md btn-on-card text-white float-sm-right" @click="addNewCustomVariable">
                                        <i class="fas fa-plus" aria-hidden="true"></i> <span>{{ $t('Add Variable') }}</span>
                                    </button>
                                </div>
                                <div class="col-6 mt-3 mb-3">
                                    <div class="input-group input-group-md">
                                        <input type="search"
                                            id="searchVariable" name="searchVariable"
                                            class="form-control float-right inputSearchBar"
                                            v-bind:placeholderr="$t('Search')">
                                        <div class="input-group-append labelSearchBar">
                                            <button type="button" class="btn btn-default">
                                                <i class="fas fa-search text-primary"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-striped table-bordered table-hover table-sm">
                                        <thead>
                                            <tr>
                                                <th>
                                                    <span>{{ $t('Variable') }}</span>&nbsp;
                                                </th>
                                                <th style="width:70px;">
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody id="tbodyVariableList">
                                            <tr v-for="(item, index) in variableList" :key="index"
                                                :data-search-text="item.title + item.name + item.value">
                                                <td>
                                                   <span class="spanCustomVariableTitle" v-html="item.title + ' (' + item.name + ')'"></span>
                                                </td>
                                                <td>
                                                   <button v-if="(0 == item.__system)"
                                                        type="button"
                                                        class="btn-icon btn-icon-primary"
                                                        @click="showEdit(item.id)"
                                                        style="margin-bottom:0;">
                                                        <span class="btn-label btn-label-right">
                                                            <i class="fas fa-pen"></i>
                                                        </span>                    
                                                    </button> 
                                                    <button v-if="(0 == item.__system)"
                                                        type="button" 
                                                        class="btn-icon btn-icon-danger"
                                                        @click="showDeleteDialog(item.id)">
                                                        <span class="btn-label btn-label-right"><i class="fas fa-times"></i></span>
                                                    </button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="modalfooter justify-content-between">
                            <div class="row">
                                <div class="col-md-12">
                                    <!-- <button type="button"
                                        class="btn btn-success btn-md btn-on-table float-right"
                                        @click="addWidgets">
                                        {{ $t('Add') }}
                                    </button> -->
                                    <button type="button" class="btn btn-outline-secondary float-right" data-dismiss="modal" style="margin-right:10px;">
                                        {{ $t('Close') }}
                                    </button>                                    
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal level5 fade" id="modalAddCustomVariable" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form>
                        <div class="modal-header">
                            <h4 class="modal-title">{{ $t('Custom Variable') }}</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" id="__cv_id" v-model="variableForm.id">
                            <input type="hidden" id="__cv___system" v-model="variableForm.__system">
                            <div class="row">
                                <div class="form-group col-lg-12">
                                    <label for="__cv_name" class="detail-label">{{ $t('Name') }}</label>
                                    <input type="text" class="form-control " id="__cv_name" v-model="variableForm.name">
                                    <span class="text-muted">
                                        {{ $t('This value must contain only English letters, numbers and "_".') }}
                                    </span>
                                </div>
                                <div class="form-group col-lg-12">
                                    <label for="__cv_title" class="detail-label">{{ $t('Title') }}</label>
                                    <input type="text" class="form-control " id="__cv_title" v-model="variableForm.title">
                                </div>
                                <div class="form-group col-lg-12">
                                    <label for="__cv_default_value" class="detail-label">
                                        {{ $t('Default Value') }}
                                        <button type="button"
                                            class="btn-icon btn-icon-primary"
                                            @click="showInsertVariableDialog"
                                            style="float:right;"
                                            title="Insert Variable">
                                            <span class="btn-label btn-label-right">
                                                <i class="fas fa-database"></i>
                                            </span>                    
                                        </button>
                                    </label>
                                    <input type="text" class="form-control " id="__cv_default_value" v-model="variableForm.default_value">
                                </div>
                                <div class="form-group col-lg-12 d-none">
                                    <label for="__cv_value" class="detail-label">
                                        {{ $t('Value') }}
                                    </label>
                                    <input type="text" class="form-control " id="__cv_value" v-model="variableForm.value">
                                </div>
                                <div class="form-group col-lg-12">
                                    <div class="">
                                        <div class="icheck-primary d-inline">
                                            <input type="checkbox"
                                                id="__cv_remember"
                                                name="__cv_remember"
                                                class="item-menu"
                                                v-model="remember_value">
                                            <label for="__cv_remember" class="">
                                                {{ $t('Remember Value') }}  
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row" v-show="remember_value">
                                <div class="form-group col-6 clearfix">
                                    <div class="icheck-primary d-inline">
                                        <input type="radio" 
                                            id="__cv_remember_type1" 
                                            name="__cv_remember_type" 
                                            value="session"
                                            checked>
                                        <label class="detail-label" for="__cv_remember_type1">Store in the session</label>
                                    </div>
                                </div>
                                <div class="form-group col-6 clearfix">
                                    <div class="icheck-primary d-inline">
                                        <input type="radio" 
                                            id="__cv_remember_type2" 
                                            name="__cv_remember_type"
                                            value="database">
                                        <label class="detail-label" for="__cv_remember_type2">Store in the database</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modalfooter justify-content-between">
                            <div class="row">
                                <div class="col-md-12">
                                    <button type="button"
                                        class="btn btn-success btn-md btn-on-table float-right"
                                        @click="saveVariable">
                                        {{ $t('Save') }}
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary float-right" data-dismiss="modal" style="margin-right:10px;">
                                        {{ $t('Cancel') }}
                                    </button>                                    
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal level6 fade" id="divInsertVariableDialog" tabindex="-1" role="dialog">
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
                                    <label for="__cv__variable_type" class="detail-label">{{ $t('Variable Type') }}</label>
                                    <select id="__cv__variable_type" v-model="insertForm.variable_type" class="form-control">
                                        <option value="">Please Select</option>
                                        <option value="global_parameters">{{ $t('Global Parameters') }}</option>
                                        <option value="user_parameters">{{ $t('User Parameters') }}</option>
                                        <option value="url_parameters">{{ $t('URL Parameters') }}</option>
                                        <option value="request_parameters">{{ $t('Request Parameters') }}</option>
                                    </select>
                                </div>
                            </div>

                            <div v-show="('global_parameters' == insertForm.variable_type)" class="row">
                                <div class="form-group col-lg-12">
                                    <label for="__cv__global_parameter" class="detail-label">{{ $t('Config Parameter') }}</label>
                                    <select2-element class="select2-element"
                                        id="__cv__global_parameter"
                                        name="__cv__global_parameter"
                                        :options="global_parameter_options">
                                    </select2-element>
                                </div>
                            </div>

                            <div v-show="('user_parameters' == insertForm.variable_type)" class="row">
                                <div class="form-group col-lg-12">
                                    <label for="__cv__user_parameter" class="detail-label">{{ $t('Config Parameter') }}</label>
                                    <select2-element class="select2-element"
                                        id="__cv__user_parameter"
                                        name="__cv__user_parameter"
                                        :options="user_parameter_options">
                                    </select2-element>
                                </div>
                            </div>

                            <div v-show="('url_parameters' == insertForm.variable_type)" class="row">
                                <div class="form-group col-lg-12">
                                    <label for="__cv__url_parameter" class="detail-label">{{ $t('Parameter Index') }}</label>
                                    <select id="__cv__url_parameter"  class="form-control">
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
                                    <label for="__cv__request_parameter" class="detail-label">{{ $t('Parameter Name') }}</label>
                                    <input type="text" class="form-control " id="__cv__request_parameter">
                                </div>
                            </div>
                        </div>
                        <div class="modalfooter justify-content-between">
                            <div class="row">
                                <div class="col-md-12">
                                    <button type="button"
                                        id="buttonInsertVariableToTarget"
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

        <div class="modal level4 fade" id="modalCustomVariableDelete" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{ $t('Delete') }}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <p>{{ $t('Selected variable will be deleted. Do you confirm?') }}</p>
                        </div>
                    </div>
                    <div class="modalfooter justify-content-between show_by_permission_must_update">
                        <div class="row">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-outline-secondary float-left" data-dismiss="modal">{{ $t('Cancel') }}</button>
                                <button type="button"
                                    id="buttonDeleteCustomVariable"
                                    @click="submitDeleteForm()"
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
</template>

<script>
export default {
    data() {
        return {
            remember_value: false,
            variableList: [],
            variableForm: new Form({
                'debug_mode': false,
                'id': 0,
                '__system': 0,
                'name': '',
                'title': '',
                'default_value': '',
                'remember': '',
                'remember_type': '',
                'value': ''
            }),
            formDelete: new Form({
                'id': 0
            }),
            delete_form: {
                has_error: false,
                error_msg: ''
            },
            lastAddedVariableId: 0,
            lastAddedVariableName: "",
            page: {
                is_ready: false,
                has_server_error: false,
                is_post_success: false,
                has_post_error: false,
                post_error_msg: '',
                is_variables_loading: false,
                is_variables_loaded: false
            },
            insertForm: new Form({
                variable_type: "",
                variable_text: "",
            }),
            global_parameter_options: [],
            user_parameter_options: []
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

            if (!self.page.is_global_parameter_options_loaded) {
                self.load_global_parameter_options();
            }

            if (!self.page.is_user_parameter_options_loaded) {
                self.load_user_parameter_options();
            }

            if (!self.page.is_variables_loaded) {
                self.load_variables();
            } else {
                self.initializePage();
            }
        },
        initializePage: function() {
            var self = this;

            $("#searchVariable").off("keyup").on("keyup", function () {
                self.doSearchVariable(this);
            });

            
        },
        load_variables: function() {
            var self = this;
            if (self.page.is_variables_loading) {
                return;
            }

            self.page.is_variables_loading = true;
			
			axios.get(WisiloHelper.getAPIURL("wisilo/get_custom_variables"))
                .then(({ data }) => {
                    self.page.is_variables_loaded = true;
                    self.page.is_variables_loading = false;
                    self.variableList = data.list;
                    window.__custom_variables.list = data.list;
                    self.refreshRelatedWithCustomVariableList();
                    self.processLoadQueue();
                }).catch(({ data }) => {
                    self.page.is_variables_loaded = true;
                    self.page.is_variables_loading = false;
                    self.$Progress.fail();
                    self.page.has_server_error = true;
                    self.processLoadQueue();
                });
        },
        refreshRelatedWithCustomVariableList: function() {
            this.renderCustomVariableValues();

            if (window.__insert_custom_variable_dialog) {
                window.__insert_custom_variable_dialog.load_custom_variable_options("CustomVariables/" + this.lastAddedVariableName);
            }

            if (window.__ds_simple__fields) {
                window.__ds_simple__fields.load_customvariables(this.lastAddedVariableId);
            }

            if (window.__ds_query__fields) {
                window.__ds_query__fields.load_customvariables(this.lastAddedVariableId);
            }

            if (window.__ds_simple__condition) {
                window.__ds_simple__condition.load_custom_variable_options();
            }

            if (window.__variable_mapping_edit__) {
                window.__variable_mapping_edit__.load_custom_variable_options(this.lastAddedVariableId);
            }

            if (window.__condition_dialog) {
                window.__condition_dialog.load_custom_variable_options();
            }
        },
        renderCustomVariableValues: function() {
            window.__custom_variables.values = {};
            var values = {};

            var length = window.__custom_variables.list.length;

            for (let index = 0; index < length; index++) {
                const element = window.__custom_variables.list[index];
                values[element.id] = element.value;
            }

            window.__custom_variables.values = values;
        },
        doSearchVariable: function(sender) {
            if (!sender) {
                return;
            }

            var searchText = sender.value;

            $("#tbodyVariableList > tr").addClass("d-none");

            var arrTR = $("#tbodyVariableList > tr");
            var countTR = arrTR.length;
            var searchedText = "";

            for (var i = 0; i < countTR; i++) {
                searchedText = arrTR[i].getAttribute("data-search-text");

                if (searchedText.search(new RegExp(searchText, "i")) != -1) {
                    $(arrTR[i]).removeClass("d-none");
                }
            }
        },
        addNewCustomVariable: function() {
            this.showEdit(0);
        },
        showEdit: function(id) {
            var self = this;
            self.variableForm.id = 0;
            self.variableForm.__system = 0;
            self.variableForm.name = "";
            self.variableForm.title = "";
            self.variableForm.value = "";
            self.variableForm.default_value = "";
            self.variableForm.remember = 0;
            self.variableForm.remember_type = "";

            if (0 != id) {
                var element = self.getItemById(id);
                self.variableForm.id = element.id;
                self.variableForm.name = element.name;
                self.variableForm.title = element.title;
                self.variableForm.value = element.value;
                self.variableForm.default_value = element.default_value;
                self.variableForm.remember = element.remember;
                self.variableForm.remember_type = element.remember_type;

                var selectorText = 'input[name="__cv_remember_type"][value="' + element.remember_type + '"]';
                $(selectorText).prop('checked', true);
            }

            self.remember_value = (1 == self.variableForm.remember);

            $("#modalAddCustomVariable").modal();
        },
        getItemById: function(id) {
            var elements = this.variableList;
            var item = {};

            for (let index = 0; index < elements.length; index++) {
                const element = elements[index];
                if (id == element.id) {
                    item = element;
                    return item;
                }
            }

            return item;
        },
        isValid: function() {
            var self = this;
            var __cv_name = document.getElementById("__cv_name").value.toLowerCase();

            if ("" == __cv_name) {
                Vue.swal.fire({
                    position: 'top-end',
                    title: self.$t("Variable name is required."),
                    icon: 'error',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
                return false;
            }

            if (!self.isKeyValid(__cv_name)) {
                Vue.swal.fire({
                    position: 'top-end',
                    title: self.$t("Variable name must contain only English letters, numbers and \"_\"."),
                    icon: 'error',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
                return false;
            }

            document.getElementById("__cv_name").value = __cv_name;
            return true;
        },
        isKeyValid: function(__key) {
            var valid = true;

            const alphabet = [
                "a","b","c","d","e","f",
                "g","h","i","j","k","l",
                "m","n","o","p","q","r",
                "s","t","u","v","w","x","y","z",
                "0","1","2","3","4","5","6","7","8","9", "_"];

            for (let i = 0; i < __key.length; i++) {
                let char = __key.charAt(i);
                
                if (!alphabet.includes(char)) {
                    valid = false;
                    break;
                }                
            }
            
            return valid;
        },
        saveVariable: function () {
            var self = this;

            if (!self.isValid()) {
                return false;
            }

            self.lastAddedVariableName = document.getElementById("__cv_name").value;

            self.variableForm.default_value = document.getElementById("__cv_default_value").value;
            self.variableForm.value = document.getElementById("__cv_value").value;
            self.variableForm.remember = document.getElementById("__cv_remember").checked;
            self.variableForm.remember_type = '';
            if (self.variableForm.remember) {
                self.variableForm.remember_type = $("input[name='__cv_remember_type']:checked").val();
            }

            self.$Progress.start();
            self.variableForm.post(WisiloHelper.getAPIURL("wisilo/post_custom_variable"))
                .then(({ data }) => {
                    self.$Progress.finish();
                    self.page.has_post_error = data.has_error;
                    self.page.post_error_msg = data.error_msg;
                    self.lastAddedVariableId = data.id;
                    self.page.has_server_error = false;
                }).catch(({ data }) => {
                    self.$Progress.fail();
                    let errors = (self.variableForm.errors.errors);
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
                                timer: 3000,
                                timerProgressBar: true,
                                onClose: () => {
                                    self.load_variables();
                                    $("#modalAddCustomVariable").modal("hide");
                                }
                            });
                        } else {
                            Vue.swal.fire({
                                position: 'top-end',
                                title: self.page.post_error_msg,
                                icon: 'error',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true
                            });
                        }
                    }
                });
        },
        showDeleteDialog: function(variableId) {
            this.formDelete.id = variableId;
            $("#modalCustomVariableDelete").modal();
        },
        submitDeleteForm: function () {
            var self = this;
            self.$Progress.start();
            self.formDelete.post(WisiloHelper.getAPIURL("wisilo/delete_custom_variable"))
                .then(({ data }) => {
                    self.$Progress.finish();
                    self.delete_form.has_error = data.has_error;
                    self.delete_form.error_msg = data.error_msg;
                }).catch(({ data }) => {
                    self.$Progress.fail();
                }).finally(function(){
                    if (!self.delete_form.has_error) {
                        Vue.swal.fire({
                            position: 'top-end',
                            title: self.$t("Selected variable have been deleted."),
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            onClose: () => {
                                $("#modalCustomVariableDelete").modal("hide");
                            }
                        });
                    } else {
                        Vue.swal.fire({
                            position: 'top-end',
                            title: self.delete_form.error_msg,
                            icon: 'error',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true
                        });
                    }
                });
        },
        getLocalVariableMatch: function(instance_id, local_variableId) {
            var local_variables = [];

            if (undefined !== window.mainLayoutInstance.pageWidgets[instance_id]) {
                if (undefined !== window.mainLayoutInstance.pageWidgets[instance_id].data) {
                    if (undefined !== window.mainLayoutInstance.pageWidgets[instance_id].data.variable_mapping) {
                        local_variables = window.mainLayoutInstance.pageWidgets[instance_id].data.variable_mapping;
                    }
                }
            }

            var custom_variableId = 0;

            local_variables.forEach(element => {
                if (local_variableId == element.local_variable) {
                    custom_variableId = element.custom_variable;
                }
            });

            return parseInt(custom_variableId);
        },
        setValue: function(custom_variableId, value, silent = false) {
            var self = this;

            if (0 != custom_variableId) {
                window.__custom_variables.values[custom_variableId] = value;

                if (!silent) {
                    self.refreshDependantWidgets(custom_variableId);
                }
            }
        },
        registerWidgetCustomVariableDependancy: function(dependant_customvariables, instance_id) {
            var dependant_widgets = window.__custom_variables.dependant_widgets;
            var length = dependant_customvariables.length;

            for (let index = 0; index < length; index++) {
                let custom_variableId = dependant_customvariables[index];
                
                if (undefined !== dependant_widgets[custom_variableId]) {
                    dependant_widgets[custom_variableId] = dependant_widgets[custom_variableId] + "," + instance_id;
                } else {
                    dependant_widgets[custom_variableId] = instance_id;
                }
            }

            window.__custom_variables.dependant_widgets = dependant_widgets;
        },
        refreshDependantWidgets: function(custom_variableId) {
            if (window.__custom_variables.dependant_widgets[custom_variableId]) {
                var dependant_widgets = window.__custom_variables.dependant_widgets[custom_variableId].split(",");
                
                for (let index = 0; index < dependant_widgets.length; index++) {
                    let instance_id = dependant_widgets[index];
                    
                    if (undefined !== window.mainLayoutInstance.pageWidgets[instance_id]) {
                        let widget = window.mainLayoutInstance.pageWidgets[instance_id];
                        if (undefined !== widget.main) {
                            let widgetInstance = widget.main;
                            if (undefined !== widgetInstance.refresh()) {
                                widgetInstance.refresh()
                            }
                        }
                    }
                }
            }
        },
        setCustomVariableValues: function(instance_id, callback) {
            var self = this;

            if (document.getElementById("container-" + instance_id)) {
                var widgetContainer = document.getElementById("container-" + instance_id);
                if (widgetContainer.hasAttribute("data-container-guid")) {
                    var container_guid = widgetContainer.getAttribute("data-container-guid");

                    if ("" != container_guid) {
                        var external_data = window.mainLayoutInstance.widgetContainers[container_guid].external_data;
                        if (Object.keys(external_data).length > 0) {
                            window.__custom_variables.setCustomVariableValues(external_data);
                        }

                        for (const key in external_data) {
                            if (key.includes("customvariable")) {
                                if (external_data.hasOwnProperty.call(external_data, key)) {
                                    const value = external_data[key];
                                    const custom_variableId = key.replace("customvariable", "");
                                    self.setValue(custom_variableId, value, true);
                                }   
                            }
                        }
                    }
                }
            }

            if (undefined !== callback) {
                callback();
            }
        },
        replaceCustomVariables: function(expression) {
            var self = this;
            var returnValue;
            var tokens = String(expression).split("{{");
            var subTokens = null;
            var content = "";
            var tokenCount = 0;
            var customVariableName = "";
            var customVariableId = 0;
            var text = "";
            var position = 0;

            if (tokens.length <= 1) {
                return expression;
            }

            tokenCount = tokens.length;
            var value = "";

            for (var i = 1; (i < tokenCount); i++) {
                content = tokens[i];
                position = 1;

                while (("}" != content[position - 1])
                        && ("}" != content[position])) {
                    position++;
                }

                subTokens = (String(content).substr(0, (position))).split("/");

                if (subTokens.length > 1) {
                    if ("CustomVariables" == subTokens[0]) {
                        customVariableName = subTokens[1];
                        customVariableId = self.getCustomVariableIdByName(customVariableName);
                        if (0 != customVariableId) {
                            value = window.__custom_variables.values[customVariableId];
                        }
                    }
                }

                /* if (object[column] !== undefined) {
                    value = object[column];
                } else {
                    value = ("{{" + column + "}}");
                } */

                text = String(content).substr(position + 2);
                text = String(text).replace(/(?:\r\n|\r|\n)/g, "");

                content = (value + text);

                tokens[i] = content;
            }

            if (tokens.length > 1) {
                returnValue = tokens.join("");
            }

            return returnValue;           
        },
        getCustomVariableIdByName: function(customVariableName) {
            var customVariableId = 0;
            var length = window.__custom_variables.list.length;

            for (let index = 0; index < length; index++) {
                const element = window.__custom_variables.list[index];
                if (customVariableName == element.name) {
                    customVariableId = element.id;
                    break;
                }
            }

            return customVariableId;
        },
        // Insert Variable
        showInsertVariableDialog: function() {
            $("#divInsertVariableDialog").off("shown.bs.modal").on("shown.bs.modal", function (e) { 
                $(document).off("focusin.modal"); 
            });

            $("#divInsertVariableDialog").modal();
        },
        load_global_parameter_options: function() {
            var self = this;
            if (self.page.is_global_parameter_options_loading) {
                return;
            }

            self.page.is_global_parameter_options_loading = true;
            
            axios.get(WisiloHelper.getAPIURL("wisilo/get_global_parameter_options"))
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
            
            axios.get(WisiloHelper.getAPIURL("wisilo/get_user_parameter_options"))
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
                    timer: 2000,
                    timerProgressBar: true,
                    onClose: () => {}
                });

                return;
            }
            
            self.insertAtCursor(document.getElementById("__cv_default_value"), data.variable);

            $("#divInsertVariableDialog").modal("hide");
        },
        getGlobalParameterVariable: function () {
            var result = {
                "has_error": false,
                "msg": "",
                "variable": ""
            };

            var configParameter = $("#__cv__global_parameter").val();

            if (("" == configParameter) || (null === configParameter)) {
                result.has_error = true;
                result.msg = this.$t("Please select a global config parameter.");
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

            var configParameter = $("#__cv__user_parameter").val();

            if (("" == configParameter) || (null === configParameter)) {
                result.has_error = true;
                result.msg = this.$t("Please select a user config parameter.");
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

            var urlParameterIndex = $("#__cv__url_parameter").val();

            if (("" == urlParameterIndex) || (null === urlParameterIndex)) {
                result.has_error = true;
                result.msg = this.$t("Please select a URL parameter index.");
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

            var requestParameterName = $("#__cv__request_parameter").val();

            if ("" == requestParameterName) {
                result.has_error = true;
                result.msg = this.$t("Please enter a request parameter name.");
                return result;
            }

            result.variable = "{{RequestParameters/" + requestParameterName + "}}";

            return result;
        }
    },
    mounted() {
        window.__custom_variables = this;
        window.__custom_variables.list = [];
        window.__custom_variables.values = {};
        window.__custom_variables.dependant_widgets = {};

        this.$Progress.start();
        this.page.is_ready = false;
        this.processLoadQueue();
    }
}
</script>