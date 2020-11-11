<template>
    <div class="content-wrapper">
        <server-error v-if="page.has_server_error" ></server-error>
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
            <section class="container-fluid">
                <widgets :widgets="widgets" :pagename="pagename"></widgets>
            </section>
            <section>
                <widget-editor :pagename="pagename"></widget-editor>
            </section>
        </div>
        <input type="hidden" id="controller" :value="pagename">
    </div>
</template>

<script>

export default {
    data() {
        return {
            widgets: [],
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
                is_widgets_loading: false,
                is_widgets_loaded: false,
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

            if (!this.page.is_authorized) {
                this.$Progress.finish();
                this.page.is_ready = true;
                return;
            }

            if (!this.page.is_variables_loaded && !this.page.is_widgets_loaded) {
                this.$Progress.start();
            }

            if (!this.page.is_variables_loaded) {
                this.loadPageVariables();
            } else {
                if (this.page.is_widgets_loaded) {
                    this.$Progress.finish();
                    this.page.is_ready = true;
                } else {
                    this.loadWidgets();
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
                   AdminLTEHelper.initializePermissions(self.page.variables, true);
                   let authorize = AdminLTEHelper.isUserAuthorized(self.page.variables, self.pagename);
                   self.page.is_authorized = authorize.status;
                   self.page.unauthorized_type = authorize.type;
                   self.processLoadQueue();
                });
        },
        loadWidgets: function () {
            var self = this;

            if (self.page.is_widgets_loading) {
                return;
            }

            self.page.is_widgets_loading = true;

            axios.get(AdminLTEHelper.getAPIURL("__layout/get_page_widgets/" + self.pagename))
                .then(({ data }) => {
                    self.page.is_widgets_loaded = true;
                    self.page.is_widgets_loading = false;
                    self.widgets = data;
                    self.processLoadQueue();
                }).catch(({ data }) => {
                    self.page.is_widgets_loaded = true;
                    self.page.is_widgets_loading = false;
                    self.$Progress.fail();
                    self.page.has_server_error = true;
                    self.processLoadQueue();
                });
        }
    },
    mounted() {
        var self = this;

        self.main_folder = AdminLTEHelper.getMainFolder();
        var pagename = AdminLTEHelper.getPagename();
        self.pagename = ('' != pagename) ? pagename : 'home';
        self.page.is_ready = false;
        self.processLoadQueue();

        self.$root.$on('recordlist-rendered', (model) => {
            AdminLTEHelper.initializePermissions(self.page.variables, true);
        });
    }
}
</script>