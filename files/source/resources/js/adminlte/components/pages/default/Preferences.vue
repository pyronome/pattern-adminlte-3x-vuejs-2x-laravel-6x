<template>
    <div class="content-wrapper">
        <server-error v-if="page.has_server_error" ></server-error>
        <permission-error v-else-if="!page.is_authorized" :type="page.unauthorized_type"></permission-error>
        <div v-else>
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark">{{ $t('Preferences') }}</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="home">{{ $t('Home') }}</a></li>
                                <li class="breadcrumb-item active">{{ $t('Preferences') }}</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <section class="content" v-show="page.is_ready">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12">
                            <div class="card">
                                <div class="card-header show_by_permission_must_update">
                                    <div class="card-tools">
                                    </div>
                                </div>
                                <form id="formPreferences" @submit.prevent="submitForm" @keydown="PreferencesForm.onKeydown($event)">
                                    <div class="card-body text-sm">
                                        <div class="row mb-10">
                                            <div class="col-lg-12 mb-1">
                                                <div class="icheck-primary d-inline">
                                                    <input type="checkbox"
                                                        id="formPreferences-main-header_border-bottom-0"
                                                        name="formPreferences-main-header_border-bottom-0"
                                                        class="">
                                                    <label for="formPreferences-main-header_border-bottom-0" class="detail-label">
                                                        {{ $t('No Navbar border') }}
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 mb-1">
                                                <div class="icheck-primary d-inline">
                                                    <input type="checkbox"
                                                        id="formPreferences-body_text-sm"
                                                        name="formPreferences-body_text-sm"
                                                        class="">
                                                    <label for="formPreferences-body_text-sm" class="detail-label">
                                                        {{ $t('Body small text') }}
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 mb-1">
                                                <div class="icheck-primary d-inline">
                                                    <input type="checkbox"
                                                        id="formPreferences-main-header_text-sm"
                                                        name="formPreferences-main-header_text-sm"
                                                        class="">
                                                    <label for="formPreferences-main-header_text-sm" class="detail-label">
                                                        {{ $t('Navbar small text') }}
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 mb-1">
                                                <div class="icheck-primary d-inline">
                                                    <input type="checkbox"
                                                        id="formPreferences-nav-sidebar_text-sm"
                                                        name="formPreferences-nav-sidebar_text-sm"
                                                        class="">
                                                    <label for="formPreferences-nav-sidebar_text-sm" class="detail-label">
                                                        {{ $t('Sidebar nav small text') }}
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 mb-1">
                                                <div class="icheck-primary d-inline">
                                                    <input type="checkbox"
                                                        id="formPreferences-main-footer_text-sm"
                                                        name="formPreferences-main-footer_text-sm"
                                                        class="">
                                                    <label for="formPreferences-main-footer_text-sm" class="detail-label">
                                                        {{ $t('Footer small text') }}
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 mb-1">
                                                <div class="icheck-primary d-inline">
                                                    <input type="checkbox"
                                                        id="formPreferences-nav-sidebar_nav-flat"
                                                        name="formPreferences-nav-sidebar_nav-flat"
                                                        class="">
                                                    <label for="formPreferences-nav-sidebar_nav-flat" class="detail-label">
                                                        {{ $t('Sidebar nav flat style') }}
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 mb-1">
                                                <div class="icheck-primary d-inline">
                                                    <input type="checkbox"
                                                        id="formPreferences-nav-sidebar_nav-legacy"
                                                        name="formPreferences-nav-sidebar_nav-legacy"
                                                        class="">
                                                    <label for="formPreferences-nav-sidebar_nav-legacy" class="detail-label">
                                                        {{ $t('Sidebar nav legacy style') }}
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 mb-1">
                                                <div class="icheck-primary d-inline">
                                                    <input type="checkbox"
                                                        id="formPreferences-nav-sidebar_nav-compact"
                                                        name="formPreferences-nav-sidebar_nav-compact"
                                                        class="">
                                                    <label for="formPreferences-nav-sidebar_nav-compact" class="detail-label">
                                                        {{ $t('Sidebar nav compact') }}
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 mb-1">
                                                <div class="icheck-primary d-inline">
                                                    <input type="checkbox"
                                                        id="formPreferences-nav-sidebar_nav-child-indent"
                                                        name="formPreferences-nav-sidebar_nav-child-indent"
                                                        class="">
                                                    <label for="formPreferences-nav-sidebar_nav-child-indent" class="detail-label">
                                                        {{ $t('Sidebar nav child indent') }}
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 mb-1">
                                                <div class="icheck-primary d-inline">
                                                    <input type="checkbox"
                                                        id="formPreferences-main-sidebar_sidebar-no-expand"
                                                        name="formPreferences-main-sidebar_sidebar-no-expand"
                                                        class="">
                                                    <label for="formPreferences-main-sidebar_sidebar-no-expand" class="detail-label">
                                                        {{ $t('Main Sidebar disable hover/focus auto expand') }}
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 mb-1">
                                                <div class="icheck-primary d-inline">
                                                    <input type="checkbox"
                                                        id="formPreferences-brand-link_text-sm"
                                                        name="formPreferences-brand-link_text-sm"
                                                        class="">
                                                    <label for="formPreferences-brand-link_text-sm" class="detail-label">
                                                        {{ $t('Brand small text') }}
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12"><h6>{{ $t('Navbar Variants') }}</h6></div>
                                            <div class="col-lg-12">
                                                <input type="hidden" id="formPreferences-navbar_variants" value="">
                                                <div id="container_navbar_variants" class="d-flex flex-wrap mb-3">
                                                    <div data-color="navbar-primary" data-header-type="navbar-dark" class="bg-primary elevation-2 preferences-button"></div>
                                                    <div data-color="navbar-secondary" data-header-type="navbar-dark" class="bg-secondary elevation-2 preferences-button"></div>
                                                    <div data-color="navbar-info" data-header-type="navbar-dark" class="bg-info elevation-2 preferences-button"></div>
                                                    <div data-color="navbar-success" data-header-type="navbar-dark" class="bg-success elevation-2 preferences-button"></div>
                                                    <div data-color="navbar-danger" data-header-type="navbar-dark" class="bg-danger elevation-2 preferences-button"></div>
                                                    <div data-color="navbar-indigo" data-header-type="navbar-dark" class="bg-indigo elevation-2 preferences-button"></div>
                                                    <div data-color="navbar-purple" data-header-type="navbar-dark" class="bg-purple elevation-2 preferences-button"></div>
                                                    <div data-color="navbar-pink" data-header-type="navbar-dark" class="bg-pink elevation-2 preferences-button"></div>
                                                    <div data-color="navbar-navy" data-header-type="navbar-dark" class="bg-navy elevation-2 preferences-button"></div>
                                                    <div data-color="navbar-lightblue" data-header-type="navbar-dark" class="bg-lightblue elevation-2 preferences-button"></div>
                                                    <div data-color="navbar-teal" data-header-type="navbar-dark" class="bg-teal elevation-2 preferences-button"></div>
                                                    <div data-color="navbar-cyan" data-header-type="navbar-dark" class="bg-cyan elevation-2 preferences-button"></div>
                                                    <div data-color="navbar-dark" data-header-type="navbar-dark" class="bg-dark elevation-2 preferences-button"></div>
                                                    <div data-color="navbar-gray-dark" data-header-type="navbar-dark" class="bg-gray-dark elevation-2 preferences-button"></div>
                                                    <div data-color="navbar-gray" data-header-type="navbar-dark" class="bg-gray elevation-2 preferences-button"></div>
                                                    <div data-color="navbar-light" data-header-type="navbar-light" class="bg-light elevation-2 preferences-button"></div>
                                                    <div data-color="navbar-warning" data-header-type="navbar-light" class="bg-warning elevation-2 preferences-button"></div>
                                                    <div data-color="navbar-white" data-header-type="navbar-light" class="bg-white elevation-2 preferences-button"></div>
                                                    <div data-color="navbar-orange" data-header-type="navbar-light" class="bg-orange elevation-2 preferences-button"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12"><h6>{{ $t('Accent Color Variants') }}</h6></div>
                                            <div class="col-lg-12">
                                                <input type="hidden" id="formPreferences-accent_variants" value="">
                                                <div id="container_accent_variants" class="d-flex flex-wrap mb-3">
                                                    <div data-color="accent-primary" class="bg-primary elevation-2 preferences-button"></div>
                                                    <div data-color="accent-warning" class="bg-warning elevation-2 preferences-button"></div>
                                                    <div data-color="accent-info" class="bg-info elevation-2 preferences-button"></div>
                                                    <div data-color="accent-danger" class="bg-danger elevation-2 preferences-button"></div>
                                                    <div data-color="accent-success" class="bg-success elevation-2 preferences-button"></div>
                                                    <div data-color="accent-indigo" class="bg-indigo elevation-2 preferences-button"></div>
                                                    <div data-color="accent-lightblue" class="bg-lightblue elevation-2 preferences-button"></div>
                                                    <div data-color="accent-navy" class="bg-navy elevation-2 preferences-button"></div>
                                                    <div data-color="accent-purple" class="bg-purple elevation-2 preferences-button"></div>
                                                    <div data-color="accent-fuchsia" class="bg-fuchsia elevation-2 preferences-button"></div>
                                                    <div data-color="accent-pink" class="bg-pink elevation-2 preferences-button"></div>
                                                    <div data-color="accent-maroon" class="bg-maroon elevation-2 preferences-button"></div>
                                                    <div data-color="accent-orange" class="bg-orange elevation-2 preferences-button"></div>
                                                    <div data-color="accent-lime" class="bg-lime elevation-2 preferences-button"></div>
                                                    <div data-color="accent-teal" class="bg-teal elevation-2 preferences-button"></div>
                                                    <div data-color="accent-oliv" class="bg-olive elevation-2 preferences-button"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12"><h6>{{ $t('Dark Sidebar Variants') }}</h6></div>
                                            <div class="col-lg-12">
                                                <input type="hidden" id="formPreferences-sidebar_variants" value="">
                                                <div id="container_sidebar_variants_dark" class="d-flex flex-wrap mb-3">
                                                    <div data-color="bg-primary" class="bg-primary elevation-2 preferences-button"></div>
                                                    <div data-color="bg-warning" class="bg-warning elevation-2 preferences-button"></div>
                                                    <div data-color="bg-info" class="bg-info elevation-2 preferences-button"></div>
                                                    <div data-color="bg-danger" class="bg-danger elevation-2 preferences-button"></div>
                                                    <div data-color="bg-success" class="bg-success elevation-2 preferences-button"></div>
                                                    <div data-color="bg-indigo" class="bg-indigo elevation-2 preferences-button"></div>
                                                    <div data-color="bg-lightblue" class="bg-lightblue elevation-2 preferences-button"></div>
                                                    <div data-color="bg-navy" class="bg-navy elevation-2 preferences-button"></div>
                                                    <div data-color="bg-purple" class="bg-purple elevation-2 preferences-button"></div>
                                                    <div data-color="bg-fuchsia" class="bg-fuchsia elevation-2 preferences-button"></div>
                                                    <div data-color="bg-pink" class="bg-pink elevation-2 preferences-button"></div>
                                                    <div data-color="bg-maroon" class="bg-maroon elevation-2 preferences-button"></div>
                                                    <div data-color="bg-orange" class="bg-orange elevation-2 preferences-button"></div>
                                                    <div data-color="bg-lime" class="bg-lime elevation-2 preferences-button"></div>
                                                    <div data-color="bg-teal" class="bg-teal elevation-2 preferences-button"></div>
                                                    <div data-color="bg-olive" class="bg-olive elevation-2 preferences-button"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12"><h6>{{ $t('Light Sidebar Variants') }}</h6></div>
                                            <div class="col-lg-12">
                                                <div id="container_sidebar_variants_light" class="d-flex flex-wrap mb-3">
                                                    <div data-color="bg-primary" class="bg-primary elevation-2 preferences-button"></div>
                                                    <div data-color="bg-warning" class="bg-warning elevation-2 preferences-button"></div>
                                                    <div data-color="bg-info" class="bg-info elevation-2 preferences-button"></div>
                                                    <div data-color="bg-danger" class="bg-danger elevation-2 preferences-button"></div>
                                                    <div data-color="bg-success" class="bg-success elevation-2 preferences-button"></div>
                                                    <div data-color="bg-indigo" class="bg-indigo elevation-2 preferences-button"></div>
                                                    <div data-color="bg-lightblue" class="bg-lightblue elevation-2 preferences-button"></div>
                                                    <div data-color="bg-navy" class="bg-navy elevation-2 preferences-button"></div>
                                                    <div data-color="bg-purple" class="bg-purple elevation-2 preferences-button"></div>
                                                    <div data-color="bg-fuchsia" class="bg-fuchsia elevation-2 preferences-button"></div>
                                                    <div data-color="bg-pink" class="bg-pink elevation-2 preferences-button"></div>
                                                    <div data-color="bg-maroon" class="bg-maroon elevation-2 preferences-button"></div>
                                                    <div data-color="bg-orange" class="bg-orange elevation-2 preferences-button"></div>
                                                    <div data-color="bg-lime" class="bg-lime elevation-2 preferences-button"></div>
                                                    <div data-color="bg-teal" class="bg-teal elevation-2 preferences-button"></div>
                                                    <div data-color="bg-olive" class="bg-olive elevation-2 preferences-button"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12"><h6>{{ $t('Brand Logo Variants') }}</h6></div>
                                            <div class="col-lg-12">
                                                <input type="hidden" id="formPreferences-logo_variants" value="">
                                                <div id="container_logo_variants" class="d-flex flex-wrap mb-3">
                                                    <div data-color="navbar-primary" class="bg-primary elevation-2 preferences-button"></div>
                                                    <div data-color="navbar-secondary" class="bg-secondary elevation-2 preferences-button"></div>
                                                    <div data-color="navbar-info" class="bg-info elevation-2 preferences-button"></div>
                                                    <div data-color="navbar-success" class="bg-success elevation-2 preferences-button"></div>
                                                    <div data-color="navbar-danger" class="bg-danger elevation-2 preferences-button"></div>
                                                    <div data-color="navbar-indigo" class="bg-indigo elevation-2 preferences-button"></div>
                                                    <div data-color="navbar-purple" class="bg-purple elevation-2 preferences-button"></div>
                                                    <div data-color="navbar-pink" class="bg-pink elevation-2 preferences-button"></div>
                                                    <div data-color="navbar-navy" class="bg-navy elevation-2 preferences-button"></div>
                                                    <div data-color="navbar-lightblue" class="bg-lightblue elevation-2 preferences-button"></div>
                                                    <div data-color="navbar-teal" class="bg-teal elevation-2 preferences-button"></div>
                                                    <div data-color="navbar-cyan" class="bg-cyan elevation-2 preferences-button"></div>
                                                    <div data-color="navbar-dark" class="bg-dark elevation-2 preferences-button"></div>
                                                    <div data-color="navbar-gray-dark" class="bg-gray-dark elevation-2 preferences-button"></div>
                                                    <div data-color="navbar-gray" class="bg-gray elevation-2 preferences-button"></div>
                                                    <div data-color="navbar-light" class="bg-light elevation-2 preferences-button"></div>
                                                    <div data-color="navbar-warning" class="bg-warning elevation-2 preferences-button"></div>
                                                    <div data-color="navbar-white" class="bg-white elevation-2 preferences-button"></div>
                                                    <div data-color="navbar-orange" class="bg-orange elevation-2 preferences-button"></div>
                                                    <a id="clear_logo" href="javascript:void(0)">{{ $t('clear') }}</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer show_by_permission_must_update">
                                        <div class="col-lg-12 col-md-12 col-xs-12">
                                            <button type="submit"
                                                class="btn btn-success btn-md btn-on-table float-right">
                                                <i class="far fa-save" aria-hidden="true"></i> {{ $t('Save') }}
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            main_folder: '',
            pagename: '',
            PreferencesForm: new Form({
                'preferences': []
            }),
            page: {
                is_ready: false,
                has_server_error: false,
                variables: [],
                is_authorized: true,
                unauthorized_type: '',
                is_variables_loading: false,
                is_variables_loaded: false,
                has_post_error: false,
                post_error_msg: '',
                is_data_loading: false,
                is_data_loaded: false,
                is_post_success: false
            }
        };
    },
    methods: {
        processLoadQueue: function () {
            if (this.page.has_server_error) {
                this.$Progress.finish();
                this.page.is_ready = true;
                return;
            }

            if (!this.page.is_authorized) {
                this.$Progress.finish();
                this.page.is_ready = true;
                return;
            }

            if (!this.page.is_variables_loaded && !this.page.is_data_loaded) {
                this.$Progress.start();
            }
            
            if (!this.page.is_variables_loaded) {
                this.loadPageVariables();
            } else {
                if (this.page.is_data_loaded) {
                    this.$nextTick(function () {
                        AdminLTEHelper.initializePreferencesPage(this.PreferencesForm.preferences);
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

            axios.get(AdminLTEHelper.getAPIURL("adminlte/get_page_variables/" + self.pagename))
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
                   AdminLTEHelper.initializePermissions(self.page.variables, false);
                   self.processLoadQueue();
                });
        },
        loadData: function () {
            var self = this;

            if (self.page.is_data_loading) {
                return;
            }

            self.page.is_data_loading = true;

            axios.get(AdminLTEHelper.getAPIURL("preferences/get"))
                .then(({ data }) => {
                    self.page.is_data_loaded = true;
                    self.page.is_data_loading = false;
                    self.PreferencesForm.preferences = data.preferences;
                    self.processLoadQueue();
                }).catch(({ data }) => {
                    self.page.is_data_loaded = true;
                    self.page.is_data_loading = false;
                    self.$Progress.fail();
                    self.page.has_server_error = true;
                    self.processLoadQueue();
                });
        },
        submitForm: function () {
            var self = this;
            self.$Progress.start();
            self.PreferencesForm.preferences = AdminLTEHelper.getPreferences();

            self.PreferencesForm.post(AdminLTEHelper.getAPIURL("preferences/post"))
                .then(({ data }) => {
                    self.$Progress.finish();
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
                                toast: true,
                                position: 'top-end',
                                title: '',
                                text: 'Changes have been saved!',
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
                                toast: true,
                                position: 'top-end',
                                title: '',
                                text: self.page.post_error_msg,
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
        this.main_folder = AdminLTEHelper.getMainFolder();
        this.pagename = AdminLTEHelper.getPagename();
        this.page.is_ready = false;
        this.processLoadQueue();
    }
}
</script>