<template>
    <div class="content-wrapper">
        <server-error v-if="page.has_server_error" ></server-error>
        <permission-error v-else-if="!page.authorization.status" :authorization="page.authorization"></permission-error>
        <div v-else>
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <!--
                        <div class="col-sm-6">
                            <h1>{{ $t("Profile Edit") }}</h1>
                        </div>
                        -->
                        <div class="col-sm-12">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><router-link :to="'/' + main_folder + '/home'">{{ $t('Home') }}</router-link></li>
                                <li class="breadcrumb-item"><router-link :to="'/' + main_folder + '/profile/detail'">{{ $t("Profile Detail") }}</router-link></li>
                                <li class="breadcrumb-item active">{{ $t("Profile Edit") }}</li>
                            </ol>
                        </div>
                        <div class="col-sm-12">
                            <a class="btn btn-primary btn-md btn-on-card text-white float-sm-right" :href="'/' + main_folder + '/profile/configuration'">
                                <span>Configuration</span>
                            </a>
                        </div>
                    </div>
                </div>
            </section>
            <section class="content">
                <div class="container-fluid">
                    <form id="ProfileForm"
                            class=""
                            @submit.prevent="submitForm"
                            @keydown="ProfileForm.onKeydown($event)">
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-xs-12 ">
                                <h4 class="form-part-header">{{  $t('Profile Image') }}</h4>
                                <h6 class="form-part-instructions text-muted">
                                    {{  $t('You can upload a profile image using this section.') }}
                                </h6>
                            </div>
                            <div class="col-lg-8 col-md-8 col-xs-12 ">
                                <div class="card">
                                    <div class="card-body">
                                        <input type="hidden" v-model="ProfileForm.id" id="id" name="id">
                                        <div class="row">
                                            <div class="form-group col-lg-12 col-md-12 col-xs-12 ">
                                                <label for="ProfileForm_profile_img" class="detail-label">{{ $t('Profile Image') }}  </label>
                                                <div class="input-field">
                                                    <input type="hidden"
                                                        class="form-control"
                                                        id="ProfileForm_profile_img"
                                                        name="ProfileForm_profile_img"
                                                        v-model="ProfileForm.profile_img"
                                                        :class="{ 'is-invalid': ProfileForm.errors.has('profile_img') }"
                                                        data-target-field="WisiloUser/profile_img"
                                                        data-media-type="2"
                                                        data-max-file-count="1"/>
                                                    <button type="button"
                                                        id="buttonBrowseprofile_imgFiles"
                                                        name="buttonBrowseprofile_imgFiles"
                                                        class="buttonBrowseFile btn btn-primary show_by_permission_must_update"
                                                        data-target-file-list="ulprofile_imgFileList">
                                                        <i class="ion-ios-folder"></i>&nbsp;{{ $t('Browse Profile Image...') }}
                                                    </button>
                                                    <has-error :form="ProfileForm" field="profile_img"></has-error>
                                                    <ul id="ulprofile_imgFileList" class="col s12 collection ulFileList" data-target-input-id="ProfileForm_profile_img">
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-xs-12 ">
                                <h4 class="form-part-header">{{  $t('Profile Information') }}</h4>
                                <h6 class="form-part-instructions text-muted">
                                    {{  $t('You can edit profile information using this section.') }}
                                </h6>
                            </div>
                            <div class="col-lg-8 col-md-8 col-xs-12 ">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="form-group col-lg-12 col-md-12 col-xs-12 ">
                                                <label for="ProfileForm_fullname" class="detail-label">{{ $t('Fullname') }} <span class="required">*</span></label>
                                                <input type="text"
                                                    v-model="ProfileForm.fullname"
                                                    class="form-control "
                                                    :class="{ 'is-invalid': ProfileForm.errors.has('fullname') }"
                                                    id="ProfileForm_fullname"
                                                    name="ProfileForm_fullname">
                                                <has-error :form="ProfileForm" field="fullname"></has-error>
                                            </div> 
                                            <div class="form-group col-lg-12 col-md-12 col-xs-12 ">
                                                <label for="ProfileForm_username" class="detail-label">{{ $t('Username') }} <span class="required">*</span></label>
                                                <input type="text"
                                                    v-model="ProfileForm.username"
                                                    class="form-control "
                                                    :class="{ 'is-invalid': ProfileForm.errors.has('username') }"
                                                    id="ProfileForm_username"
                                                    name="ProfileForm_username">
                                                <has-error :form="ProfileForm" field="username"></has-error>
                                            </div>
                                            <div class="form-group col-lg-12 col-md-12 col-xs-12 ">
                                                <label for="ProfileForm_email" class="detail-label">{{ $t('Email') }} <span class="required">*</span></label>
                                                <input type="email"
                                                    v-model="ProfileForm.email"
                                                    class="form-control "
                                                    :class="{ 'is-invalid': ProfileForm.errors.has('email') }"
                                                    id="ProfileForm_email"
                                                    name="ProfileForm_email">
                                                <has-error :form="ProfileForm" field="email"></has-error>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-xs-12 "></div>
                            <div class="col-lg-8 col-md-8 col-xs-12 ">
                                <div class="card">
                                    <div class="action-buttons-container">
                                        <button :disabled="ProfileForm.busy"
                                            type="submit"
                                            class="btn btn-success btn-card-default btn-card-footer float-right">
                                            {{ $t('Save') }}
                                        </button>
                                        <router-link tag="a"
                                            class="btn btn btn-outline-secondary btn-card-footer float-right"
                                            :to="'/' + this.main_folder + '/profile/detail'"
                                            style="margin-right:10px;">
                                            <span>{{ $t('Cancel') }}</span>
                                        </router-link>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </section>
            <dropzone upload-url="/api/wisilomedia/post" style="display:none;"></dropzone>
        </div>
        <input type="hidden" id="controller" value="profile">
    </div>
</template>

<script>

export default {
    data() {
        return {
            main_folder: '',
            pagename: 'wisilousergroup',
            id: 0,
            files: [],
            ProfileForm: new Form({
                'debug_mode': false,
                'id': 0,
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
    /* computed: {
        configurationURL() {
            let URL = '/' + this.main_folder + '/profile';
            if (this.id > 0) {
                URL = URL + '/configuration/' + this.id;
            }
            return URL;
        },
    }, */
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
                   self.processLoadQueue();
                });
        },
		load_files: function () {
            if (this.page.is_files_loading) {
                return;
            }

            this.page.is_files_loading = true;

            axios.get(WisiloHelper.getAPIURL("profile/get_files/" + this.id))
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

            axios.get(WisiloHelper.getAPIURL("profile/get"))
                .then(({ data }) => {
                    this.page.is_data_loaded = true;
                    this.page.is_data_loading = false;
                    this.ProfileForm.fill(data.object);
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
            WisiloHelper.initializePageFiles(this.files);
        },
        submitForm: function () {
            var self = this;
            self.$Progress.start();
            self.ProfileForm.post(WisiloHelper.getAPIURL("profile/post"))
                .then(({ data }) => {
                    self.$Progress.finish();
                    self.page.has_post_error = data.has_error;
                    self.page.post_error_msg = data.error_msg;
                    self.page.has_server_error = false;
                }).catch(({ data }) => {
                    self.$Progress.fail();
                    let errors = (self.ProfileForm.errors.errors);
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
                                    window.location = "detail";
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
        this.main_folder = WisiloHelper.getMainFolder();
        this.page.is_ready = false;
        this.processLoadQueue();
    }
}
</script>