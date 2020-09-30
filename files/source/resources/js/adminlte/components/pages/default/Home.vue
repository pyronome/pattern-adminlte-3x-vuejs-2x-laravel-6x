<template>
    <div class="content-wrapper">
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
        <input type="hidden" id="controller" value="home">
    </div>
</template>

<script>

export default {
    data() {
        return {
            widgets: [],
            pagename: '',
            page: {
                is_ready: false,
                is_widgets_loading: false,
                is_widgets_loaded: false,
            }
        };
    },
    methods: {
        processLoadQueue: function () {
            if (!this.page.is_widgets_loaded) {
                this.$Progress.start();
                this.loadWidgets();
            } else {
                this.$Progress.finish();
                this.page.is_ready = true;
            }
        },
        loadWidgets: function () {
            if (this.page.is_widgets_loading) {
                return;
            }

            this.page.is_widgets_loading = true;

            axios.get(AdminLTEHelper.getAPIURL("__layout/get_page_widgets/home"))
                .then(({ data }) => {
                    this.page.is_widgets_loaded = true;
                    this.page.is_widgets_loading = false;
                    this.widgets = data;
                    this.pagename = 'home';
                    this.processLoadQueue();
                }).catch(({ data }) => {
                    this.page.is_widgets_loaded = true;
                    this.page.is_widgets_loading = false;
                    this.$Progress.fail();
                    this.processLoadQueue();
                });
        }
    },
    mounted() {
        this.page.is_ready = false;
        this.processLoadQueue();
    }
}
</script>