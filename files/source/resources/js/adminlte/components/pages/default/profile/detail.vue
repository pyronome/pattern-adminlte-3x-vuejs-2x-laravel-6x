<template>
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>{{ $t("Profile Detail") }}</h1>
                    </div>
                    <div class="col-sm-6">
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
                    <div class="col-lg-12 col-md-12 col-xs-12">
                        <div class="card">
                            <div class="card-header show_by_permission_must_update">
                                <div class="card-tools">
                                    <router-link tag="a"
                                        class="btn btn-primary btn-xs btn-on-table text-white"
                                        :to="'/' + main_folder + '/profile/edit'">
                                        <i class="fas fa-pencil-alt" aria-hidden="true"></i> <span>{{ $t('Edit') }}</span>
                                    </router-link>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-xs-12">
                                        <div class="detail-container profile-image-container">
                                            <div v-html="data.profile_img__displaytext__"></div>
                                        </div>
                                    </div>
									<div class="col-lg-12 col-md-12 col-xs-12">
                                        <div class="detail-container">
                                            <label class="detail-label">{{ $t('AdminLTEUserGroup') }}</label>
                                            <div v-html="data.adminlteusergroup_id__displaytext__"></div>
                                        </div>
                                        <div class="detail-container">
                                            <label class="detail-label">{{ $t('Fullname') }}</label>
                                            <div v-html="data.fullname__displaytext__"></div>
                                        </div>
                                        <div class="detail-container">
                                            <label class="detail-label">{{ $t('Username') }}</label>
                                            <div v-html="data.username__displaytext__"></div>
                                        </div>
                                        <div class="detail-container">
                                            <label class="detail-label">{{ $t('Email') }}</label>
                                            <div v-html="data.email__displaytext__"></div>
                                        </div>
									</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <input type="hidden" id="controller" value="profile">
		<image-display :init_image_display="init_image_display"></image-display>
    </div>
</template>

<script>

export default {
    data() {
        return {
            main_folder: '',
            pagename: 'profile',
            id: 0,
            data: [],
            page: {
                is_ready: false,
                has_server_error: false,
                variables: [],
                is_authorized: true,
                unauthorized_type: '',
                is_variables_loading: false,
                is_variables_loaded: false,
                is_data_loading: false,
                is_data_loaded: false,
            },
			init_image_display: false
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

            if (!this.page.is_variables_loaded && !this.page.is_data_loaded) {
                this.$Progress.start();
            }

            if (!this.page.is_variables_loaded) {
                this.loadPageVariables();
            } else {
                if (this.page.is_data_loaded) {
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
                   let authorize = AdminLTEHelper.isUserAuthorized(self.page.variables, self.pagename, 'People', 'read');
                   self.page.is_authorized = authorize.status;
                   self.page.unauthorized_type = authorize.type;
                   self.processLoadQueue();
                });
        },
        loadData: function () {
            if (this.page.is_data_loading) {
                return;
            }

            this.page.is_data_loading = true;
			
			var self = this;

            axios.get(AdminLTEHelper.getAPIURL("profile/get"))
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
        }
    },
    mounted() {
        this.main_folder = AdminLTEHelper.getMainFolder();
        this.id = this.$route.params.id;
        this.processLoadQueue();
    }
}
</script>