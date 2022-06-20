<template>
    <div class="content-wrapper">
        <server-error v-if="page.has_server_error" ></server-error>
        <permission-error v-else-if="!page.authorization.status" :authorization="page.authorization"></permission-error>
        <div v-else>
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark">
                                {{ $t('Server Information') }}
                            </h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="home">{{ $t('Home') }}</a></li>
                                <li class="breadcrumb-item"><a href="configuration">{{ $t('Configuration') }}</a></li>
                                <li class="breadcrumb-item enabled">{{ $t('Server Information') }}</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <section class="content" v-show="page.is_ready">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12">
                            <div class="card card-solid">
                                <div class="card-body pb-0">
                                    <div class="row d-flex align-items-stretch">
                                        <div class="col-12 col-sm-12 col-md-6 d-flex align-items-stretch">
                                            <div class="card bg-light server_info_card">
                                                <div class="card-header text-muted border-bottom-0">
                                                    {{ $t('Operating System') }}
                                                </div>
                                                <div class="card-body pt-0">
                                                    <div class="row">
                                                        <div class="col-7">
                                                            <h2 class="lead"><b>{{ server_information.os_header }}</b></h2>
                                                            <p class="text-muted text-sm">{{ server_information.os_detail }}</p>
                                                        </div>
                                                        <div class="col-5 text-right">
                                                            <img v-bind:src="server_information.os_icon_src" alt="" class="img-fluid">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-12 col-md-6 d-flex align-items-stretch">
                                            <div class="card bg-light server_info_card">
                                                <div class="card-header text-muted border-bottom-0">
                                                    {{ $t('Web Server') }}
                                                </div>
                                                <div class="card-body pt-0">
                                                    <div class="row">
                                                        <div class="col-7">
                                                            <h2 class="lead"><b>{{ server_information.web_header }}</b></h2>
                                                            <p class="text-muted text-sm">{{ server_information.web_detail }}</p>
                                                        </div>
                                                        <div class="col-5 text-right">
                                                            <img v-bind:src="server_information.web_icon_src" alt="" class="img-fluid">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-12 col-md-6 d-flex align-items-stretch">
                                            <div class="card bg-light server_info_card">
                                                <div class="card-header text-muted border-bottom-0">
                                                    {{ $t('Application Server') }}
                                                </div>
                                                <div class="card-body pt-0">
                                                    <div class="row">
                                                        <div class="col-7">
                                                            <h2 class="lead"><b>{{ server_information.app_header }}</b></h2>
                                                            <p class="text-muted text-sm">{{ server_information.app_detail }}</p>
                                                        </div>
                                                        <div class="col-5 text-right">
                                                            <img v-bind:src="server_information.app_icon_src" alt="" class="img-fluid">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-12 col-md-6 d-flex align-items-stretch">
                                            <div class="card bg-light server_info_card">
                                                <div class="card-header text-muted border-bottom-0">
                                                    {{ $t('Database Server') }}
                                                </div>
                                                <div class="card-body pt-0">
                                                    <div class="row">
                                                        <div class="col-7">
                                                            <h2 class="lead"><b>{{ server_information.db_header }}</b></h2>
                                                            <p class="text-muted text-sm">{{ server_information.db_detail }}</p>
                                                        </div>
                                                        <div class="col-5 text-right">
                                                            <img v-bind:src="server_information.db_icon_src" alt="" class="img-fluid">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <input type="hidden" id="controller" value="server_information">
    </div>
</template>

<script>
export default {
    data() {
        return {
            main_folder: '',
            pagename: '',
            server_information: {
                "os_header": "",
                "os_detail": "",
                "os_icon_src": "",
                "web_header": "",
                "web_detail": "",
                "web_icon_src": "",
                "app_header": "",
                "app_detail": "",
                "app_icon_src": "",
                "db_header": "",
                "db_detail": "",
                "db_icon_src": ""
            },
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
                is_data_loading: false,
                is_data_loaded: false,
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

            if (!this.page.authorization.status) {
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
                    self.page.authorization = WisiloHelper.isUserAuthorized(self.page.variables, self.pagename);
                    self.processLoadQueue();
                });
        },
        loadData: function () {
            if (this.page.is_data_loading) {
                return;
            }

            this.page.is_data_loading = true;
            
            var self = this;

            axios.get(WisiloHelper.getAPIURL("server_information"))
                .then(({ data }) => {
                    this.page.is_data_loaded = true;
                    this.page.is_data_loading = false;
                    this.server_information = data;
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
        this.main_folder = WisiloHelper.getMainFolder();
        this.pagename = WisiloHelper.getPagename();
        this.id = this.$route.params.id;
        this.processLoadQueue();
    }
}
</script>