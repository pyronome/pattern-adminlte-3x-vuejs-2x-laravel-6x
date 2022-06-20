<template>
    <div class="content-wrapper">
        <server-error v-if="page.has_server_error" ></server-error>
        <permission-error v-else-if="!page.authorization.status" :authorization="page.authorization"></permission-error>
        <div v-else>
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-12">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><router-link :to="'/' + main_folder + '/home'">{{ $t('Home') }}</router-link></li>
                                <li class="breadcrumb-item"><router-link :to="'/' + main_folder + '/wisilousergroup'">{{ $t("WisiloUserGroup List") }}</router-link></li>
                                <li class="breadcrumb-item active" v-html="this.LayoutForm.title"></li>
                            </ol>
                        </div>
                    </div>
                </div>
            </section>
            <section class="content">
                <div class="container-fluid">
                    <form id="LayoutForm"
                        class=""
                        @submit.prevent="submitForm"
                        @keydown="LayoutForm.onKeydown($event)">

                        <input type="hidden" v-model="LayoutForm.usergroup_id" id="usergroup_id" name="usergroup_id">
                        <input type="hidden" v-model="LayoutForm.title" id="title" name="title">
                        <input type="hidden" v-model="LayoutForm.selected_pages" id="selected_pages" name="selected_pages">
                        <input type="hidden" v-model="LayoutForm.layout_data" id="layout_data" name="layout_data">

                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="mb-0">
                                            {{  $t('Layout Settings') }}
                                        </h4>
                                        <h6 class="mb-0 text-muted">
                                            {{  $t('Layout Settings') }}
                                        </h6>
                                        <button type="button"
                                            @click="showLayoutPageSelection"
                                            class="btn btn-primary btn-sm text-white float-right"
                                            style="position: absolute;right:1.25rem;top:0.75rem;">
                                            <i class="fas fa-plus"></i>&nbsp;{{ $t('Add / Drop') }}
                                        </button>
                                    </div>
                                    <div class="card-body">
                                        <div v-show="!show_page" class="row">
                                            <div class="callout callout-warning col-lg-12">
                                                <p>{{  $t('No records added yet.') }}</p>
                                            </div>
                                        </div>

                                        <div v-show="show_page" class="row">
                                            <div v-for="item in page_options" :key="item.id" :page-id="item.id"
                                                class="form-group col-lg-12 col-md-12 col-sm-12 div-page">
                                                <label class="detail-label" v-html="item.text"></label>
                                                <textarea type="text"
                                                    :id="'layout_page-' + item.id"
                                                    :data-page-id="item.id"
                                                    class="form-control layout_page_data" rows="4"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>      
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="card">
                                    <div class="action-buttons-container">
                                        <button type="submit"
                                            menu-permission-token="wisilousergroup"
                                            model-permission-token="WisiloUserGroup-update"
                                            :disabled="LayoutForm.busy"
                                            class="sbp-item sbp-hide btn btn-success btn-md btn-on-card float-right">
                                            {{ $t('Save') }}
                                        </button>
                                        <router-link tag="a"
                                            class="btn btn-outline-secondary btn-md btn-on-card float-right"
                                            :to="backbuttonURL" style="margin-right:10px;">
                                            <span>{{ $t('Cancel') }}</span>
                                        </router-link>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
            </section>

            <div class="modal fade" id="modal-selectPage" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-md">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">{{ $t('Page Selection') }}</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body" style="min-height:350px;">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <ul class="ul-selection ul-highlighted">
                                        <li v-for="item in page_options" :key="item.id" v-on:click="toggleSelect('btn-select-page-' + item.id)"
                                            class="li-page-selection" :data-searchtext="item.text">
                                            <button type="button"
                                                class="btn-confirmation-checkmark"
                                                :id="'btn-select-page-' + item.id"
                                                :data-item-id="item.id"
                                                data-value="">
                                                <span class="empty">&#9634;</span>
                                                <span class="checked">&check;</span>
                                            </button>
                                            <span v-html="item.text"></span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="modalfooter justify-content-between show_by_permission_must_update">
                            <div class="row">
                                <div class="col-md-12">
                                    <button type="button"
                                        id="button-savePageSelection"
                                        name="button-savePageSelection"
                                        @click="savePageSelection"
                                        class="btn btn-success float-right">
                                        {{ $t('Save') }}
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary float-right" data-dismiss="modal" style="margin-right:10px;">{{ $t('Close') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" id="controller" value="wisilousergroup">
        <body-loader :body_loader_active="body_loader_active" class="content-wrapper bodyLoader"></body-loader>
    </div>
