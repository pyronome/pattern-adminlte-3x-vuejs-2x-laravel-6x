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

            if (this.has_widgets && page_variables.show_widget_config_button) {
                $("#buttonWidgetConfig").removeClass("d-none");
            }
        }
    },
    mounted() {
        this.page.is_ready = false;

        if (this.has_widgets) {
            this.processLoadQueue();
        } else {
            this.initializePage();
        }
    }
}
</script>