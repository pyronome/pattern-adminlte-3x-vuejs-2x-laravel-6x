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

        <div class="modal level4 fade" id="modalAddCustomVariable" tabindex="-1" role="dialog">
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
                                        <insert-variable-button 
                                            :variable_options="['global_parameters','user_parameters','url_parameters','request_parameters']" 
                                            target="__cv_default_value">
                                        </insert-variable-button>
                                    </label>
                                    <input type="text" class="form-control " id="__cv_default_value" v-model="variableForm.default_value">
                                </div>
                                <div class="form-group col-lg-12 d-none">
                                    <label for="__cv_value" class="detail-label">
                                        {{ $t('Value') }}
                                        <insert-variable-button 
                                            :variable_options="['query_result_fields','global_parameters','user_parameters','url_parameters','request_parameters']" 
                                            target="__cv_value">
                                        </insert-variable-button>
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
            page: {
                is_ready: false,
                has_server_error: false,
                is_post_success: false,
                has_post_error: false,
                post_error_msg: '',
                is_variables_loading: false,
                is_variables_loaded: false
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
        load_variables: function(callback) {
            var self = this;
            if (self.page.is_variables_loading) {
                return;
            }

            self.page.is_variables_loading = true;
			
			axios.get(AdminLTEHelper.getAPIURL("adminlte/get_custom_variables"))
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

            if (window.__insert_variable_dialog) {
                window.__insert_variable_dialog.load_custom_variable_options();
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

            self.variableForm.default_value = document.getElementById("__cv_default_value").value;
            self.variableForm.value = document.getElementById("__cv_value").value;
            self.variableForm.remember = document.getElementById("__cv_remember").checked;
            self.variableForm.remember_type = '';
            if (self.variableForm.remember) {
                self.variableForm.remember_type = $("input[name='__cv_remember_type']:checked").val();
            }

            self.$Progress.start();
            self.variableForm.post(AdminLTEHelper.getAPIURL("adminlte/post_custom_variable"))
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
            self.formDelete.post(AdminLTEHelper.getAPIURL("adminlte/delete_custom_variable"))
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
        setValue: function(custom_variableId, value) {
            var self = this;

            if (0 != custom_variableId) {
                window.__custom_variables.values[custom_variableId] = value;
                /* var element = self.getItemById(custom_variableId);

                self.variableForm.id = element.id;
                self.variableForm.name = element.name;
                self.variableForm.title = element.title;
                self.variableForm.value = value;
                self.variableForm.default_value = element.default_value;
                self.variableForm.remember = element.remember;
                self.variableForm.remember_type = element.remember_type;
                
                self.$Progress.start();

                self.variableForm.post(AdminLTEHelper.getAPIURL("adminlte/post_custom_variable"))
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
                        }
                    }).finally(function() {
                        if (!self.page.has_server_error) {
                            self.load_variables();
                        }
                    }); */
            }
        }
    },
    mounted() {
        window.__custom_variables = this;
        window.__custom_variables.list = [];
        window.__custom_variables.values = {};
        this.$Progress.start();
        this.page.is_ready = false;
        this.processLoadQueue();
    }
}
</script>