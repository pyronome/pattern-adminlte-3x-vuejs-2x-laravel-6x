<template>
    <div class="content-wrapper">
        <server-error v-if="page.has_server_error" ></server-error>
        <permission-error v-else-if="!page.authorization.status" :authorization="page.authorization"></permission-error>
        <div v-else>
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-0">
                        <div class="col-sm-6">
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="home">{{ $t('Home') }}</a></li>
                                <li class="breadcrumb-item"><a href="configuration">{{ $t('Configuration') }}</a></li>
                            </ol>
                        </div>
                    </div>
                </div>
            </section>
            <layout :pagename="pagename" :pagevariables="page.variables"></layout>
        </div>
        <input type="hidden" id="controller" :value="pagename">
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
                authorization: {
                    status: true,
                    type: "",
                    msg: ""
                },
                is_variables_loading: false,
                is_variables_loaded: false,
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

            if (!this.page.is_variables_loaded) {
                this.$Progress.start();
                this.loadPageVariables();
            } else {
                this.$Progress.finish();
                this.page.is_ready = true;
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
                    WisiloHelper.initializePermissions(self.page.variables, true);
                    self.page.authorization = WisiloHelper.isUserAuthorized(self.page.variables, self.pagename);
                    self.processLoadQueue();
                });
        }
    },
    mounted() {
        this.main_folder = WisiloHelper.getMainFolder();
        this.pagename = WisiloHelper.getPagename();
        this.page.is_ready = false;
        this.processLoadQueue();
    }
}
</script>