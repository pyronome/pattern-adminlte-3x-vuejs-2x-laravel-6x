<template>
    <div class="content-wrapper">
        <server-error v-if="page.has_server_error" ></server-error>
        <permission-error v-else-if="!page.authorization.status" :authorization="page.authorization"></permission-error>
        <div v-else>
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>{{ $t("New") }}</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><router-link :to="'/' + main_folder + '/home'">{{ $t('Home') }}</router-link></li>
                                <li class="breadcrumb-item"><router-link :to="'/' + main_folder + '/wisilousergroup'">{{ $t("WisiloUserGroup List") }}</router-link></li>
                            </ol>
                        </div>
                    </div>
                </div>
            </section>
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12">
                            <form id="WisiloUserGroupForm"
                                class=""
                                @submit.prevent="submitForm"
                                @keydown="WisiloUserGroupForm.onKeydown($event)">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="col-lg-12 col-md-12 col-xs-12">
                                            <button v-if="'new' == id"
                                                menu-permission-token="wisilousergroup"
                                                model-permission-token="WisiloUserGroup-create"
                                                :disabled="WisiloUserGroupForm.busy"
                                                type="submit"
                                                class="sbp-item sbp-hide btn btn-success btn-md btn-on-card float-right">
                                                {{ $t('Create') }}
                                            </button>
                                            <button v-else 
                                                menu-permission-token="wisilousergroup"
                                                model-permission-token="WisiloUserGroup-update"
                                                :disabled="WisiloUserGroupForm.busy"
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
                                        <input type="hidden" v-model="WisiloUserGroupForm.id" id="id" name="id">
                                        <div class="row">
                                            <div class="form-group col-lg-12 col-md-12 col-xs-12 ">
                                                <div class="icheck-primary d-inline">
                                                    <input type="checkbox"
                                                        id="WisiloUserGroupForm_enabled"
                                                        name="WisiloUserGroupForm_enabled"
                                                        class=""
                                                        v-model="WisiloUserGroupForm.enabled"/>
                                                    <label for="WisiloUserGroupForm_enabled" class="detail-label">
                                                        {{ $t('Enabled') }}  
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group col-lg-12 col-md-12 col-xs-12 ">
                                                <div class="icheck-primary d-inline">
                                                    <input type="checkbox"
                                                        id="WisiloUserGroupForm_admin"
                                                        name="WisiloUserGroupForm_admin"
                                                        class=""
                                                        v-model="WisiloUserGroupForm.admin"/>
                                                    <label for="WisiloUserGroupForm_admin" class="detail-label">
                                                        {{ $t('Admin') }}  
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group col-lg-12 col-md-12 col-xs-12 ">
                                                <label for="WisiloUserGroupForm_title" class="detail-label">{{ $t('Title') }}  </label>
                                                <input type="text"
                                                    v-model="WisiloUserGroupForm.title"
                                                    class="form-control "
                                                    :class="{ 'is-invalid': WisiloUserGroupForm.errors.has('title') }"
                                                    id="WisiloUserGroupForm_title"
                                                    name="WisiloUserGroupForm_title">
                                                    <has-error :form="WisiloUserGroupForm" field="title"></has-error>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <div class="col-lg-12 col-md-12 col-xs-12">
                                            <button v-if="'new' == id"
                                                menu-permission-token="wisilousergroup"
                                                model-permission-token="WisiloUserGroup-create"
                                                :disabled="WisiloUserGroupForm.busy"
                                                type="submit"
                                                class="sbp-item sbp-hide btn btn-success btn-md btn-on-card float-right">
                                                {{ $t('Create') }}
                                            </button>
                                            <button v-else 
                                                menu-permission-token="wisilousergroup"
                                                model-permission-token="WisiloUserGroup-update"
                                                :disabled="WisiloUserGroupForm.busy"
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
        </div>
        <input type="hidden" id="controller" value="wisilousergroup">
    </div>
</template>

<script>

