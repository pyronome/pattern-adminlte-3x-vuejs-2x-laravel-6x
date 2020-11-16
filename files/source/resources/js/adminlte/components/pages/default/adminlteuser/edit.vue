<template>
    <div class="content-wrapper">
        <server-error v-if="page.has_server_error" ></server-error>
        <permission-error v-else-if="!page.is_authorized" :type="page.unauthorized_type"></permission-error>
        <div v-else>
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>{{ $t("AdminLTEUser Edit") }}</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><router-link :to="'/' + main_folder + '/home'">{{ $t('Home') }}</router-link></li>
                                <li class="breadcrumb-item"><router-link :to="'/' + main_folder + '/adminlteuser'">{{ $t("AdminLTEUser List") }}</router-link></li>
                                <li class="breadcrumb-item active">{{ $t("AdminLTEUser Edit") }}</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </section>
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12">
                            <form id="AdminLTEUserForm"
                                class=""
                                @submit.prevent="submitForm"
                                @keydown="AdminLTEUserForm.onKeydown($event)">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="col-lg-12 col-md-12 col-xs-12">
                                            <button v-if="'new' == id"
                                                menu-permission-token="adminlteuser"
                                                model-permission-token="AdminLTEUser-create"
                                                :disabled="AdminLTEUserForm.busy"
                                                type="submit"
                                                class="sbp-item sbp-hide btn btn-success btn-md btn-on-card float-right">
                                                {{ $t('Create') }}
                                            </button>
                                            <button v-else 
                                                menu-permission-token="adminlteuser"
                                                model-permission-token="AdminLTEUser-update"
                                                :disabled="AdminLTEUserForm.busy"
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
                                        <input type="hidden" v-model="AdminLTEUserForm.id" id="id" name="id">
                                        <div class="row">
                                            <div class="form-group col-lg-12 col-md-12 col-xs-12 ">
                                                <div class="icheck-primary d-inline">
                                                    <input type="checkbox"
                                                        id="AdminLTEUserForm_enabled"
                                                        name="AdminLTEUserForm_enabled"
                                                        class=""
                                                        v-model="AdminLTEUserForm.enabled"/>
                                                    <label for="AdminLTEUserForm_enabled" class="detail-label">
                                                        {{ $t('Enabled') }}  
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group col-lg-12 col-md-12 col-xs-12 ">
                                                <label for="AdminLTEUserForm_adminlteusergroup_id" class="detail-label">{{  $t('AdminLTEUserGroup') }}  </label>
                                                <select2-element
                                                    data-placeholder=""
                                                    id="AdminLTEUserForm_adminlteusergroup_id"
                                                    name="AdminLTEUserForm_adminlteusergroup_id"
                                                    :options="adminlteusergroup_id_options"
                                                    v-model="AdminLTEUserForm.adminlteusergroup_id"
                                                    class="select2-element">
                                                    <option></option>
                                                </select2-element>
                                            </div>
                                            <div class="form-group col-lg-12 col-md-12 col-xs-12 ">
                                                <label for="AdminLTEUserForm_fullname" class="detail-label">{{ $t('Fullname') }}  </label>
                                                <input type="text"
                                                    v-model="AdminLTEUserForm.fullname"
                                                    class="form-control "
                                                    id="AdminLTEUserForm_fullname"
                                                    name="AdminLTEUserForm_fullname">
                                            </div> 
                                            <div class="form-group col-lg-12 col-md-12 col-xs-12 ">
                                                <label for="AdminLTEUserForm_username" class="detail-label">{{ $t('Username') }} <span class="required">*</span></label>
                                                <input type="text"
                                                    v-model="AdminLTEUserForm.username"
                                                    class="form-control "
                                                    :class="{ 'is-invalid': AdminLTEUserForm.errors.has('username') }"
                                                    id="AdminLTEUserForm_username"
                                                    name="AdminLTEUserForm_username">
                                                <has-error :form="AdminLTEUserForm" field="username"></has-error>
                                            </div>
                                            <div class="form-group col-lg-12 col-md-12 col-xs-12 ">
                                                <label for="AdminLTEUserForm_email" class="detail-label">{{ $t('Email') }} <span class="required">*</span></label>
                                                <input type="email"
                                                    v-model="AdminLTEUserForm.email"
                                                    class="form-control "
                                                    :class="{ 'is-invalid': AdminLTEUserForm.errors.has('email') }"
                                                    id="AdminLTEUserForm_email"
                                                    name="AdminLTEUserForm_email">
                                                <has-error :form="AdminLTEUserForm" field="email"></has-error>
                                            </div>
                                            <div class="form-group col-lg-12 col-md-12 col-xs-12 ">
                                                <label for="AdminLTEUserForm_password" class="detail-label">{{ $t('Password') }}  </label>
                                                <input type="password"
                                                    v-model="AdminLTEUserForm.password"
                                                    class="form-control "
                                                    id="AdminLTEUserForm_password"
                                                    name="AdminLTEUserForm_password">
                                            </div>
                                            <div class="form-group col-lg-12 col-md-12 col-xs-12 ">
                                                <label for="AdminLTEUserForm_profile_img" class="detail-label">{{ $t('Profile Image') }}  </label>
                                                <div class="input-field">
                                                    <input type="hidden"
                                                        class="form-control"
                                                        id="AdminLTEUserForm_profile_img"
                                                        name="AdminLTEUserForm_profile_img"
                                                        v-model="AdminLTEUserForm.profile_img"
                                                        :class="{ 'is-invalid': AdminLTEUserForm.errors.has('profile_img') }"
                                                        data-target-field="AdminLTEUser/profile_img"
                                                        data-media-type="2"
                                                        data-max-file-count="1"/>
                                                    <button type="button"
                                                        id="buttonBrowseprofile_imgFiles"
                                                        name="buttonBrowseprofile_imgFiles"
                                                        class="buttonBrowseFile btn btn-primary show_by_permission_must_update"
                                                        data-target-file-list="ulprofile_imgFileList">
                                                        <i class="ion-ios-folder"></i>&nbsp;{{ $t('Browse...') }}
                                                    </button>
                                                    <has-error :form="AdminLTEUserForm" field="profile_img"></has-error>
                                                    <ul id="ulprofile_imgFileList" class="col s12 collection ulFileList" data-target-input-id="AdminLTEUserForm_profile_img">
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <div class="col-lg-12 col-md-12 col-xs-12">
                                            <button v-if="'new' == id"
                                                menu-permission-token="adminlteuser"
                                                model-permission-token="AdminLTEUser-create"
                                                :disabled="AdminLTEUserForm.busy"
                                                type="submit"
                                                class="sbp-item sbp-hide btn btn-success btn-md btn-on-card float-right">
                                                {{ $t('Create') }}
                                            </button>
                                            <button v-else 
                                                menu-permission-token="adminlteuser"
                                                model-permission-token="AdminLTEUser-update"
                                                :disabled="AdminLTEUserForm.busy"
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
            <dropzone upload-url="/api/media/post" style="display:none;"></dropzone>
        </div>
        <input type="hidden" id="controller" value="adminlteuser">
    </div>
