<template>
    <div class="content-wrapper">
        <server-error v-if="page.has_server_error" ></server-error>
        <permission-error v-else-if="!page.authorization.status" :authorization="page.authorization"></permission-error>
        <div v-else>
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>{{ $t("Logs") }}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="home">{{ $t('Home') }}</a></li>
                            <li class="breadcrumb-item"><a href="configuration">{{ $t('Configuration') }}</a></li>
                            <li class="breadcrumb-item active">{{ $t("Logs") }}</li>
                        </ol>
                    </div>
                    </div>
                </div>
            </section>
            <section class="content">
                <div class="container-fluid">
                    <div class="card recordlist-card">
                        <div class="card-header">
                            <h3 class="card-title">{{ $t("Logs") }}</h3>
                        </div>
                        <div class="card-body">
                            <div style="height:40px">
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
                                            <button type="button" class="btn btn-default float-right" @click="showFilterModal">
                                                <i class="fas fa-filter text-primary"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="containerFilterButtons" style="margin-top: 10px;text-align: right;">
                                <div v-for="(btn,index) in filter_buttons" :key="index" v-html="btn" style="display:inline-block;">
                                </div>                
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover table-sm">
                                    <thead>
                                        <tr>
                                            <th>
                                                <button type="button"
                                                    id="button_sort_WisiloLog_updated_at"
                                                    class="button-table-sort"
                                                    @click="sort('created_at')">
                                                    <span>{{ $t("Time") }}</span>&nbsp;
                                                    <span class="sorting loading">
                                                        <img class="imgLoader" src="/img/wisilo/loader.svg" width="14" height="14"/>
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
                                            
                                            <th>
                                                <button type="button"
                                                    id="button_sort_WisiloLog_type"
                                                    class="button-table-sort"
                                                    @click="sort('type')">
                                                    <span>{{ $t("Type") }}</span>&nbsp;
                                                    <span class="sorting loading">
                                                        <img class="imgLoader" src="/img/wisilo/loader.svg" width="14" height="14"/>
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

                                            <th>
                                                <button type="button"
                                                    id="button_sort_WisiloLog_title"
                                                    class="button-table-sort"
                                                    @click="sort('title')">
                                                    <span>{{ $t("Record") }}</span>&nbsp;
                                                    <span class="sorting loading">
                                                        <img class="imgLoader" src="/img/wisilo/loader.svg" width="14" height="14"/>
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

                                            <th>
                                                <span>{{ $t("User") }}</span>&nbsp;
                                            </th>

                                            <th class="text-center">
                                                <span>{{ $t("Detail") }}</span>&nbsp;
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody id="'tbodyWisiloLogRecordList'">
                                        <tr v-for="row in list" :key="row.id">
                                            <td v-html="row.process_time"></td>
                                            <td v-html="row.type_table_text"></td>
                                            <td v-html="row.record"></td>
                                            <td v-html="row.user_text"></td>
                                            <td class="text-center">
                                                <button type="button"
                                                    class="btn-icon btn-icon-primary"
                                                    @click="show_log(row.id)"
                                                    :id="row.id"
                                                    style="margin-bottom:0;">
                                                    <span class="btn-label btn-label-right">
                                                        <i class="fas fa-eye"></i>
                                                    </span>                    
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div style="min-height:60px;">
                                <pagination v-if="show_pagination" :data="data" :limit="1" align="right" :show-disabled="false" @pagination-change-page="paginate">
                                    <span slot="prev-nav">&lt;</span>
                                    <span slot="next-nav">&gt;</span>
                                </pagination>
                            </div>
                        </div>            
                    </div>    
                </div>
            </section>
        </div>

        <div class="modal fade" id="modal-Filter" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{ $t('Filters') }}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" style="min-height:350px;">
                        <div class="form-group row">
                            <label for="FormFilter_created_at__start__" class="detail-label col-form-label col-sm-12" style="padding-bottom:0;">
                                {{ $t('Created At') }}
                            </label>
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <span>{{ $t('Start') }}</span>
                                <br>
                                <input type="date"
                                    v-model="FormFilter.created_at__start__"
                                    class="form-control"
                                    id="FormFilter_created_at__start__"
                                    name="FormFilter_created_at__start__">
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <span>{{ $t('End') }}</span>
                                <br>
                                <input type="date"
                                    v-model="FormFilter.created_at__end__"
                                    class="form-control"
                                    id="FormFilter_created_at__end__"
                                    name="FormFilter_created_at__end__">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-12">
                                <label for="FormFilter_user" class="detail-label col-form-label" style="padding-bottom:0;">
                                    {{  $t('User') }}
                                </label>
                                <select2-element class="select2-element"
                                    multiple="multiple"
                                    id="FormFilter_user"
                                    name="FormFilter_user"
                                    :options="user_options"
                                    v-model="FormFilter.user">
                                </select2-element>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-12">
                                <label for="FormFilter_type" class="detail-label col-form-label" style="padding-bottom:0;">
                                    {{  $t('Type') }}
                                </label>
                                <select2-element class="select2-element"
                                    multiple="multiple"
                                    id="FormFilter_type"
                                    name="FormFilter_type"
                                    :options="type_options"
                                    v-model="FormFilter.type">
                                </select2-element>
                            </div>
                        </div>
                        
                    </div>
                    <div class="modalfooter justify-content-between">
                        <div class="row">
                            <div class="col-md-12">
                                <button type="button"
                                    @click="saveFormFilter"
                                    class="btn btn-success float-right">
                                    {{ $t('Filter') }}
                                </button>
                                <button type="button" class="btn btn-outline-secondary float-right" data-dismiss="modal" style="margin-right:10px;">{{ $t('Close') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modal-Log" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="model-log-title" v-html="logDetail.type_table_text"></div>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" style="min-height:350px;">
                        <div class="row" id="log-detail-text">
                            <div class="col-lg-6">
                                <b>{{ $t('Log Id')}} :</b> <span v-html="logDetail.id"></span>
                            </div>
                            <div class="col-lg-6">
                                <b>{{ $t('Record')}} :</b> <span v-html="logDetail.record"></span>
                            </div>
                            <div class="col-lg-6">
                                <b>{{ $t('User :')}}</b> <span v-html="logDetail.user_text"></span>
                            </div>
                            <div class="col-lg-6">
                                <b>{{ $t('Time :')}}</b> <span v-html="logDetail.process_time"></span>
                            </div>
                            <div class="col-lg-12">
                                <b>{{ $t('Message')}} :</b> <span v-html="logDetail.message"></span>
                            </div>
                        </div>   
                        <div class="row mt-5">
                            <div class="col-lg-12">
                                <div class="table-responsive">
                                    <table id="tableLogDetail" class="table table-striped table-bordered table-hover table-sm"></table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modalfooter justify-content-between">
                        <div class="row">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-outline-secondary float-right" data-dismiss="modal" style="margin-right:10px;">{{ $t('Close') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <input type="hidden" id="controller" value="wisilologs">
    </div>
</template>

<script>

export default {
    data() {
        return {
            main_folder: '',
            data: {},
            list: [],
            search_text: '',
            current_page: 1,
            show_pagination: false,
            sort_variable: 'id',
            sort_direction: 'desc',            
            page: {
                is_ready: false,
                has_server_error: false,
                variables: [],
                authorization: {
                    status: true,
                    type: "",
                    msg: ""
                },
                is_data_loading: false,
                is_data_loaded: false,
                is_filter_options_loading: false,
                is_filter_options_loaded: false,
            },
            user_options: [],
            type_options: [],
            FormFilter: new Form({
                'created_at__start__': '',
                'created_at__end__': '',
                'user': [],
                'type': []
            }),
            filter_data: '',
            filter_buttons: [],
            body_loader_active: false,
            logDetail: new Form({
                'id':'',
                'record': '',
                'user_text': '',
                'process_time': '',
                'type_table_text': '',
                'message': '',
            }),
        };
    },
    methods: {
        show_log: function(id) {
            var logData = this.getLogDataById(id);
            this.logDetail.fill(logData);
            this.createLogTable(logData);

            $('#modal-Log').modal();
        },
        getLogDataById: function(id) {
            var logData = [];
            this.list.forEach(log => {
                if (id == log.id) {
                    logData = log;
                }                        
            });

            return logData;
        },
        createLogTable: function(logData) {
            var self = this;
            var Log = logData;
        
            if ("INSERT" == Log["type"]) {
                self.createInsertLogTable(Log);
            } else if ("UPDATE" == Log["type"]) {
                self.createUpdateLogTable(Log);
            } else if ("DELETE" == Log["type"]) {
                self.createDeleteLogTable(Log);
            } else {
                document.getElementById("tableLogDetail").innerHTML = "";
            }
        },
        createInsertLogTable: function(Log) {
            var self = this;
            var theadHTML = ""
                + "<thead>"
                    + "<tr>"
                        + "<th>Field</th>"
                        + "<th>Value</th>"
                    + "</tr>"
                + "</thead>";
        
            var objLog = JSON.parse(Log["object_new_values"]);
            var tbodyHTML = "";
            var trHTML = "";
        
            Object.keys(objLog).map(function(key) {
                trHTML = ''
                    + '<tr>'
                        + '<th>' + self.getTitle(key) + '</th>'
                        + '<td>' + objLog[key] + '</td>'
                    + '</tr>';
        
                tbodyHTML += trHTML;
            });
        
            tbodyHTML = "<tbody>" + tbodyHTML + "</tbody>";
        
            document.getElementById("tableLogDetail").innerHTML = theadHTML + tbodyHTML;
        },
        createUpdateLogTable: function(Log) {
            var self = this;

            var theadHTML = ""
                + "<thead>"
                    + "<tr>"
                        + "<th>Field</th>"
                        + "<th>Old Value</th>"
                        + "<th>New Value</th>"
                    + "</tr>"
                + "</thead>";

            var logData_OLD = JSON.parse(Log["object_old_values"]);
            var logData_NEW = JSON.parse(Log["object_new_values"]);

            var differentData = [];
            var objPair = {};
        
            Object.keys(logData_OLD).map(function(key) {
                if (undefined !== logData_NEW[key]) {
                    if (logData_OLD[key] != logData_NEW[key]) {
                        objPair = {};
                        objPair["first"] = logData_OLD[key];
                        objPair["last"] = logData_NEW[key];
                        differentData[key] = objPair;
                    }
                } else {
                    objPair = {};
                    objPair["first"] = logData_OLD[key];
                    objPair["last"] = "-";
                    differentData[key] = objPair;
                }
            });
        
            Object.keys(logData_NEW).map(function(key) {
                if (undefined === differentData[key]) {
                    if (undefined !== logData_OLD[key]) {
                        if (logData_NEW[key] != logData_OLD[key]) {
                            objPair = {};
                            objPair["first"] = logData_OLD[key];
                            objPair["last"] = logData_NEW[key];
                            differentData[key] = objPair;
                        }
                    } else {
                        objPair = {};
                        objPair["first"] = "-";
                        objPair["last"] = logData_NEW[key];
                        differentData[key] = objPair;
                    }
                }
            });
        
            var tbodyHTML = "";
            var trHTML = "";
            
            Object.keys(differentData).map(function(key) {
                trHTML = ''
                    + '<tr>'
                        + '<th>' + self.getTitle(key) + '</th>'
                        + '<td>' + differentData[key]["first"] + '</td>'
                        + '<td>' + differentData[key]["last"] + '</td>'
                    + '</tr>';
        
                tbodyHTML += trHTML;
            });
            
            tbodyHTML = "<tbody>" + tbodyHTML + "</tbody>";
            
            document.getElementById("tableLogDetail").innerHTML = theadHTML + tbodyHTML;
        },
        createDeleteLogTable: function(Log) {
            var self = this;

            var theadHTML = ""
                + "<thead>"
                    + "<tr>"
                        + "<th>Field</th>"
                        + "<th>Value</th>"
                    + "</tr>"
                + "</thead>";
        
            var objLog = JSON.parse(Log["object_old_values"]);
            var tbodyHTML = "";
            var trHTML = "";
        
            Object.keys(objLog).map(function(key) {
                trHTML = ''
                    + '<tr>'
                        + '<th>' + self.getTitle(key) + '</th>'
                        + '<td>' + objLog[key] + '</td>'
                    + '</tr>';
            
                tbodyHTML += trHTML;
            });
        
            tbodyHTML = "<tbody>" + tbodyHTML + "</tbody>";
            
            document.getElementById("tableLogDetail").innerHTML = theadHTML + tbodyHTML;
        },
        getTitle: function(key) {
            /* if (undefined !== titles[key]) {
                return titles[key];
            } */
        
            return key;
        },
        showFilterModal: function () {
            $('#modal-Filter').modal();
        },
        saveFormFilter: function() {
            var self = this;

            $('#modal-Filter').modal("hide");
            self.body_loader_active = true;

            var filter_data = {};
            if ("" != self.FormFilter.created_at__start__) {
                filter_data['created_at__start__'] = self.FormFilter.created_at__start__;
            }

            if ("" != self.FormFilter.created_at__end__) {
                filter_data['created_at__end__'] = self.FormFilter.created_at__end__;
            }

            if (self.FormFilter.user.length > 0) {
                filter_data['user'] = self.FormFilter.user;
            }

            if (self.FormFilter.type.length > 0) {
                filter_data['type'] = self.FormFilter.type;
            }

            self.filter_data = btoa(JSON.stringify(filter_data));

            self.loadData(function() {
                self.body_loader_active = false;
            });
        },
        removeFilter: function(btn) {
            var self = this;
            var filterToken = btn.getAttribute("data-filter-token");
            var filterValue = btn.getAttribute("data-filter-value");
            var newSelectedFilters = [];
            var currentSelectedFilters = [];
            
            switch (filterToken) {
                case 'created_at__start__':
                    self.FormFilter.created_at__start__ = "";
                    break;
                case 'created_at__end__':
                    self.FormFilter.created_at__end__ = "";
                    break;
                case 'user':
                    newSelectedFilters = [];
                    currentSelectedFilters = self.FormFilter.user;

                    currentSelectedFilters.forEach(filter_val => {
                        if (filter_val != filterValue) {
                            newSelectedFilters.push(filter_val);
                        }                        
                    });

                    self.FormFilter.user = newSelectedFilters;
                    break;
                case 'type':
                    newSelectedFilters = [];
                    currentSelectedFilters = self.FormFilter.type;

                    currentSelectedFilters.forEach(filter_val => {
                        if (filter_val != filterValue) {
                            newSelectedFilters.push(filter_val);
                        }
                    });

                    self.FormFilter.type = newSelectedFilters;
                    break;
            }

            self.saveFormFilter();
        },
        clearFilter: function() {
            var self = this;
            self.FormFilter.created_at__start__ = "";
            self.FormFilter.created_at__end__ = "";
            self.FormFilter.user = [];
            self.FormFilter.type = [];
            self.saveFormFilter();
        },
        initializeFilter: function() {
            var self = this;

            self.$el.querySelectorAll(".btn-filter").forEach(btn => {
                btn.addEventListener('click', () => {
                    self.removeFilter(btn);
                });
            });

            self.$el.querySelectorAll(".btn-clear-filter").forEach(btn => {
                btn.addEventListener('click', () => {
                    self.clearFilter();
                });
            });
        },
        processLoadQueue: function () {
            var self = this;
            
            if (self.page.has_server_error) {
                self.$Progress.finish();
                self.page.is_ready = true;
                return;
            }

            if (!self.page.authorization.status) {
                self.$Progress.finish();
                self.page.is_ready = true;
                return;
            }

            if (!self.page.is_variables_loaded && !self.page.is_filter_options_loaded) {
                self.$Progress.start();
            }

             if (!self.page.is_variables_loaded) {
                self.loadPageVariables();
            } else {
                if (!self.page.is_filter_options_loaded) {
                    self.load_filter_options();
                } else {
                    if (self.page.is_data_loaded) {
                        self.page.is_ready = true;
                    } else {
                        self.loadData(function(){
                            WisiloHelper.setDefaultSortButton("button_sort_WisiloLog_id");
                        });
                    }
                }
            }
        },
        loadPageVariables: function () {
            var self = this;

            if (self.page.is_variables_loading) {
                return;
            }

            self.page.is_variables_loading = true;

            axios.get(WisiloHelper.getAPIURL("wisilo/get_page_variables/configuration"))
                .then(({ data }) => {
                    self.page.is_variables_loaded = true;
                    self.page.is_variables_loading = false;
                    self.page.variables = data;
                }).catch(({ data }) => {
                    self.page.is_variables_loaded = true;
                    self.page.is_variables_loading = false;
                    self.$Progress.fail();
                    self.page.has_server_error = true;
                    self.processLoadQueue();
                }).finally(function() {
                   WisiloHelper.initializePermissions(self.page.variables, true);
                   self.page.authorization = WisiloHelper.isUserAuthorized(self.page.variables, "configuration");
                   self.processLoadQueue();
                });
        },
        load_filter_options: function () {
            var self = this;
            if (self.page.is_filter_options_loading) {
                return;
            }

            self.page.is_filter_options_loading = true;
			
			axios.get(WisiloHelper.getAPIURL("wisilologs/get_filter_options"))
                .then(({ data }) => {
                    self.page.is_filter_options_loaded = true;
                    self.page.is_filter_options_loading = false;
                    self.user_options = data.options['user'];
                    self.type_options = data.options['type'];
                    self.processLoadQueue();
                }).catch(({ data }) => {
                    self.page.is_filter_options_loaded = true;
                    self.page.is_filter_options_loading = false;
                    self.$Progress.fail();
                    self.processLoadQueue();
                });
        },
        loadData: function (callback) {
            var self = this;
            var filter = (null === self.filter_data) ? '' : self.filter_data;
            var query = WisiloHelper.getURLQuery(self);
            axios.get(WisiloHelper.getAPIURL("wisilologs/get_recordlist/" + query + "&f=" + filter))
                .then(({ data }) => {
                    self.data = data;
                    /* self.titles = self.widget_options.table_header.titles;
                    self.variables = self.widget_options.table_header.variables; */
                    self.list = data.data.list;
                    self.filter_data = data.filter_data;
                    self.filter_buttons = data.filter_buttons;
                    self.show_pagination = data.show_pagination;
                }).catch(({ data }) => {
                    self.$Progress.fail();
                }).finally(function() {
                    callback();
                    self.$root.$emit("projelist-rendered");
                    self.initializeFilter();
                });
        },
        search_list: _.debounce(function (e) {
            var search_input = e.target;
            
            WisiloHelper.activateSearchLoader(search_input);

            this.search_text = search_input.value;
            this.current_page = 1;

            this.loadData(function(){
                WisiloHelper.deactivateSearchLoader(search_input)
            });
        }, 1000),
        paginate: function (page = 1) {
            this.current_page = page;
            this.loadData(function(){});
        },
        sort: function (variable) {
            WisiloHelper.activateSortLoader(`button_sort_WisiloLog_${variable}`);

            this.sort_variable = variable;
            this.sort_direction = ('asc' == this.sort_direction) ? 'desc' : 'asc';
            this.current_page = 1;

            var self = this;
            this.loadData(function() {
                WisiloHelper.deactivateSortLoader(`button_sort_WisiloLog_${self.sort_variable}`, self.sort_direction)
            });
        },
        select_all_row: function(target) {
            WisiloHelper.doCheckAllCheckboxClick(target);
        },
        select_row: function(target) {
            WisiloHelper.doCheckboxClick(target);
        }
    },
    mounted() {
        var self = this;
        self.main_folder = WisiloHelper.getMainFolder();
        
        self.processLoadQueue();
    }
}
</script>