</template>

<script>
export default {
    data() {
        return {
            main_folder: '',
            pagename: '',
            usergroup_id: 0,
            page_options: [],
            show_page: false,
			LayoutForm: new Form({
                'debug_mode': false,
                'usergroup_id': 0,
                'title': '',
                'selected_pages': [],
                'layout_data': ''
            }),
            page: {
                is_ready: false,
                has_server_error: false,
                variables: [],
                authorization: {
                    status: true,
                    type: "",
                    msg: ""
                },
                is_variables_loading: false,
                is_variables_loaded: false,
                has_post_error: false,
                post_error_msg: '',
                is_ready: false,
                is_data_loading: false,
                is_data_loaded: false
            },
            search_permission: '',
            body_loader_active: false,
        };
    },
    computed: {
        backbuttonURL() {
            let URL = '/' + this.main_folder + '/wisilousergroup';
            if (this.usergroup_id > 0) {
                URL = URL + '/detail/' + this.usergroup_id;
            }
            return URL;
        }
    },
    methods: {
        showLayoutPageSelection: function () {
            $(".li-page-selection > .btn-confirmation-checkmark").attr("data-value", "");

            var selected_ids = this.LayoutForm.selected_pages;
            selected_ids.forEach(id => {
                $('#btn-select-page-' + id).attr("data-value", "Y");
            });

            $('#modal-selectPage').modal();
        },
        savePageSelection: function() {
            var selected_ids = [];

            this.$el.querySelectorAll(".li-page-selection > .btn-confirmation-checkmark[data-value='Y']").forEach(button => {
                selected_ids.push(button.getAttribute("data-item-id"));
            });

            this.LayoutForm.selected_pages = selected_ids;
            
            if (this.LayoutForm.selected_pages.length > 0) {
                this.show_page = true;
            } else {
                this.show_page = false;
            }

            this.showSelectedPages();
            $('#modal-selectPage').modal("hide");
        },
        showSelectedPages: function () {
            $(".div-page").hide();

            var selected_ids = this.LayoutForm.selected_pages;
            selected_ids.forEach(page_id => {
                $(".div-page[page-id='" + page_id + "']").show();
            });
        },
        updateLayoutPageData: function () {
            if (this.LayoutForm.selected_pages.length > 0) {
                this.LayoutForm.layout_data.forEach(item => {
                    document.getElementById("layout_page-" + item.id).value = item.value;
                });
            }
        },
        toggleSelect: function (button_id) {
            let button = document.getElementById(button_id);

            if ("Y" == button.getAttribute("data-value")) {
                button.setAttribute("data-value", "");
            } else {
                button.setAttribute("data-value", "Y");
            }
        },
        processLoadQueue: function () {
            if (this.page.has_server_error) {
                this.$Progress.finish();
                this.page.is_ready = true;
                return;
            }

            if (!this.page.authorization.status) {
                this.$Progress.finish();
                this.page.is_ready = true;
                return;
            }

            if (!this.page.is_variables_loaded && !this.page.is_page_options_loaded && !this.page.is_data_loaded) {
                this.$Progress.start();
            }
            
            if (!this.page.is_variables_loaded) {
                this.loadPageVariables();
            } else {
                if (!this.page.is_page_options_loaded) {
                    this.load_page_options();
                }

                if (this.page.is_data_loaded) {
                    this.$nextTick(function () {
                        var self = this;

                        setTimeout(function() {
                            self.initializePage();
                            self.body_loader_active = false;
                        }, 500);                        
                    });

                    this.$Progress.finish();
                    this.page.is_ready = true;
                } else {
                    this.loadData();
                }
            }
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
                   WisiloHelper.initializePermissions(self.page.variables, false);

                   let authorization = {};
                   if ("new" == self.id) {
                       authorization = WisiloHelper.isUserAuthorized(self.page.variables, self.pagename, 'wisilousergroup', 'create');
                   } else {
                       authorization = WisiloHelper.isUserAuthorized(self.page.variables, self.pagename, 'wisilousergroup', 'read');
                       if (authorization.status) {
                           authorization = WisiloHelper.isUserAuthorized(self.page.variables, self.pagename, 'wisilousergroup', 'update');
                       }
                   }

                   self.page.authorization = authorization;
                   self.processLoadQueue();
                });
        },
        load_page_options: function () {
            if (this.page.is_page_options_loading) {
                return;
            }

            this.page.is_page_options_loading = true;
			
			axios.get(WisiloHelper.getAPIURL("__layout/get_layout_page_options"))
                .then(({ data }) => {
                    this.page.is_page_options_loaded = true;
                    this.page.is_page_options_loading = false;
                    this.page_options = data.list;
                    this.processLoadQueue();
                }).catch(({ data }) => {
                    this.page.is_page_options_loaded = true;
                    this.page.is_page_options_loading = false;
                    this.$Progress.fail();
                    this.processLoadQueue();
                });
        },
        loadData: function () {
            var self = this;
            if (self.page.is_data_loading) {
                return;
            }

            self.page.is_data_loading = true;

            axios.get(WisiloHelper.getAPIURL("wisilousergroup/get_layout_data/" + self.usergroup_id))
                .then(({ data }) => {
                    self.page.is_data_loaded = true;
                    self.page.is_data_loading = false;
                    if (self.usergroup_id > 0) {
                        self.LayoutForm.fill(data.form_data);
                    }
                    self.processLoadQueue();
                }).catch(({ data }) => {
                    self.page.is_data_loaded = true;
                    self.page.is_data_loading = false;
                    self.$Progress.fail();
                    self.page.has_server_error = true;
                    self.processLoadQueue();
                });
        },
        initializePage: function () {
            var self = this;
 
            self.showSelectedPages();
            self.updateLayoutPageData();

            if (self.LayoutForm.selected_pages.length > 0) {
                self.show_page = true;
            } else {
                self.show_page = false;
            }
        },
        collectLayoutData: function () {
            var layout_data = [];

            this.$el.querySelectorAll("textarea.layout_page_data").forEach(textarea => {
                let layout_page = {};
                layout_page["id"] = textarea.getAttribute("data-page-id");
                
                layout_page["value"] = '';
                if ('' != textarea.value) {
                    layout_page["value"] = textarea.value;
                }
                
                layout_data.push(layout_page);
            });

            this.LayoutForm.layout_data = layout_data;
        },
        submitForm: function () {
            var self = this;
            self.collectLayoutData();
            
            self.$Progress.start();
            self.LayoutForm.post(WisiloHelper.getAPIURL("wisilousergroup/post_layout_data"))
                .then(({ data }) => {
                    self.$Progress.finish();
                    self.id = data.id;
                    self.page.has_post_error = data.has_error;
                    self.page.post_error_msg = data.error_msg;
                    self.page.has_server_error = false;
                }).catch(({ data }) => {
                    self.$Progress.fail();
                    self.page.has_server_error = true;
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
                                    self.$router.push('/' + self.main_folder + '/wisilousergroup/detail/' + self.usergroup_id);
                                }
                            });
                        } else {
                            Vue.swal.fire({
                                position: 'top-end',
                                title: self.page.post_error_msg,
                                icon: 'error',
                                showConfirmButton: false,
                                timer: 10000,
                                timerProgressBar: true
                            });
                        }
                    }
                });
        }
    },
    mounted() {
        this.body_loader_active = true;
        this.main_folder = WisiloHelper.getMainFolder();
        this.pagename = WisiloHelper.getPagename();
        this.usergroup_id = parseInt(this.$route.params.id);    
        this.page.is_ready = false;
        this.processLoadQueue();
    }
}
</script>
