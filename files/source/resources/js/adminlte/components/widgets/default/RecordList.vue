<template>
    <div class="widgetcomponent">
        <div class="card collapsed-card recordlist-card">
            <div class="card-header">
                <h3 class="card-title" v-html="widget_options.title"></h3>
                <div class="card-tools">
                    <button type="button" :id="'buttonToggleWidgetRecordList' + model" class="btn btn-tool buttonSHWidget"
                        state="state" data-card-widget="collapse" v-on:click="toggleWidget" ref="toggleWidget">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body" style="display:none;">
                <div class="recordlist-search-container">
                    <div class="input-group input-group-sm divSearchBar float-right" style="margin-bottom:1rem;">
                        <input type="text"
                            id="searchText" name="searchText"
                            @keyup="search_list" v-model="search_text"
                            class="form-control float-right inputSearchBar"
                            placeholder="Search">
                        <div class="input-group-append labelSearchBar">
                            <button type="button" class="btn btn-default ">
                                <img class="imgLoader" src="/img/adminlte/loader.svg" width="14" height="14"/>
                                <i class="fas fa-search text-primary"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th class="text-center sbp-item" :model-permission-token="model + '-delete'">
                                    <div class="icheck-primary d-inline">
                                        <input type="checkbox"
                                            @click="select_all_row($event.target)"
                                            :id="'select_' + model + '_rows'"
                                            class="select_all_row"
                                            :data-model="model">
                                        <label :for="'select_' + model + '_rows'"></label>
                                    </div>
                                </th>
                                <th v-for="(pair, index) in pairs" :key="index">
                                    <button type="button"
                                        :id="'button_sort_' + model + '_' + pair.variable"
                                        class="button-table-sort"
                                        @click="sort(pair.variable)">
                                        <span>{{pair.title}}</span>&nbsp;
                                        <span class="sorting loading">
                                            <img class="imgLoader" src="/img/adminlte/loader.svg" width="14" height="14"/>
                                        </span>
                                        <span class="sorting active default text-muted">
                                            <i class="fa fa-caret-down"></i>
                                        </span>
                                        <span class="sorting desc text-primary">
                                            <i class="fa fa-caret-down"></i>
                                        </span>
                                        <span class="sorting asc text-primary">
                                            <i class="fa fa-caret-up"></i>
                                        </span>
                                    </button>
                                </th>
                                <th class="text-center th-btn-1">
                                    <router-link :id="'buttonNew' + model" class="btn btn-primary btn-xs btn-on-table sbp-item"
                                        :menu-permission-token="model.toLowerCase()"
                                        :model-permission-token="model + '-create'"
                                        :to="'/' + main_folder + '/' + (model.toLowerCase()) + '/edit/new'">
                                        <i class="fa fa-plus"></i> <span class="hidden-xxs">{{ $t('Add') }}</span>
                                    </router-link>
                                    
                                    <button type="button"
                                        :id="'buttonDelete' + model"
                                        @click="deleteSelectedRows($event.target, model)"
                                        class="btn btn-danger btn-xs btn-on-table button-model-delete sbp-item"
                                        :model-permission-token="model + '-delete'"
                                        style="display:none;">
                                        <i class="fa fa-trash"></i> <span class="hidden-xxs">{{ $t('Delete') }}</span> <span class="selected-count"></span>
                                    </button>
                                </th>
                            </tr>
                        </thead>
                        <tbody :id="'tbody' + model + 'RecordList'">
                            <tr v-for="row in list" :key="row.id">
                                <td class="text-center sbp-item" :model-permission-token="model + '-delete'">
                                    <div class="icheck-primary d-inline">
                                        <input type="checkbox"
                                            @click="select_row($event.target)"
                                            :id="'select_' + model + '_row' + row.id"
                                            class="select_row"
                                            :data-model="model"
                                            :data-row-id="row.id">
                                        <label :for="'select_' + model + '_row' + row.id"></label>
                                    </div>
                                </td>
                                <td v-for="(displaytext, index) in row.displaytexts" :key="index" v-html="displaytext">
                                </td>
                                <td class="text-center">
                                    <router-link class="btn btn-outline-primary btn-xs btn-on-table sbp-item"
                                        :menu-permission-token="model.toLowerCase()"
                                        :model-permission-token="model + '-read'"
                                        :to="'/' + main_folder + '/' + (model.toLowerCase()) + '/detail/' + row.id">
                                        <i class="fa fa-info-circle"></i> <span class="hidden-xxs">{{ $t('Detail') }}</span>
                                    </router-link>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer" style="min-height:60px;">
                <pagination v-if="show_pagination" :data="data" :limit="1" align="right" :show-disabled="false" @pagination-change-page="paginate">
                    <span slot="prev-nav">&lt;</span>
                    <span slot="next-nav">&gt;</span>
                </pagination>
            </div>
        </div>
        
    </div>
