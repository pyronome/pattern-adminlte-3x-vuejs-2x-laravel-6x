<template>
    <div class="content-wrapper">
        <server-error v-if="page.has_server_error" ></server-error>
        <permission-error v-else-if="!page.is_authorized" :type="page.unauthorized_type"></permission-error>
        <div v-else>
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>{{ $t("AdminLTEUserGroup Detail") }}</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><router-link :to="'/' + main_folder + '/home'">{{ $t('Home') }}</router-link></li>
                                <li class="breadcrumb-item"><router-link :to="'/' + main_folder + '/adminlteusergroup'">{{ $t("AdminLTEUserGroup List") }}</router-link></li>
                                <li class="breadcrumb-item active">{{data.title__displaytext__}}</li>
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
                                <div class="card-header" v-if="data.user_can_update">
                                    <div class="card-tools">
                                        <router-link tag="a"
                                            class="btn btn-primary btn-md btn-on-card text-white"
                                            :to="'/' + main_folder + '/adminlteusergroup/edit/' + id">
                                            <i class="fas fa-pencil-alt" aria-hidden="true"></i> <span>{{ $t('Edit') }}</span>
                                        </router-link>
                                        <router-link tag="a"
                                            class="btn btn-primary btn-md btn-on-card text-white"
                                            :to="'/' + main_folder + '/adminlteusergroup/permission/' + id">
                                            <i class="fas fa-pencil-alt" aria-hidden="true"></i> <span>{{ $t('Permissions') }}</span>
                                        </router-link>
                                        <router-link tag="a"
                                            class="btn btn-primary btn-md btn-on-card text-white"
                                            :to="'/' + main_folder + '/adminlteusergroup/layout/' + id">
                                            <i class="fas fa-pencil-alt" aria-hidden="true"></i> <span>{{ $t('Layout Settings') }}</span>
                                        </router-link>
                                        <router-link tag="a" v-show="page.variables.is_admin"
                                            class="btn btn-primary btn-md btn-on-card text-white"
                                            :to="'/' + main_folder + '/adminlteusergroup/parametersettings/' + id">
                                            <i class="fas fa-pencil-alt" aria-hidden="true"></i> <span>{{ $t('Parameter Settings') }}</span>
                                        </router-link>
                                    </div>
                                </div>
                                <div class="card-body p-0">
                                    <table class="table table-hover table-sm">
                                        <thead>
                                            <tr>
                                                <th colspan="2">{{ $t("AdminLTEUserGroup Detail") }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td width="30%" class="text-muted"><strong>{{ $t('Enabled') }}</strong></td>
                                                <td><div v-html="data.enabled__displaytext__"></div></td>
                                            </tr>
                                            <tr>
                                                <td width="30%" class="text-muted"><strong>{{ $t('Admin') }}</strong></td>
                                                <td><div v-html="data.admin__displaytext__"></div></td>
                                            </tr>
                                            <tr>
                                                <td width="30%" class="text-muted"><strong>{{ $t('Widget Edit Permission') }}</strong></td>
                                                <td><div v-html="data.widget_permission__displaytext__"></div></td>
                                            </tr>
                                            <tr>
                                                <td width="30%" class="text-muted"><strong>{{ $t('Title') }}</strong></td>
                                                <td><div v-html="data.title__displaytext__"></div></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <page-layout :pagename="pagename"></page-layout>
                </div>
            </section>
        </div>
        <input type="hidden" id="controller" value="adminlteusergroup">
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
                init_image_display: false,
            body_loader_active: false
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
                        this.$nextTick(function () {
                            var self = this;

                            setTimeout(function() {
                                self.initializePage();
                                self.body_loader_active = false;
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
                    let authorize = AdminLTEHelper.isUserAuthorized(self.page.variables, self.pagename, 'AdminLTEUserGroup', 'read');
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

                axios.get(AdminLTEHelper.getAPIURL("adminlteusergroup/get/" + this.id))
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
                    });
        },
        initializePage: function () {
            var self = this;
            self.init_image_display = true;
        }
    },
    mounted() {
        this.body_loader_active = true;
        this.main_folder = AdminLTEHelper.getMainFolder();
        this.pagename = AdminLTEHelper.getPagename();
        this.id = this.$route.params.id;
        this.processLoadQueue();
    }
    }
</script>