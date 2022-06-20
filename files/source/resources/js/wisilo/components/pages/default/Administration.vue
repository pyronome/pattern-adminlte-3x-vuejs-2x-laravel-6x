<template>
    <div class="content-wrapper">
        <server-error v-if="page.has_server_error" ></server-error>
        <permission-error v-else-if="!page.authorization.status" :authorization="page.authorization"></permission-error>
        <div v-else>
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <!-- <div class="col-sm-6">
                            <h1>{{ $t("Home") }}</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item active">{{ $t("Home") }}</li>
                            </ol>
                        </div> -->
                    </div>
                </div>
            </section>
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12">
                            <form id="LayoutForm"
                                class=""
                                @submit.prevent="submitLayoutForm"
                                @keydown="LayoutForm.onKeydown($event)">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">{{ $t("Layout") }}</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                           
                                            <div class="form-group col-lg-6 col-md-6 col-xs-12">
                                                <label for="LayoutForm_source_group_id" class="detail-label">{{  $t('Source User Group') }}  </label>
                                                <select id="LayoutForm_source_group_id">
                                                    <option value="0">{{ $t("Please select..") }}</option>
                                                    <option v-for="(data,index) in impersonation_users" :key="index" :value="data.group_id" v-html="data.group_title">
                                                    </option>
                                                </select>
                                            </div>
                                            <div class="form-group col-lg-6 col-md-6 col-xs-12">
                                                <label for="LayoutForm_target_group_id" class="detail-label">{{  $t('Target User Group') }}  </label>
                                                <select id="LayoutForm_target_group_id">
                                                    <option value="0">{{ $t("Please select..") }}</option>
                                                    <option v-for="(data,index) in impersonation_users" :key="index" :value="data.group_id" v-html="data.group_title">
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-lg-12">
                                                <table class="table table-striped table-bordered table-hover table-sm">
                                                    <thead>
                                                        <tr>
                                                            <th style="width:30px;">
                                                                <div class="icheck-primary m-0">
                                                                    <input type="checkbox"
                                                                        @click="select_all_widget($event.target)"
                                                                        id="select_all_widget"
                                                                        class="select_all_widget">
                                                                    <label for="select_all_widget"></label>
                                                                </div>
                                                            </th>
                                                            <th colspan="2">
                                                                <span>{{ $t('Widgets') }}</span>&nbsp;
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="tbodyWidgetList">
                                                        <tr v-for="(element, index) in source_widgets" :key="index"
                                                            :data-search-text="element.title + element.name">
                                                            <td>
                                                                <div class="icheck-primary m-0">
                                                                    <input type="checkbox"
                                                                        :id="'select_widget-' + element.id"
                                                                        class="select_widget"
                                                                        :data-widget-id="element.id">
                                                                    <label :for="'select_widget-' + element.id"></label>
                                                                </div>
                                                            </td>
                                                            <td style="width:200px;">
                                                                <span><b>{{element.pagename}}</b></span>
                                                            </td>
                                                            <td>
                                                                <span v-html="element.title + ' (' + element.widget + ')'"></span>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="card-footer ">
                                        <button type="button"
                                            class="btn btn-success btn-md btn-on-table float-right"
                                            @click="submitLayoutForm"
                                            :disabled="LayoutForm.busy">
                                            {{ $t('Copy Widgets') }}
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <input type="hidden" id="controller" :value="pagename">
        <body-loader :body_loader_active="body_loader_active" class="content-wrapper bodyLoader"></body-loader>
    </div>
</template>

<script>