</template>

<script>
    export default {
        data() {
            return {
                main_folder: '',
                model: this.$attrs.model,
                state: 0,
                cookie_suffix: ('widgetRecordList' + this.$attrs.model),
                data: {},
                widget_options: {
                    size: '',
                    title: '',
                },
                titles: [],
                variables: [],
                list: [],
                search_text: '',
                current_page: 1,
                show_pagination: false,
                sort_variable: 'id',
                sort_direction: 'desc',            
                formDelete: new Form({
                    'selected_ids': []
                })
            };
        },
        computed: {
            pairs() {
                const pair = [];
                for (let i = 0, len = Math.max(this.titles.length, this.variables.length); i < len; i++) {
                    pair.push({
                        title: this.titles[i],
                        variable: this.variables[i]
                    });
                }
                return pair;
            }
        },
        methods: {
            toggleWidget: function() {
                this.state = (1 == this.state) ? 0 : 1;
                setWidgetState(this.cookie_suffix, this.state);
            },
            initializeWidget: function() {
                this.state = getWidgetState(this.cookie_suffix);
                
                if (1 == this.state) {
                    $("#buttonToggleWidgetRecordList" + this.model).parent().parent().parent().CardWidget('expand');
                }
            },
            loadData: function (callback) {
                var self = this;
                var query = AdminLTEHelper.getURLQuery(this);
                axios.get(AdminLTEHelper.getAPIURL("__layout/get_recordlist/" + this.$attrs.pagename + "/" + this.model + query))
                    .then(({ data }) => {
                        this.data = data;
                        this.widget_options = data.data.widget_options;
                        this.titles = this.widget_options.table_header.titles;
                        this.variables = this.widget_options.table_header.variables;
                        this.list = data.data.list;
                        this.show_pagination = data.show_pagination;
                        this.$nextTick(function () {
                            this.initializeWidget();
                        });
                    }).catch(({ data }) => {
                        this.$Progress.fail();
                    }).finally(function() {
                        callback();
                        AdminLTEHelper.cleanCheckedBoxes(self.model);
                    });
            },
            search_list: _.debounce(function (e) {
                var search_input = e.target;
                
                AdminLTEHelper.activateSearchLoader(search_input);

                this.search_text = search_input.value;
                this.current_page = 1;

                this.loadData(function(){
                    AdminLTEHelper.deactivateSearchLoader(search_input)
                });
            }, 1000),
            paginate: function (page = 1) {
                this.current_page = page;
                this.loadData(function(){});
            },
            sort: function (variable) {
                AdminLTEHelper.activateSortLoader(`button_sort_${this.model}_${variable}`);

                this.sort_variable = variable;
                this.sort_direction = ('asc' == this.sort_direction) ? 'desc' : 'asc';
                this.current_page = 1;

                var self = this;
                this.loadData(function() {
                    AdminLTEHelper.deactivateSortLoader(`button_sort_${self.$attrs.model}_${self.sort_variable}`, self.sort_direction)
                });
            },
            select_all_row: function(target) {
                AdminLTEHelper.doCheckAllCheckboxClick(target);
            },
            select_row: function(target) {
                AdminLTEHelper.doCheckboxClick(target);
            },
            deleteSelectedRows: function(sender, model) {
                Vue.swal.fire({
                    title: 'Selected record(s) will be deleted.',
                    text: "Do you confirm?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Continue'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.formDelete.selected_ids = AdminLTEHelper.getTableSelectedRowIds(model);
                        this.submitDeleteForm();
                    }
                });
            },
            submitDeleteForm: function () {
                var self = this;
                self.$Progress.start();
                self.formDelete.post(AdminLTEHelper.getAPIURL((self.$attrs.model.toLowerCase()) + "/delete"))
                    .then(({ data }) => {
                        self.$Progress.finish();
                    }).catch(({ data }) => {
                        self.$Progress.fail();
                    }).finally(function(){
                        self.loadData(function(){
                            Vue.swal.fire({
                                toast: true,
                                position: 'top-end',
                                title: '',
                                text: 'Selected records have been deleted.',
                                icon: 'success',
                                showConfirmButton: false,
                                timer: 2000,
                                timerProgressBar: true,
                            });
                            let eventName = "refresh" + self.$attrs.model + "Data";
                            self.$root.$emit(eventName);
                        });
                    });
            }
        },
        mounted() {
            this.main_folder = AdminLTEHelper.getMainFolder();

            var self = this;
            this.loadData(function(){
                AdminLTEHelper.setDefaultSortButton("button_sort_" + self.$attrs.model + "_id");
            });

            this.$nextTick(() => {
                this.$root.$emit("widget-rendered", this.model, "recordlist");
            });
        },
        updated() {
            
        }
    }
</script>