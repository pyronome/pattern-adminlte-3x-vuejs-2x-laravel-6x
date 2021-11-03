<template>
    <div class="content-wrapper">
        <server-error v-if="page.has_server_error" ></server-error>
        <permission-error v-else-if="!page.is_authorized" :type="page.unauthorized_type"></permission-error>
        <div v-else>
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>{{ $t("AdminLTEConfig Detail") }}</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><router-link :to="'/' + main_folder + '/home'">{{ $t('Home') }}</router-link></li>
                                <li class="breadcrumb-item"><router-link :to="'/' + main_folder + '/adminlteconfig'">{{ $t("AdminLTEConfig List") }}</router-link></li>
                                <li class="breadcrumb-item active">{{ $t("AdminLTEConfig Detail") }}</li>
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
                                            :to="'/' + main_folder + '/adminlteconfig/edit/' + id">
                                            <i class="fas fa-pencil-alt" aria-hidden="true"></i> <span>{{ $t('Edit') }}</span>
                                        </router-link>
                                    </div>
                                </div>
                                <div class="card-body p-0">
                                    <table class="table table-hover table-sm">
                                        <thead>
                                            <tr>
                                                <th colspan="2">{{ $t("AdminLTEConfig Detail") }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="">
                                                <td width="30%" class="text-muted"><strong>{{ $t('Enabled') }}</strong></td>
                                                <td><div v-html="data.enabled__displaytext__"></div></td>
                                            </tr>
                                            <tr class="">
                                                <td width="30%" class="text-muted"><strong>{{ $t('Required') }}</strong></td>
                                                <td><div v-html="data.required__displaytext__"></div></td>
                                            </tr>
                                            <tr class="">
                                                <td width="30%" class="text-muted"><strong>{{ $t('Order') }}</strong></td>
                                                <td><div v-html="data.__order__displaytext__"></div></td>
                                            </tr>
                                            <tr class="">
                                                <td width="30%" class="text-muted"><strong>{{ $t('Type') }}</strong></td>
                                                <td><div v-html="data.type__displaytext__"></div></td>
                                            </tr>
                                            <tr class="">
                                                <td width="30%" class="text-muted"><strong>{{ $t('Key') }}</strong></td>
                                                <td><div v-html="data.__key__displaytext__"></div></td>
                                            </tr>
                                            <tr class="">
                                                <td width="30%" class="text-muted"><strong>{{ $t('Title') }}</strong></td>
                                                <td><div v-html="data.title__displaytext__"></div></td>
                                            </tr>
                                            <tr class="">
                                                <td width="30%" class="text-muted"><strong>{{ $t('Meta Data') }}</strong></td>
                                                <td><div v-html="data.meta_data__displaytext__"></div></td>
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
            <image-display :init_image_display="init_image_display"></image-display>
        </div>
        <input type="hidden" id="controller" :value="pagename">
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
                   let authorize = AdminLTEHelper.isUserAuthorized(self.page.variables, self.pagename, 'AdminLTEConfig', 'read');
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

            axios.get(AdminLTEHelper.getAPIURL("adminlteconfig/get/" + this.id))
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
            setTimeout(function() {
                self.body_loader_active = false;
            }, 500);
        }
    },
    mounted() {
        var self = this;
        self.body_loader_active = true;
        self.main_folder = AdminLTEHelper.getMainFolder();
        self.pagename = AdminLTEHelper.getPagename();
        self.id = self.$route.params.id;
        self.processLoadQueue();
    }
}
</script>