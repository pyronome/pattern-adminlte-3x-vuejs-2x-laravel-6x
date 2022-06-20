<template>
    <div :pagename="pagename" :parameters="parameters">
        <div class="pageLayoutContainer" v-html="layout_value"></div>
    </div>
</template>

<script>
    export default {
        props: ['pagename', 'parameters'],
        data() {
            return {
                main_folder: '',
                layout_base_value: '',
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
                var self = this;

                if (self.is_layout_loaded) {
                    self.$nextTick(function () {
                        setTimeout(function() {
                            self.addParameterToLayout();
                            self.initializePage();
                            self.body_loader_active = false;
                        }, 500);                        
                    });
                } else {
                    self.loadLayouts();
                }
            },
            loadLayouts: function () {
                var self = this;

                if (self.is_layout_loading) {
                    return;
                }

                self.is_layout_loading = true;

                axios.get(WisiloHelper.getAPIURL("__layout/get_page_layout/" + self.pagename))
                    .then(({ data }) => {
                        self.is_layout_loaded = true;
                        self.is_layout_loading = false;
                        self.layout_base_value = data;
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
            },
            addParameterToLayout: function() {
                var self = this;

                var result = self.layout_base_value;

                if (self.parameters) {
                    Object.keys(self.parameters).forEach(key => {
                        result = self.layout_base_value.replace(key, self.parameters[key]);
                    });
                }

                self.layout_value = result;
            }
        },
        mounted() {
            this.main_folder = WisiloHelper.getMainFolder();
        },
        updated() {
            
        }
    }
</script>