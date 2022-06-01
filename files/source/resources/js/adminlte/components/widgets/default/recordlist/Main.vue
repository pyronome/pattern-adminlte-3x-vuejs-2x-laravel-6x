<template>
    <div class="widgetcomponent">
        <div class="widget-inner-container">
            <h5 class="m-0" v-html="record_list_title"></h5>
            <div class="recordlist-search-container">
                <div class="input-group input-group-sm divSearchBar float-right" style="margin-bottom:1rem;">
                    <input type="text"
                        id="searchText" name="searchText" 
                        @keyup="search_list" v-model="search_text"
                        class="form-control float-right inputSearchBar"
                        v-bind:placeholder="$t('Search')">
                    <div class="input-group-append labelSearchBar">
                        <button type="button" class="btn btn-default ">
                            <img class="imgLoader" src="/img/adminlte/loader.svg" width="14" height="14"/>
                            <i class="fas fa-search text-primary"></i>
                        </button>
                    </div>
                </div>
            </div>
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
                search_text: "",
                content: this.data.content,
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
            loadData: function (callback) {
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
                    }).finally(function() {
                        if (undefined !== callback) {
                            callback();
                        }
                    });
            },
            /* search_list: _.debounce(function (e) {
                var search_input = e.target;
                
                AdminLTEHelper.activateSearchLoader(search_input);

                this.search_text = search_input.value;
                this.current_page = 1;

                this.loadData(function(){
                    AdminLTEHelper.deactivateSearchLoader(search_input)
                });
            }, 1000), */

            search_list: _.debounce(function (e) {
                var self = this;
                var search_input = e.target;

                var custom_variableId = window.__custom_variables.getLocalVariableMatch(self.instance_id, "__list_search_box_value");

                if (0 != custom_variableId) {
                    AdminLTEHelper.activateSearchLoader(search_input);
                    self.search_text = search_input.value;

                    window.__custom_variables.setValue(custom_variableId, self.search_text);
                    
                    self.loadData(function(){
                        AdminLTEHelper.deactivateSearchLoader(search_input)
                    });
                }
            }, 1000),
            /* dosearch_text_variable_change: function(variable_name, old_value, new_value) {
                this.refresh();
            }, */
        },
        mounted() {
            this.loadData();
            window.mainLayoutInstance.pageWidgets[this.instance_id].main = this;

            /* this.registerVariableChangeFunctions(); */

            /* window.__custom_variables.watch(this.search_text_variable, this.refresh);
            window.__custom_variables.watch(this.page_number_variable, this.refresh); */

            /* window.__custom_variables.setValue()
            window.__custom_variables.getValue() */
        }
    }
</script>