export default {
    data() {
        return {
            main_folder: '',
            pagename: '',
            source_widgets: [],
            LayoutForm: new Form({
                'source_group_id': 0,
                'target_group_id': 0,
                'selected_widget_ids': [],
            }),
            page: {
                is_ready: false,
                has_server_error: false,
                has_post_error: false,
                post_message: "",
                variables: [],
                authorization: {
                    status: true,
                    type: "",
                    msg: ""
                },
                is_variables_loading: false,
                is_variables_loaded: false,
                is_source_widgets_loading: false,
                is_source_widgets_loaded: false,
                external_files: [
                    ("/js/wisilo/select2/dist/js/select2.min.js"),
                ],
            },
            body_loader_active: false
        };
    },
    methods: {
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

            if (!self.page.is_variables_loaded 
                && !self.page.is_source_widgets_loaded) {
                self.$Progress.start();
            }

            if (!self.page.is_variables_loaded) {
                self.loadPageVariables();
            } else {
                if (!self.page.is_source_widgets_loaded) {
                    self.load_source_widgets();
                } else {
                    self.$nextTick(function () {
                        setTimeout(function() {
                            self.initializePage();
                            self.body_loader_active = false;
                        }, 500);                        
                    });
                    
                    self.$Progress.finish();
                    self.page.is_ready = true;
                }
            }
        },
        initializePage: function () {
            var self = this;

            self.initailizeSelect2();
        },
        initailizeSelect2(){
            var self = this;

            $("#LayoutForm_source_group_id").select2();
            $("#LayoutForm_source_group_id").off("change").on("change", function () {
                self.load_source_widgets();
            });

            $("#LayoutForm_target_group_id").select2();

            /* $(document.getElementById(selectId)).select2({
                ajax: {
                    url: "ajaxfile.php",
                    type: "post",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            searchTerm: params.term // search term
                        };
                    },
                    processResults: function (response) {
                        return {
                            results: response
                        };
                    },
                    cache: true
                }
            }); */
        },
        loadPageVariables: function () {
            var self = this;

            if (self.page.is_variables_loading) {
                return;
            }

            self.page.is_variables_loading = true;

            axios.get(WisiloHelper.getAPIURL("wisilo/get_page_variables/" + self.pagename))
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
                   self.page.authorization = WisiloHelper.isUserAuthorized(self.page.variables, self.pagename);
                   self.processLoadQueue();
                });
        },
        load_source_widgets: function () {
            var self = this;

            if (self.page.is_source_widgets_loading) {
                return;
            }

            self.page.is_source_widgets_loading = true;
            var source_group_id = document.getElementById("LayoutForm_source_group_id").value;
            
            axios.get(WisiloHelper.getAPIURL("wisilo/get_source_widgets/" + source_group_id))
                .then(({ data }) => {
                    self.page.is_source_widgets_loaded = true;
                    self.page.is_source_widgets_loading = false;
                    self.source_widgets = data.source_widgets;
                    self.processLoadQueue();
                }).catch(({ data }) => {
                    self.page.is_source_widgets_loaded = true;
                    self.page.is_source_widgets_loading = false;
                    self.$Progress.fail();
                    self.page.has_server_error = true;
                    self.processLoadQueue();
                });
        },
        select_all_widget: function(sender) {
            $("#tbodyWidgetList > tr > td > div > .select_widget").prop("checked", sender.checked);
        },
        submitLayoutForm: function () {
            var self = this;
            self.$Progress.start();

            self.LayoutForm.source_group_id = document.getElementById("LayoutForm_source_group_id").value;
            self.LayoutForm.target_group_id = document.getElementById("LayoutForm_target_group_id").value;
            self.LayoutForm.selected_widget_ids = [];

            var tbodyElement = document.getElementById("tbodyWidgetList");
            var selectedCheckboxes = $(".select_widget:checked", tbodyElement);
            var selectedCount = selectedCheckboxes.length;
            var selectedWidgets = [];
            for (var i = 0; i < selectedCount; i++) {
                selectedWidgets.push(selectedCheckboxes[i].getAttribute("data-widget-id"));
            }

            self.LayoutForm.selected_widget_ids = selectedWidgets;
            
            self.LayoutForm.post(WisiloHelper.getAPIURL("wisilo/post_copy_widgets"))
                .then(({ data }) => {
                    self.$Progress.finish();
                    self.page.has_post_error = data.has_error;
                    self.page.post_message = data.error_msg;
                    self.page.has_server_error = false;
                }).catch(({ data }) => {
                    self.$Progress.fail();
                    Vue.swal.fire({
                        position: 'top-end',
                        title: self.$t("An error occurred while processing your request."),
                        icon: 'error',
                        showConfirmButton: false,
                        timer: 10000,
                        timerProgressBar: true
                    });
                }).finally(function() {
                    if (!self.page.has_server_error) {
                        if (!self.page.has_post_error) {
                            Vue.swal.fire({
                                position: 'top-end',
                                title: self.$t("Your changes have been saved!"),
                                icon: 'success',
                                showConfirmButton: false,
                                timer: 2000,
                                timerProgressBar: true,
                                onClose: () => {
                                    window.location.reload()
                                }
                            });
                        } else {
                            Vue.swal.fire({
                                position: 'top-end',
                                title: self.page.post_message,
                                icon: 'error',
                                showConfirmButton: false,
                                timer: 10000,
                                timerProgressBar: true
                            });
                        }
                    }
                });
        },
        
    },
    mounted() {
        var self = this;
        self.body_loader_active = true;
        self.main_folder = WisiloHelper.getMainFolder();
        var pagename = WisiloHelper.getPagename();
        self.pagename = ('' != pagename) ? pagename : 'home';
        self.page.is_ready = false;

        WisiloHelper.loadExternalFiles(
            self.page.external_files, 
            self.processLoadQueue()
        );
    }
}
</script>