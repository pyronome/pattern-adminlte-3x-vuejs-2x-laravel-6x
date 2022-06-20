<template>
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-12">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><router-link :to="'/' + main_folder + '/home'">{{ $t('Home') }}</router-link></li>
                            <li class="breadcrumb-item active">{{ $t("Profile Detail") }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-xs-12 "></div>
                    <div class="col-lg-8 col-md-8 col-xs-12 ">
                        <div class="card">
                            <div class="action-buttons-container">
                                <router-link tag="a"
                                    class="btn btn-primary btn-md btn-on-card btn-card-default text-white float-right"
                                    :to="'/' + main_folder + '/profile/configuration'">
                                    <i class="fas fa-pencil-alt" aria-hidden="true"></i> <span>{{ $t('Edit') }}</span>
                                </router-link>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-xs-12 ">
                        <h4 class="form-part-header">{{  $t('Profile Information') }}</h4>
                        <h6 class="form-part-instructions text-muted">
                            {{  $t('You can edit profile information using this section.') }}
                        </h6>
                    </div>
                    <div class="col-lg-8 col-md-8 col-xs-12 ">
                        <div class="card">
                        <div class="card-body p-0">
                            <table class="table table-hover table-sm">
                                <thead>
                                    <tr>
                                        <th colspan="2">{{  $t('Profile Information') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td width="30%" class="text-muted"><strong>{{ $t('Fullname') }}</strong></td>
                                        <td><div v-html="data.fullname__displaytext__"></div></td>
                                    </tr>
                                    <tr>
                                        <td width="30%" class="text-muted"><strong>{{ $t('Username') }}</strong></td>
                                        <td><div v-html="data.username__displaytext__"></div></td>
                                    </tr>
                                    <tr>
                                        <td width="30%" class="text-muted"><strong>{{ $t('Email') }}</strong></td>
                                        <td><div v-html="data.email__displaytext__"></div></td>
                                    </tr>
                                    <tr>
                                        <td width="30%" class="text-muted"><strong>{{ $t('Profile Image') }}</strong></td>
                                        <td>
                                        <div class="detail-container profile-image-container">
                                            <div v-html="data.profile_img__displaytext__"></div>
                                        </div></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-xs-12 ">
                        <h4 class="form-part-header">{{  $t('Profile Security') }}</h4>
                        <h6 class="form-part-instructions text-muted">
                            {{  $t('You can specify your profile password using this section.') }}
                        </h6>
                    </div>
                    <div class="col-lg-8 col-md-8 col-xs-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-xs-12">
                                        <label for="ProfileForm_profile_img" class="detail-label">{{ $t('Profile Password') }}  </label>
                                        <div class="input-field">
                                            <router-link tag="a"
                                                class="btn btn-primary btn-md btn-on-card btn-card-default text-white"
                                                :to="'/' + main_folder + '/profile/changepassword'">
                                                <span>{{ $t('Change Password...') }}</span>
                                            </router-link>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" v-if="page.variables.is_admin">
                    <div class="col-lg-4 col-md-4 col-xs-12 ">
                        <h4 class="form-part-header">{{  $t('Impersonation') }}</h4>
                        <h6 class="form-part-instructions text-muted">
                            {{  $t('You can impersonate any user.') }}
                        </h6>
                    </div>
                    <div class="col-lg-8 col-md-8 col-xs-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-xs-12">
                                        <div class="input-field">
                                            <button type="button"
                                                class="btn btn-info btn-md btn-on-card btn-card-default text-white"
                                                @click="showImpersonationDialog">
                                                <span>{{ $t('Impersonation') }}</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <layout :pagename="pagename" :pagevariables="page.variables"></layout>
        <image-display :init_image_display="init_image_display"></image-display>
    </div>
</template>

<script>

export default {
    data() {
        return {
            main_folder: '',
            pagename: '',
            id: 0,
            data: [],
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
            },
			init_image_display: false
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

            if (!self.page.authorization.status) {
                self.$Progress.finish();
                self.page.is_ready = true;
                return;
            }

            if (!this.page.is_variables_loaded && !this.page.is_data_loaded) {
                this.$Progress.start();
            }

            if (!this.page.is_variables_loaded) {
                this.loadPageVariables();
            } else {
                if (this.page.is_data_loaded) {
                    self.$nextTick(function () {
                        setTimeout(function() {
                            self.initializePage();
                        }, 500);                        
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
        loadData: function () {
            if (this.page.is_data_loading) {
                return;
            }

            this.page.is_data_loading = true;
			
			var self = this;

            axios.get(WisiloHelper.getAPIURL("profile/get"))
                .then(({ data }) => {
                    this.page.is_data_loaded = true;
                    this.page.is_data_loading = false;
                    this.data = data.object;
                    this.processLoadQueue();
                }).catch(({ data }) => {
                    this.page.is_data_loaded = true;
                    this.page.is_data_loading = false;
                    this.$Progress.fail();
                    this.page.has_server_error = true;
                    this.processLoadQueue();
                }).finally(function() {
                    self.init_image_display = true;
                });
        },
        initializePage: function () {
            var self = this;
        },
        showImpersonationDialog: function() {
            $("#divImpersonationDialog").modal();
        }
    },
    mounted() {
        var self = this;
        self.main_folder = WisiloHelper.getMainFolder();
        var pagename = WisiloHelper.getPagename();
        self.pagename = ('' != pagename) ? pagename : 'home';
        self.page.is_ready = false;
        self.processLoadQueue();
    }
}
</script>