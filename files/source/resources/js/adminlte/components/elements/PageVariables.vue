<template>
    <div></div>
</template>
<script>
export default {
    props: ['pagename', 'has_widgets'],
    data() {
        return {
            page_variables: [],
            page: {
                is_ready: false,
                is_data_loading: false,
                is_data_loaded: false,
                is_post_success: false
            }
        };
    },
    methods: {
        processLoadQueue: function () {
            if (!this.page.is_data_loaded) {
                this.loadData();
            } else {
                this.page.is_ready = true;
            }
        },
        loadData: function () {
            var self = this;

            if (self.page.is_data_loading) {
                return;
            }

            self.page.is_data_loading = true;

            if('' == self.pagename) {
                self.pagename = 'anonymous';
            }

            axios.get(AdminLTEHelper.getAPIURL("adminlte/get_page_variables/" + self.pagename))
                .then(({ data }) => {
                    self.page.is_data_loaded = true;
                    self.page.is_data_loading = false;
                    self.page_variables = data;
                    self.processLoadQueue();
                }).catch(({ data }) => {
                    self.page.is_data_loaded = true;
                    self.page.is_data_loading = false;
                    self.processLoadQueue();
                }).finally(function() {
                   self.initializePage();
                });
        },
        initializePage() {
            $("#buttonWidgetConfig").addClass("d-none");

            if (this.has_widgets && this.page_variables.show_widget_config_button) {
                $("#buttonWidgetConfig").removeClass("d-none");
            }

            this.showHideMenuItem();
        },
        showHideMenuItem() {
            if(this.page_variables.is_admin) {
                $('li.nav-item').css("display", "block");
            } else {
                if ('undefined' !== typeof this.page_variables.user_permission_data.menu_permissions) {
                    let menu_permissions = this.page_variables.user_permission_data.menu_permissions;
                    Object.keys(menu_permissions).map((key) => {
                        if (1 == menu_permissions[key]) {
                            $('li.nav-item[data-href="' + key + '"').css("display", "block");
                        } else {
                            $('li.nav-item[data-href="' + key + '"').css("display", "none");
                        }
                    });
                }
            }
        }
    },
    mounted() {
        this.page.is_ready = false;
        this.processLoadQueue();
    }
}
</script>