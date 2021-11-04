<template>
    <div class="content-wrapper">
        <server-error v-if="page.has_server_error" ></server-error>
        <permission-error v-else-if="!page.is_authorized" :type="page.unauthorized_type"></permission-error>
        <div v-else>
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>{{ $t("AdminLTEConfig List") }}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="home">{{ $t('Home') }}</a></li>
                            <li class="breadcrumb-item active">{{ $t("AdminLTEConfig List") }}</li>
                        </ol>
                    </div>
                    </div>
                </div>
            </section>
            <section class="content">
                <div class="container-fluid">
                    <div class="card collapsed-card recordlist-card">
                        <div class="card-header">
                            <h3 class="card-title">Config Parameters</h3>
                            <div class="card-tools">
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="recordlist-search-container">
                                <div class="input-group input-group-sm divSearchBar float-right" style="margin-bottom:1rem;">
                                    <input type="text"
                                        id="searchText" name="searchText"
                                        @keyup="search_list" v-model="search_text"
                                        class="form-control float-right inputSearchBar"
                                        v-bind:placeholder="$t('Search')">
                                    <div class="input-group-append labelSearchBar">
                                        <button type="button" class="btn btn-default ">
                                            <img class="imgLoader" src="/img/adminlte/loader.svg" width="14" height="14"/>
                                            <i class="fas fa-search text-primary"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover table-sm">
                                    <thead>
                                        <tr>
                                            <th class="text-center sbp-item" model-permission-token="AdminLTEConfig-delete">
                                                <div class="icheck-primary d-inline">
                                                    <input type="checkbox"
                                                        @click="select_all_row($event.target)"
                                                        id="select_AdminLTEConfig_rows"
                                                        class="select_all_row"
                                                        data-model="AdminLTEConfig">
                                                    <label for="select_AdminLTEConfig_rows"></label>
                                                </div>
                                            </th>
                                            <th>
                                                <button type="button"
                                                    id="button_sort_AdminLTEConfig_id"
                                                    class="button-table-sort"
                                                    @click="sort('id')">
                                                    <span>Id</span>&nbsp;
                                                    <span class="sorting loading">
                                                        <img class="imgLoader" src="/img/adminlte/loader.svg" width="14" height="14"/>
                                                    </span>
                                                    <span class="sorting active default text-muted">
                                                        <i class="fa fa-caret-down"></i>
                                                    </span>
                                                    <span class="sorting desc text-primary">
                                                        <i class="fa fa-caret-down"></i>
                                                    </span>
                                                    <span class="sorting asc text-primary">
                                                        <i class="fa fa-caret-up"></i>
                                                    </span>
                                                </button>
                                            </th>
                                            <th>
                                                <button type="button"
                                                    id="button_sort_AdminLTEConfig___key"
                                                    class="button-table-sort"
                                                    @click="sort('__key')">
                                                    <span>Key</span>&nbsp;
                                                    <span class="sorting loading">
                                                        <img class="imgLoader" src="/img/adminlte/loader.svg" width="14" height="14"/>
                                                    </span>
                                                    <span class="sorting active default text-muted">
                                                        <i class="fa fa-caret-down"></i>
                                                    </span>
                                                    <span class="sorting desc text-primary">
                                                        <i class="fa fa-caret-down"></i>
                                                    </span>
                                                    <span class="sorting asc text-primary">
                                                        <i class="fa fa-caret-up"></i>
                                                    </span>
                                                </button>
                                            </th>
                                            <th>
                                                <button type="button"
                                                    id="button_sort_AdminLTEConfig_title"
                                                    class="button-table-sort"
                                                    @click="sort('title')">
                                                    <span>Title</span>&nbsp;
                                                    <span class="sorting loading">
                                                        <img class="imgLoader" src="/img/adminlte/loader.svg" width="14" height="14"/>
                                                    </span>
                                                    <span class="sorting active default text-muted">
                                                        <i class="fa fa-caret-down"></i>
                                                    </span>
                                                    <span class="sorting desc text-primary">
                                                        <i class="fa fa-caret-down"></i>
                                                    </span>
                                                    <span class="sorting asc text-primary">
                                                        <i class="fa fa-caret-up"></i>
                                                    </span>
                                                </button>
                                            </th>
                                            <th class="text-center th-btn-1">
                                                <router-link id="buttonNewAdminLTEConfig" class="btn btn-primary btn-xs btn-on-table sbp-item"
                                                    menu-permission-token="adminlteconfig"
                                                    model-permission-token="AdminLTEConfig-create"
                                                    :to="'/' + main_folder + '/adminlteconfig/edit/new'">
                                                    <i class="fa fa-plus"></i> <span class="hidden-xxs">{{ $t('Add') }}</span>
                                                </router-link>
                                                
                                                <button type="button"
                                                    id="buttonDeleteAdminLTEConfig"
                                                    @click="deleteSelectedRows($event.target, 'AdminLTEConfig')"
                                                    class="btn btn-danger btn-xs btn-on-table button-model-delete sbp-item"
                                                    model-permission-token="AdminLTEConfig-delete"
                                                    style="display:none;">
                                                    <i class="fa fa-trash"></i> <span class="hidden-xxs">{{ $t('Delete') }}</span> <span class="selected-count"></span>
                                                </button>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbodyAdminLTEConfigRecordList">
                                        <tr v-for="row in list" :key="row.id">
                                            <td class="text-center sbp-item" model-permission-token="AdminLTEConfig-delete">
                                                <div class="icheck-primary d-inline">
                                                    <input type="checkbox"
                                                        @click="select_row($event.target)"
                                                        :id="'select_AdminLTEConfig_row' + row.id"
                                                        class="select_row"
                                                        data-model="AdminLTEConfig"
                                                        :data-row-id="row.id">
                                                    <label :for="'select_AdminLTEConfig_row' + row.id"></label>
                                                </div>
                                            </td>
                                            <td>row.__key</td>
                                            <td>row.title</td>
                                            <td class="text-center">
                                                <router-link class="btn btn-outline-primary btn-xs btn-on-table sbp-item"
                                                    menu-permission-token="adminlteconfig"
                                                    model-permission-token="AdminLTEConfig-read"
                                                    :to="'/' + main_folder + '/adminlteconfig/detail/' + row.id">
                                                    <i class="fa fa-info-circle"></i> <span class="hidden-xxs">{{ $t('Detail') }}</span>
                                                </router-link>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div style="min-height:60px;">
                                <pagination v-if="show_pagination" :data="data" :limit="1" align="right" :show-disabled="false" @pagination-change-page="paginate">
                                    <span slot="prev-nav">&lt;</span>
                                    <span slot="next-nav">&gt;</span>
                                </pagination>
                            </div>
                        </div>            
                    </div>  
                </div>
            </section>
        </div>
        <input type="hidden" id="controller" value="adminlteconfig">
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
            formDelete: new Form({
                'selected_ids': []
            }),
            delete_form: {
                has_error: false,
                error_msg: ''
            },
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
            }
        };
    },
    methods: {
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

            if (!this.page.is_variables_loaded && !this.page.is_configlist_loaded) {
                this.$Progress.start();
            }

            if (!this.page.is_variables_loaded) {
                this.loadPageVariables();
            } else {
                if (this.page.is_configlist_loaded) {
                    this.$Progress.finish();
                    this.page.is_ready = true;
                } else {
                    this.loadData(
                        function() {
                            AdminLTEHelper.initializePermissions(self.page.variables, true);
                        }
                    );
                }
            }
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
        loadData: function (callback) {
            var self = this;

            if (self.page.is_configlist_loading) {
                return;
            }

            var query = AdminLTEHelper.getURLQuery(self);
            axios.get(AdminLTEHelper.getAPIURL("adminlteconfig/get_recordlist/" + query))
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
            var search_input = e.target;
            
            AdminLTEHelper.activateSearchLoader(search_input);

            this.search_text = search_input.value;
            this.current_page = 1;

            this.loadData(function(){
                AdminLTEHelper.deactivateSearchLoader(search_input)
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
        }
    },
    mounted() {
        var self = this;

        self.main_folder = AdminLTEHelper.getMainFolder();
        self.page.is_ready = false;
        self.processLoadQueue();
    }
}
</script>