</template>

<script>

export default {
    data() {
        return {
            main_folder: '',
            pagename: '',
            id: 0,
            adminlteusergroup_id_options: [],
            files: [],
            AdminLTEUserForm: new Form({
                'debug_mode': false,
                'id': this.id,
                'enabled': false,
                'adminlteusergroup_id': [],
                'fullname': '',
                'username': '',
                'email': '',
                'password': '',
                'profile_img': ''
            }),
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
                is_files_loading: false,
                is_files_loaded: false,
                is_adminlteusergroup_id_options_loading: false,
                is_adminlteusergroup_id_options_loaded: false
            }
        };
    },
    computed: {
        backbuttonURL() {
            let URL = '/' + this.main_folder + '/adminlteuser';
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

            if (!this.page.is_variables_loaded && !this.page.is_data_loaded && !this.page.is_files_loaded && !this.page.is_adminlteusergroup_id_options_loaded) {
                this.$Progress.start();
            }
            
            if (!this.page.is_variables_loaded) {
                this.loadPageVariables();
            } else {
                if (!this.page.is_adminlteusergroup_id_options_loaded) {
                    this.load_adminlteusergroup_id_options();
                }
                
                if (!this.page.is_files_loaded) {
                    this.load_files();
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
                       authorize = AdminLTEHelper.isUserAuthorized(self.page.variables, self.pagename, 'AdminLTEUser', 'create');
                   } else {
                       authorize = AdminLTEHelper.isUserAuthorized(self.page.variables, self.pagename, 'AdminLTEUser', 'read');
                       if (authorize.status) {
                           authorize = AdminLTEHelper.isUserAuthorized(self.page.variables, self.pagename, 'AdminLTEUser', 'update');
                       }
                   }

                   self.page.is_authorized = authorize.status;
                   self.page.unauthorized_type = authorize.type;
                   self.processLoadQueue();
                });
        },
        load_adminlteusergroup_id_options: function () {
            var self = this;

            if (self.page.is_adminlteusergroup_id_options_loading) {
                return;
            }

            self.page.is_adminlteusergroup_id_options_loading = true;
            
            axios.get(AdminLTEHelper.getAPIURL("adminltemodeloption/get_model_option_list/AdminLTEUserGroup/title"))
                .then(({ data }) => {
                    self.page.is_adminlteusergroup_id_options_loaded = true;
                    self.page.is_adminlteusergroup_id_options_loading = false;
                    self.adminlteusergroup_id_options = data.list;
                    self.processLoadQueue();
                }).catch(({ data }) => {
                    self.page.is_adminlteusergroup_id_options_loaded = true;
                    self.page.is_adminlteusergroup_id_options_loading = false;
                    self.$Progress.fail();
                    self.page.has_server_error = true;
                    self.processLoadQueue();
                });
        },
        load_files: function () {
            if (this.page.is_files_loading) {
                return;
            }

            this.page.is_files_loading = true;

            axios.get(AdminLTEHelper.getAPIURL("adminlteuser/get_files/" + this.id))
                .then(({ data }) => {
                    this.page.is_files_loaded = true;
                    this.page.is_files_loading = false;
                    this.files = data.list;
                    this.processLoadQueue();
                }).catch(({ data }) => {
                    this.page.is_files_loaded = true;
                    this.page.is_files_loading = false;
                    this.$Progress.fail();
                    this.page.has_server_error = true;
                    this.processLoadQueue();
                });
        },
        loadData: function () {
            if (this.page.is_data_loading) {
                return;
            }

            this.page.is_data_loading = true;

            axios.get(AdminLTEHelper.getAPIURL("adminlteuser/get/" + this.id))
                .then(({ data }) => {
                    this.page.is_data_loaded = true;
                    this.page.is_data_loading = false;
                    if (this.id > 0) {
                        this.AdminLTEUserForm.fill(data.object);
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
            AdminLTEHelper.initializeTextEditor();
                    
            var locationInputs = this.$el.querySelectorAll(".vue-location");
            locationInputs.forEach(locationInput => {
                AdminLTEHelper.updateLocationPicker(locationInput);
            });

            var switchInputs = this.$el.querySelectorAll(".vue-switch");
            switchInputs.forEach(switchInput => {
                AdminLTEHelper.updateSwitch(switchInput);
            });

			AdminLTEHelper.initializePageFiles(this.files);
		},
        submitForm: function () {
            var self = this;
            self.$Progress.start();
            self.AdminLTEUserForm.post(AdminLTEHelper.getAPIURL("adminlteuser/post"))
                .then(({ data }) => {
                    self.$Progress.finish();
                    self.id = data.id;
                    self.page.has_post_error = data.has_error;
                    self.page.post_error_msg = data.error_msg;
                    self.page.has_server_error = false;
                }).catch(({ data }) => {
                    self.$Progress.fail();
                    let errors = (self.AdminLTEUserForm.errors.errors);
                    if (undefined !== errors.error) {
                        self.page.has_server_error = true;
                    } else {
                        self.page.has_post_error = true;
                        self.page.post_error_msg = self.$t("Please fill in the required fields.");
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
                                    self.$router.push('/' + self.main_folder + '/adminlteuser/detail/' + self.id);
                                }
                            });
                        } else {
                            Vue.swal.fire({
                                toast: true,
                                position: 'top-end',
                                title: '',
                                text: self.page.post_error_msg,
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
        this.main_folder = AdminLTEHelper.getMainFolder();
        this.pagename = AdminLTEHelper.getPagename();
        this.id = this.$route.params.id;
        this.page.is_ready = false;
        this.processLoadQueue()
    }
}
</script>