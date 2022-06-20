<template>
    <div class="content-wrapper">
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
                authorization: {
                    status: true,
                    type: "",
                    msg: ""
                },
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

            if (!self.page.authorization.status) {
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
        },
        initializePage: function () {
            var self = this;
        }
    },
    mounted() {
        var self = this;
        self.body_loader_active = true;
        self.main_folder = WisiloHelper.getMainFolder();
        var pagename = WisiloHelper.getPagename();
        self.pagename = ('' != pagename) ? pagename : 'home';
        self.page.is_ready = false;
        self.processLoadQueue();
    }
}
</script>