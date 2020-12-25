<template>
    <div :pagename="pagename">
        <div class="pageLayoutContainer" v-html="layout_value"></div>
    </div>
</template>

<script>
    export default {
        props: ['pagename'],
        data() {
            return {
                main_folder: '',
                layout_value: '',
                is_layout_loading: false,
                is_layout_loaded: false,
            };
        },
        watch: {
            pagename: function (pagename) {
                this.processLoadQueue();
            }
        },
        methods: {
            processLoadQueue: function () {
                if (this.is_layout_loaded) {
                    this.$nextTick(function () {
                        var self = this;

                        setTimeout(function() {
                            self.initializePage();
                            self.body_loader_active = false;
                        }, 500);                        
                    });
                } else {
                    this.loadLayouts();
                }
            },
            loadLayouts: function () {
                var self = this;

                if (self.is_layout_loading) {
                    return;
                }

                self.is_layout_loading = true;

                axios.get(AdminLTEHelper.getAPIURL("__layout/get_page_layout/" + self.pagename))
                    .then(({ data }) => {
                        self.is_layout_loaded = true;
                        self.is_layout_loading = false;
                        self.layout_value = data;
                        self.processLoadQueue();
                    }).catch(({ data }) => {
                        self.is_layout_loaded = true;
                        self.is_layout_loading = false;
                        self.$Progress.fail();
                        self.processLoadQueue();
                    });
            },
            initializePage: function () {
                var self = this;
                
                if (window.CustomJS != undefined) {
                    if (window.CustomJS.doPageActivate != undefined) {
                        window.CustomJS.doPageActivate(self);
                    }
                }
            }
        },
        mounted() {
            this.main_folder = AdminLTEHelper.getMainFolder();
        },
        updated() {
            
        }
    }
</script>