export default {
    data() {
        return {
            main_folder: '',
            pagename: '',
            id: 0,
			WisiloUserGroupForm: new Form({
                'debug_mode': false,
                'id': this.id,
                'enabled': false,
                'admin': false,
                'title': ''
            }),
            page: {
                is_ready: false,
                has_server_error: false,
                variables: [],
                authorization: {
                    status: true,
                    type: "",
                    msg: ""
                },
                is_variables_loading: false,
                is_variables_loaded: false,
                has_post_error: false,
                post_error_msg: '',
                is_ready: false,
                is_data_loading: false,
                is_data_loaded: false,
                is_files_loading: false,
                is_files_loaded: false
            }
        };
    },
    computed: {
        backbuttonURL() {
            let URL = '/' + this.main_folder + '/wisilousergroup';
            if (this.id > 0) {
                URL = URL + '/detail/' + this.id;
            }
            return URL;
        },
        configurationURL() {
            let URL = '/' + this.main_folder + '/wisilousergroup';
            if (this.id > 0) {
                URL = URL + '/configuration/' + this.id;
            }
            return URL;
        },
    },
    methods: {
        processLoadQueue: function () {
            if (this.page.has_server_error) {
                this.$Progress.finish();
                this.page.is_ready = true;
                return;
            }

            if (!this.page.authorization.status) {
                this.$Progress.finish();
                this.page.is_ready = true;
                return;
            }

            if (!this.page.is_variables_loaded && !this.page.is_data_loaded && !this.page.is_files_loaded) {
                this.$Progress.start();
            }
            
            if (!this.page.is_variables_loaded) {
                this.loadPageVariables();
            } else {
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

            axios.get(WisiloHelper.getAPIURL("wisilo/get_page_variables/" + self.pagename))
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
                    WisiloHelper.initializePermissions(self.page.variables, false);
                    self.page.authorization = WisiloHelper.isUserAuthorized(self.page.variables, self.pagename, 'wisilousergroup', 'create');
                    self.processLoadQueue();
                });
        },
		load_files: function () {
            if (this.page.is_files_loading) {
                return;
            }

            this.page.is_files_loading = true;

            axios.get(WisiloHelper.getAPIURL("wisilousergroup/get_files/" + this.id))
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

            axios.get(WisiloHelper.getAPIURL("wisilousergroup/get/" + this.id))
                .then(({ data }) => {
                    this.page.is_data_loaded = true;
                    this.page.is_data_loading = false;
                    if (this.id > 0) {
                        this.WisiloUserGroupForm.fill(data.object);
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
            WisiloHelper.initializeTextEditor();
                    
            var locationInputs = this.$el.querySelectorAll(".vue-location");
            locationInputs.forEach(locationInput => {
                WisiloHelper.updateLocationPicker(locationInput);
            });

            var switchInputs = this.$el.querySelectorAll(".vue-switch");
            switchInputs.forEach(switchInput => {
                WisiloHelper.updateSwitch(switchInput);
            });

			WisiloHelper.initializePageFiles(this.files);
		},
        submitForm: function () {
            var self = this;
            self.$Progress.start();
            self.WisiloUserGroupForm.post(WisiloHelper.getAPIURL("wisilousergroup/post"))
                .then(({ data }) => {
                    self.$Progress.finish();
                    self.id = data.id;
                    self.page.has_post_error = data.has_error;
                    self.page.post_error_msg = data.error_msg;
                    self.page.has_server_error = false;
                }).catch(({ data }) => {
                    self.$Progress.fail();
                    let errors = (self.WisiloUserGroupForm.errors.errors);
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
                                    self.$router.push('/' + self.main_folder + '/wisilousergroup/detail/' + self.id);
                                }
                            });
                        } else {
                            Vue.swal.fire({
                                position: 'top-end',
                                title:self.page.post_error_msg,
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
        this.main_folder = WisiloHelper.getMainFolder();
        this.pagename = WisiloHelper.getPagename();
        this.id = this.$route.params.id;
        this.page.is_ready = false;
        this.processLoadQueue();
    }
}
</script>