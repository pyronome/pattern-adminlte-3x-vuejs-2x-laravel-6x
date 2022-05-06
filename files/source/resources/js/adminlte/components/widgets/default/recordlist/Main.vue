<template>
    <div class="widgetcomponent">
        <div class="widget-inner-container">
            <h5 class="m-0" v-html="record_list_title"></h5>
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover table-sm">
                    <thead>
                        <tr>
                            <th v-for="(pair, title_index) in title_pairs" :key="title_index">
                                <span v-html="pair.title"></span>&nbsp;
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(row, row_index) in list" :key="row_index">
                            <td v-for="(displaytext, column_index) in row.displaytexts" :key="column_index" v-html="displaytext" :style="row.styles[column_index]">
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="widget-settings-dialog-container">
            <settingsDialog :instance_id="instance_id"></settingsDialog>
        </div>
        <div class="widget-preview-dialog-container">
            <previewDialog widget_name="recordlist"></previewDialog>
        </div>
    </div>
</template>

<script>
    import settingsDialog from "./Settings.vue";
    import previewDialog from "./Preview.vue";

    export default {
        components: {settingsDialog, previewDialog},
        props: ["instance_id","data"],
        data() {
            return {
                "content": this.data.content,
                record_list_title: '',
                titles: [],
                variables: [],
                list: [],
            };
        },
        computed: {
            title_pairs() {
                const pair = [];
                for (let i = 0, len = Math.max(this.titles.length, this.variables.length); i < len; i++) {
                    pair.push({
                        title: this.titles[i],
                        variable: this.variables[i]
                    });
                }
                return pair;
            },
            /* value_pairs() {
                const pair = [];
                for (let i = 0; i < this.list.; i++) {
                    pair.push({
                        display_text: this.titles[i],
                        style: this.variables[i]
                    });
                }
                return pair;
            } */
        },
        methods: {
            refresh: function () {
                this.data = window.mainLayoutInstance.pageWidgets[this.instance_id].data;
            },
            loadData: function () {
                var self = this;

                var parameters = AdminLTEHelper.getWidgetParameter(self.data.general.id);
                axios.get(AdminLTEHelper.getAPIURL("__layout/get_recordlist_data/" + parameters))
                    .then(({ data }) => {
                        if (data) {
                            self.record_list_title = data.record_list_title;
                            self.titles = data.table_header.titles;
                            self.variables = data.table_header.variables;
                            self.list = data.list;
                        }

                        self.$Progress.finish();
                    }).catch(({ data }) => {
                        self.$Progress.fail();
                    });
            }
        },
        mounted() {
            this.loadData();
            window.mainLayoutInstance.pageWidgets[this.instance_id].main = this;
        }
    }
</script>