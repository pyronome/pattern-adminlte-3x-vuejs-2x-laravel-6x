<template>
    <div class="content-wrapper">
        <server-error v-if="page.has_server_error" ></server-error>
        <permission-error v-else-if="!page.is_authorized" :type="page.unauthorized_type"></permission-error>
        <div v-else>
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>{{ $t("AdminLTEConfig Edit") }}</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><router-link :to="'/' + main_folder + '/home'">{{ $t('Home') }}</router-link></li>
                                <li class="breadcrumb-item"><router-link :to="'/' + main_folder + '/adminlteconfig'">{{ $t("AdminLTEConfig List") }}</router-link></li>
                                <li class="breadcrumb-item active">{{ $t("AdminLTEConfig Edit") }}</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </section>
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12">
                            <form id="AdminLTEConfigForm"
                                class=""
                                @submit.prevent="submitForm"
                                @keydown="AdminLTEConfigForm.onKeydown($event)">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="col-lg-12 col-md-12 col-xs-12">
                                            <button v-if="'new' == id"
                                                menu-permission-token="adminlteconfig"
                                                model-permission-token="AdminLTEConfig-create"
                                                :disabled="AdminLTEConfigForm.busy"
                                                type="submit"
                                                class="sbp-item sbp-hide btn btn-success btn-md btn-on-card float-right">
                                                {{ $t('Create') }}
                                            </button>
                                            <button v-else 
                                                menu-permission-token="adminlteconfig"
                                                model-permission-token="AdminLTEConfig-update"
                                                :disabled="AdminLTEConfigForm.busy"
                                                type="submit"
                                                class="sbp-item sbp-hide btn btn-success btn-md btn-on-card float-right">
                                                {{ $t('Save') }}
                                            </button>
                                            <router-link tag="a"
                                                class="btn btn-outline-secondary btn-md btn-on-card float-right"
                                                :to="backbuttonURL" style="margin-right:10px;">
                                                <span>{{ $t('Cancel') }}</span>
                                            </router-link>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <input type="hidden" v-model="AdminLTEConfigForm.id" id="id" name="id">
                                        <div class="row">
                                        <div class="form-group col-lg-12 col-md-12 col-xs-12 ">
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox"
                                                    id="AdminLTEConfigForm_enabled"
                                                    name="AdminLTEConfigForm_enabled"
                                                    class=""
                                                    v-model="AdminLTEConfigForm.enabled" />
                                                <label for="AdminLTEConfigForm_enabled" class="detail-label">
                                                    {{ $t('Enabled') }}  
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group col-lg-12 col-md-12 col-xs-12 ">
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox"
                                                    id="AdminLTEConfigForm_required"
                                                    name="AdminLTEConfigForm_required"
                                                    class=""
                                                    v-model="AdminLTEConfigForm.required" />
                                                <label for="AdminLTEConfigForm_required" class="detail-label">
                                                    {{ $t('Required') }}  
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group col-lg-12 col-md-12 col-xs-12 ">
                                            <label for="AdminLTEConfigForm___order" class="detail-label">{{ $t('Order') }}  </label>
                                            <input type="number"
                                                v-model="AdminLTEConfigForm.__order"
                                                class="form-control" 
                                                id="AdminLTEConfigForm___order"
                                                name="AdminLTEConfigForm___order"   >
                                            
                                        </div>
                                        <div class="form-group col-lg-12 col-md-12 col-xs-12 ">
                                            <label for="AdminLTEConfigForm_type" class="detail-label">{{ $t('Type') }}  </label>
                                            <select2-element class="select2-element"
                                                data-placeholder=""
                                                id="AdminLTEConfigForm_type"
                                                name="AdminLTEConfigForm_type"
                                                :options="typelist"
                                                v-model="AdminLTEConfigForm.type">
                                                <option></option>
                                            </select2-element>
                                            <input type="hidden" :class="{ 'is-invalid': AdminLTEConfigForm.errors.has('type') }">
                                            <has-error :form="AdminLTEConfigForm" field="type"></has-error>
                                        </div>

                                        <div class="form-group col-lg-12 col-md-12 col-xs-12 ">
                                            <label for="AdminLTEConfigForm_parent" class="detail-label">{{ $t('Parent') }}  </label>
                                            <select2-element class="select2-element"
                                                data-placeholder=""
                                                id="AdminLTEConfigForm_parent"
                                                name="AdminLTEConfigForm_parent"
                                                :options="parentlist"
                                                v-model="AdminLTEConfigForm.parent">
                                                <option></option>
                                            </select2-element>
                                            <input type="hidden" :class="{ 'is-invalid': AdminLTEConfigForm.errors.has('type') }">
                                            <has-error :form="AdminLTEConfigForm" field="type"></has-error>
                                        </div>

                                        <div class="form-group col-lg-12 col-md-12 col-xs-12 ">
                                            <label for="AdminLTEConfigForm___key" class="detail-label">{{ $t('Key') }}  </label>
                                            <textarea rows="5"
												v-model="AdminLTEConfigForm.__key"
												id="AdminLTEConfigForm___key"
                                                name="AdminLTEConfigForm___key"
                                                class="form-control"  ></textarea>
                                        </div>
                                        <div class="form-group col-lg-12 col-md-12 col-xs-12 ">
                                            <label for="AdminLTEConfigForm_title" class="detail-label">{{ $t('Title') }}  </label>
                                            <input type="text"
                                                v-model="AdminLTEConfigForm.title"
                                                class="form-control "
                                                id="AdminLTEConfigForm_title"
                                                name="AdminLTEConfigForm_title"
                                                :class="{ 'is-invalid': AdminLTEConfigForm.errors.has('title') }">
                                            <has-error :form="AdminLTEConfigForm" field="title"></has-error>
                                        </div>
                                        <div class="form-group col-lg-12 col-md-12 col-xs-12 ">
                                            <label for="AdminLTEConfigForm_meta_data" class="detail-label">{{ $t('Meta Data') }}  </label>
                                            <textarea rows="5"
												v-model="AdminLTEConfigForm.meta_data"
												id="AdminLTEConfigForm_meta_data"
                                                name="AdminLTEConfigForm_meta_data"
                                                class="form-control"  ></textarea>
                                        </div>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <div class="col-lg-12 col-md-12 col-xs-12">
                                            <button v-if="'new' == id"
                                                menu-permission-token="adminlteconfig"
                                                model-permission-token="AdminLTEConfig-create"
                                                :disabled="AdminLTEConfigForm.busy"
                                                type="submit"
                                                class="sbp-item sbp-hide btn btn-success btn-md btn-on-card float-right">
                                                {{ $t('Create') }}
                                            </button>
                                            <button v-else 
                                                menu-permission-token="adminlteconfig"
                                                model-permission-token="AdminLTEConfig-update"
                                                :disabled="AdminLTEConfigForm.busy"
                                                type="submit"
                                                class="sbp-item sbp-hide btn btn-success btn-md btn-on-card float-right">
                                                {{ $t('Save') }}
                                            </button>
                                            <router-link tag="a"
                                                class="btn btn-outline-secondary btn-md btn-on-card float-right"
                                                :to="backbuttonURL" style="margin-right:10px;">
                                                <span>{{ $t('Cancel') }}</span>
                                            </router-link>
                                        </div>
                                    </div>                                    
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </section>
            <input type="hidden" id="controller" value="adminlteconfig">
            <dropzone upload-url="/api/media/post" style="display:none;"></dropzone>
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
            id: 0,
			AdminLTEConfigForm: new Form({
                'debug_mode': false,
                'id': this.id,
				'enabled': false,
				'required': false,
                '__order': 0,
                'type': '',
                'parent': '',
                '__key': '',
                'title': '',
                'meta_data': ''
            }),
            typelist: [],
            parentlist: [],
            page: {
                is_ready: false,
                has_server_error: false,
                variables: [],
                is_authorized: true,
                unauthorized_type: '',
                is_variables_loading: false,
                is_variables_loaded: false,
                has_post_error: false,
                post_error_msg: '',
                is_ready: false,
                is_data_loading: false,
                is_data_loaded: false,
                is_typelist_loading: false,
                is_typelist_loaded: false,
                is_parentlist_loading: false,
                is_parentlist_loaded: false,
                external_files: [
                    ("/js/adminlte/bootstrap-switch/js/bootstrap-switch.js")
                ],
            },
            body_loader_active: false,
        };
    },
    computed: {
        backbuttonURL() {
            let URL = '/' + this.main_folder + '/' + this.pagename;
            if (this.id > 0) {
                URL = URL + '/detail/' + this.id;
            }
            return URL;
        }
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

            if (!this.page.is_variables_loaded 
                && !this.page.is_typelist_loaded
                && !this.page.is_parentlist_loaded
                && !this.page.is_data_loaded) {
                this.$Progress.start();
            }
            
            if (!this.page.is_variables_loaded) {
                this.loadPageVariables();
            } else {
                if (!this.page.is_typelist_loaded) {
                    this.load_typelist();
                }

                if (!this.page.is_parentlist_loaded) {
                    this.load_parentlist();
                }

                if (this.page.is_data_loaded) {
                    this.$nextTick(function () {
                        this.initializePage();
                    });

                    this.$Progress.finish();
                    this.page.is_ready = true;
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
                   let authorize = {};
                   if ("new" == self.id) {
                       authorize = AdminLTEHelper.isUserAuthorized(self.page.variables, self.pagename, 'AdminLTEConfig', 'create');
                   } else {
                       authorize = AdminLTEHelper.isUserAuthorized(self.page.variables, self.pagename, 'AdminLTEConfig', 'read');
                       if (authorize.status) {
                           authorize = AdminLTEHelper.isUserAuthorized(self.page.variables, self.pagename, 'AdminLTEConfig', 'update');
                       }
                   }

                   self.page.is_authorized = authorize.status;
                   self.page.unauthorized_type = authorize.type;
                   self.processLoadQueue();
                });
        },
        load_typelist: function () {
            if (this.page.is_typelist_loading) {
                return;
            }

            this.page.is_typelist_loading = true;
            
            axios.get(AdminLTEHelper.getAPIURL("adminlteconfig/get_typelist"))
                .then(({ data }) => {
                    this.page.is_typelist_loaded = true;
                    this.page.is_typelist_loading = false;
                    this.typelist = data.list;
                    this.processLoadQueue();
                }).catch(({ data }) => {
                    this.page.is_typelist_loaded = true;
                    this.page.is_typelist_loading = false;
                    this.$Progress.fail();
                    this.processLoadQueue();
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
        loadData: function () {
            if (this.page.is_data_loading) {
                return;
            }

            this.page.is_data_loading = true;

            axios.get(AdminLTEHelper.getAPIURL("adminlteconfig/get/" + this.id))
                .then(({ data }) => {
                    this.page.is_data_loaded = true;
                    this.page.is_data_loading = false;
                    if (this.id > 0) {
                        this.AdminLTEConfigForm.fill(data.object);
                    }
                    this.processLoadQueue();
                }).catch(({ data }) => {
                    this.page.is_data_loaded = true;
                    this.page.is_data_loading = false;
                    this.$Progress.fail();
                    this.page.has_server_error = true;
                    this.processLoadQueue();
                });
        },
		initializePage: function () {
            var self = this;

            AdminLTEHelper.initializeTextEditor();
                    
            var locationInputs = self.$el.querySelectorAll(".vue-location");
            locationInputs.forEach(locationInput => {
                AdminLTEHelper.updateLocationPicker(locationInput);
            });

            var switchInputs = self.$el.querySelectorAll(".vue-switch");
            switchInputs.forEach(switchInput => {
                AdminLTEHelper.updateSwitch(switchInput);
            });

			AdminLTEHelper.initializePageFiles(self.files);

            setTimeout(function() {
                self.body_loader_active = false;
            }, 500);
		},
        submitForm: function () {
            var self = this;
            self.$Progress.start();
            self.AdminLTEConfigForm.post(AdminLTEHelper.getAPIURL("adminlteconfig/post"))
                .then(({ data }) => {
                    self.$Progress.finish();
                    self.id = data.id;
                    self.page.has_post_error = data.has_error;
                    self.page.has_server_error = false;
                }).catch(({ data }) => {
                    self.$Progress.fail();
                    let errors = (self.AdminLTEConfigForm.errors.errors);
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
                                onClose: () => {
                                    self.$router.push('/' + self.main_folder + '/adminlteconfig/detail/' + self.id);
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
        }
    },
    mounted() {
        this.body_loader_active = true;
        this.main_folder = AdminLTEHelper.getMainFolder();
        this.pagename = AdminLTEHelper.getPagename();
        this.id = this.$route.params.id;
        this.page.is_ready = false;
        AdminLTEHelper.loadExternalFiles(this.page.external_files, this.processLoadQueue());
    }
}
</script>