<template>
    <div class="content-wrapper">
        <!--server-error v-if="page.has_server_error" ></server-error>
        <permission-error v-else-if="!page.is_authorized" :type="page.unauthorized_type"></permission-error>
        <div v-else>
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>{{ $t("Home") }}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">{{ $t("Home") }}</li>
                        </ol>
                    </div>
                    </div>
                </div>
            </section>
            <section class="content">
                <div class="container-fluid">
                    <page-layout :pagename="pagename"></page-layout>
                </div>
            </section>
        </div>

        

        <input type="hidden" id="controller" :value="pagename">
        <body-loader :body_loader_active="body_loader_active" class="content-wrapper bodyLoader"></body-loader-->

        <layout :pagename="pagename" :pagevariables="page.variables"></layout>
    </div>
</template>

<script>

export default {
    data() {
        return {
            main_folder: '',
            pagename: '',
            page: {
                is_ready: false,
                has_server_error: false,
                variables: [],
                is_authorized: true,
                unauthorized_type: '',
                is_variables_loading: false,
                is_variables_loaded: false,
            },
            body_loader_active: false
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

            if (!self.page.is_authorized) {
                self.$Progress.finish();
                self.page.is_ready = true;
                return;
            }

            if (!self.page.is_variables_loaded) {
                self.$Progress.start();
                self.loadPageVariables();
            } else {
                self.$nextTick(function () {
                    setTimeout(function() {
                        self.initializePage();
                        self.body_loader_active = false;
                    }, 500);                        
                });
                
                self.$Progress.finish();
                self.page.is_ready = true;
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
                   AdminLTEHelper.initializePermissions(self.page.variables, true);
                   let authorize = AdminLTEHelper.isUserAuthorized(self.page.variables, self.pagename);
                   self.page.is_authorized = authorize.status;
                   self.page.unauthorized_type = authorize.type;
                   self.processLoadQueue();
                });
        },
        initializePage: function () {
            var self = this;
        }
    },
    mounted() {
        var self = this;
        self.body_loader_active = true;
        self.main_folder = AdminLTEHelper.getMainFolder();
        var pagename = AdminLTEHelper.getPagename();
        self.pagename = ('' != pagename) ? pagename : 'home';
        self.page.is_ready = false;
        self.processLoadQueue();
    }
}
</script>