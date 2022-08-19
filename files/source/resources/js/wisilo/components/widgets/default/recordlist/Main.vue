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
                            <img class="imgLoader" src="/img/wisilo/loader.svg" width="14" height="14"/>
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
            <div style="min-height:60px;">
                <pagination v-if="data_pagination.show_pagination" :data="data_pagination" :limit="1" align="right" :show-disabled="false" @pagination-change-page="paginate">
                    <span slot="prev-nav">&lt;</span>
                    <span slot="next-nav">&gt;</span>
                </pagination>
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
                current_page: 1,
                show_pagination: false,
                content: this.data.content,
                record_list_title: '',
                titles: [],
                variables: [],
                list: [],
                data_pagination: [],
                dependant_customvariables: [],
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
        },
        methods: {
            refresh: function () {
                this.data = window.mainLayoutInstance.pageWidgets[this.instance_id].data;
                this.loadData(function(){});
            },
            loadData: function (callback) {
                var self = this;

                if (undefined === self.data.general.id) {
                    return;
                }

                var parameters = WisiloHelper.getWidgetParameter(self.data.general.id, "");
                axios.get(WisiloHelper.getAPIURL("__layout/get_recordlist_data/" + parameters))
                    .then(({ data }) => {
                        if (data) {
                            self.record_list_title = data.record_list_title;
                            self.show_pagination = data.show_pagination;
                            self.titles = data.table_header.titles;
                            self.variables = data.table_header.variables;
                            self.list = data.list;
                            self.data_pagination = data.data_pagination;
                            self.dependant_customvariables = data.dependant_customvariables;
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
            search_list: _.debounce(function (e) {
                var self = this;
                var search_input = e.target;

                var per_page = 50;
                if (undefined !== self.data_pagination.per_page) {
                    per_page = self.data_pagination.per_page;
                }

                var custom_variableId = window.__custom_variables.getLocalVariableMatch(self.instance_id, "__list_search_box_value");

                if (0 != custom_variableId) {
                    WisiloHelper.activateSearchLoader(search_input);
                    self.search_text = search_input.value;

                    window.__custom_variables.setValue(custom_variableId, self.search_text);

                    custom_variableId = window.__custom_variables.getLocalVariableMatch(self.instance_id, "__list_page_number");
                    
                    if (0 != custom_variableId) {
                        window.__custom_variables.setValue(custom_variableId, 0);
                    }
                    
                    self.loadData(function(){
                        WisiloHelper.deactivateSearchLoader(search_input)
                    });
                }
            }, 1000),
            paginate: function (page = 1) {
                var self = this;
                var per_page = 50;
                if (undefined !== self.data_pagination.per_page) {
                    per_page = self.data_pagination.per_page;
                }

                var custom_variableId = window.__custom_variables.getLocalVariableMatch(self.instance_id, "__list_page_number");
                
                if (0 != custom_variableId) {
                    window.__custom_variables.setValue(custom_variableId, ((page-1) * per_page));
                    
                    self.loadData(function(){
                    });
                }
            },
            registerWidgetCustomVariableDependancy: function() {
                if (this.dependant_customvariables.length > 0) {
                    window.__custom_variables.registerWidgetCustomVariableDependancy(this.dependant_customvariables, this.instance_id);
                }
            }
        },
        mounted() {
            var self = this;

            window.__custom_variables.setCustomVariableValues(self.instance_id, 
                function () {
                    self.loadData(
                        function() {
                            self.registerWidgetCustomVariableDependancy();
                        }
                    );
                }
            );

            window.mainLayoutInstance.pageWidgets[self.instance_id].main = self;
        }
    }
</script>