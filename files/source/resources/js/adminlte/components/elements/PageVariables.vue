<template>
    <div></div>
</template>
<script>
export default {
    props: ['has_widgets'],
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
    computed: {
        main_folder() {
            return AdminLTEHelper.getMainFolder();
        },
        pagename() {
            return AdminLTEHelper.getPagename();
        }
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

            if ('' == self.pagename) {
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
            this.setupShowByPermissionItem();
        },
        showHideMenuItem() {
            $('li.nav-item').css("display", "none");
            
            if(this.page_variables.is_admin) {
                $('li.nav-item').css("display", "block");
            } else {
                if ('undefined' !== typeof this.page_variables.permissions.__adminlte_menu) {
                    let menu_permissions = this.page_variables.permissions.__adminlte_menu;
                    Object.keys(menu_permissions).map((key) => {
                        if (menu_permissions[key]) {
                            $('li.nav-item[data-href="' + key + '"]').css("display", "block");
                        }
                    });
                }
            }

            console.log("page_variables showHideMenuItem");
        },
        setupShowByPermissionItem() {
            if (this.page_variables.is_admin) {
                return;
            }
            
            if ('undefined' !== typeof this.page_variables.permissions.__adminlte_menu) {
                let menu_permissions = this.page_variables.permissions.__adminlte_menu;
                Object.keys(menu_permissions).map((key) => {
                    if (menu_permissions[key]) {
                        $('.sbp-item.sbp-hide[menu-permission-token="' + key + '"]').removeClass('sbp-hide');
                    } else {
                        $('.sbp-item[menu-permission-token="' + key + '"]').addClass('sbp-hide');
                    }
                });
            }

            if ('undefined' !== typeof this.page_variables.permissions.__adminlte_model) {
                let model_permissions = this.page_variables.permissions.__adminlte_model;
                Object.keys(model_permissions).map((key) => {
                    if (model_permissions[key]) {
                        $('.sbp-item[model-permission-token="' + key + '"]').removeClass('sbp-hide');
                    } else {
                        $('.sbp-item[model-permission-token="' + key + '"]').addClass('sbp-hide');
                    }
                });
            }
        }
    },
    mounted() {
        this.page.is_ready = false;
        this.processLoadQueue();

        this.$root.$on('widgets-loaded', () => {
            this.setupShowByPermissionItem();
        });
    }
}
</script>