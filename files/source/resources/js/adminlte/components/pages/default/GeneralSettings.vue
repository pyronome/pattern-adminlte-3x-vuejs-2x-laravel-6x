<template>
    <div class="content-wrapper">
        <server-error v-if="page.has_server_error" ></server-error>
        <permission-error v-else-if="!page.is_authorized" :type="page.unauthorized_type"></permission-error>
        <div v-else>
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark">{{ $t('General Settings') }}</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="home">{{ $t('Home') }}</a></li>
                                <li class="breadcrumb-item active">{{ $t('General Settings') }}</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <section class="content" v-show="page.is_ready">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12">
                            <div class="card card-primary">
                                <form id="formConfiguration"
                                    class=""
                                    @submit.prevent="submitForm"
                                    @keydown="form.onKeydown($event)">
                                    <div class="card-body text-sm">
                                        <div class="row">
                                            <div class="form-group col-lg-12 col-md-12 col-xs-12">
                                                <label for="project_title" class="detail-label">{{ $t('Project Title') }}</label>
                                                <input type="text"
                                                    v-model="form.project_title"
                                                    class="form-control"
                                                    :class="{ 'is-invalid': form.errors.has('project_title') }"
                                                    id="project_title"
                                                    name="project_title">
                                                <has-error :form="form" field="project_title"></has-error>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-lg-12 col-md-12 col-xs-12">
                                                <label for="main_folder" class="detail-label">{{ $t('Main Folder') }}</label>
                                                <input type="text"
                                                    v-model="form.main_folder"
                                                    class="form-control"
                                                    :class="{ 'is-invalid': form.errors.has('main_folder') }"
                                                    id="main_folder"
                                                    name="main_folder">
                                                <has-error :form="form" field="main_folder"></has-error>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-lg-12 col-md-12 col-xs-12">
                                                <label for="landing_page" class="detail-label">{{ $t('Landing Page') }}</label>
                                                <input type="text"
                                                    v-model="form.landing_page"
                                                    class="form-control"
                                                    :class="{ 'is-invalid': form.errors.has('landing_page') }"
                                                    id="landing_page"
                                                    name="landing_page">
                                                <has-error :form="form" field="landing_page"></has-error>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-lg-12 col-md-12 col-xs-12">
                                                <label for="default_language" class="detail-label">{{ $t('Default Language') }}</label>
                                                <select2-element id="default_language"
                                                    name="default_language"
                                                    :options="default_language_options"
                                                    v-model="form.default_language"
                                                    :class="{ 'is-invalid': form.errors.has('default_language') }">
                                                </select2-element>
                                                <has-error :form="form" field="default_language"></has-error>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-lg-12 col-md-12 col-xs-12">
                                                <label for="timezone" class="detail-label">{{ $t('Timezone') }}</label>
                                                <select2-element id="timezone"
                                                    name="timezone"
                                                    :options="timezone_options"
                                                    v-model="form.timezone"
                                                    :class="{ 'is-invalid': form.errors.has('timezone') }">
                                                </select2-element>
                                                <has-error :form="form" field="timezone"></has-error>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                                <label for="date_format" class="detail-label">{{ $t('Date Format') }}</label>
                                                <select2-element id="date_format"
                                                    name="date_format"
                                                    :options="date_format_options"
                                                    v-model="form.date_format"
                                                    :class="{ 'is-invalid': form.errors.has('date_format') }">
                                                    <option value="d/m/Y">15/06/1981</option>
                                                    <option value="j/n/Y">15/6/1981</option>
                                                    <option value="d/m/y">15/06/81</option>
                                                    <option value="j/n/y">15/6/81</option>
                                                    <option value="d-m-Y">15-06-1981</option>
                                                    <option value="j-n-Y">15-6-1981</option>
                                                    <option value="d-m-y">15-06-81</option>
                                                    <option value="j-n-y">15-6-81</option>
                                                    <option value="d.m.Y">15.06.1981</option>
                                                    <option value="j.n.Y">15.6.1981</option>
                                                    <option value="d.m.y">15.06.81</option>
                                                    <option value="j.n.y">15.6.81</option>
                                                    <option value="m/d/Y">06/15/1981</option>
                                                    <option value="n/j/Y">6/15/1981</option>
                                                    <option value="m/d/y">06/15/81</option>
                                                    <option value="n/j/y">6/15/81</option>
                                                    <option value="m-d-Y">06-15-1981</option>
                                                    <option value="n-j-Y">6-15-1981</option>
                                                    <option value="m-d-y">06-15-81</option>
                                                    <option value="n-j-y">6-15-81</option>
                                                    <option value="m.d.Y">06.15.1981</option>
                                                    <option value="n.j.Y">6.15.1981</option>
                                                    <option value="m.d.y">06.15.81</option>
                                                    <option value="n.j.y">6.15.81</option>
                                                    <option value="Y/m/d">1981/06/15</option>
                                                    <option value="Y/n/j">1981/6/15</option>
                                                    <option value="y/m/d">81/06/15</option>
                                                    <option value="y/n/j">81/6/15</option>
                                                    <option value="Y-m-d">1981-06-15</option>
                                                    <option value="Y-n-j">1981-6-15</option>
                                                    <option value="y-m-d">81-06-15</option>
                                                    <option value="y-n-j">81-6-15</option>
                                                    <option value="Y.m.d">1981.06.15</option>
                                                    <option value="Y.n.j">1981.6.15</option>
                                                    <option value="y.m.d">81.06.15</option>
                                                    <option value="y.n.j">81.6.15</option>
                                                    <option value="j F Y">{{ $t('15 June 1981') }}</option>
                                                    <option value="j F y">{{ $t('15 June 81') }}</option>
                                                    <option value="j M Y">{{ $t('15 Jun 1981') }}</option>
                                                    <option value="j M y">{{ $t('15 Jun 81') }}</option>
                                                    <option value="F j, Y">{{ $t('June 15, 1981') }}</option>
                                                    <option value="F j, y">{{ $t('June 15, 81') }}</option>
                                                    <option value="F j, Y">{{ $t('Jun 15, 1981') }}</option>
                                                    <option value="M j, y">{{ $t('Jun 15, 81') }}</option>
                                                </select2-element>
                                                <has-error :form="form" field="date_format"></has-error>
                                            </div>
                                            <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                                <label for="year_month_format" class="detail-label">{{ $t('Year Month Format') }}</label>
                                                <select2-element id="year_month_format"
                                                    name="year_month_format"
                                                    :options="year_month_format_options"
                                                    v-model="form.year_month_format"
                                                    :class="{ 'is-invalid': form.errors.has('year_month_format') }">
                                                    <option value="m/Y">06/1981</option>
                                                    <option value="n/Y">6/1981</option>
                                                    <option value="m/y">06/81</option>
                                                    <option value="n/y">6/81</option>
                                                    <option value="m-Y">06-1981</option>
                                                    <option value="n-Y">6-1981</option>
                                                    <option value="m-y">06-81</option>
                                                    <option value="n-y">6-81</option>
                                                    <option value="m.Y">06.1981</option>
                                                    <option value="n.Y">6.1981</option>
                                                    <option value="m.y">06.81</option>
                                                    <option value="n.y">6.81</option>
                                                    <option value="Y/m">1981/06</option>
                                                    <option value="Y/n">1981/6</option>
                                                    <option value="y/m">81/06</option>
                                                    <option value="y/n">81/6</option>
                                                    <option value="Y-m">1981-06</option>
                                                    <option value="Y-n">1981-6</option>
                                                    <option value="y-m">81-06</option>
                                                    <option value="y-n">81-6</option>
                                                    <option value="Y.m">1981.06</option>
                                                    <option value="Y.n">1981.6</option>
                                                    <option value="y.m">81.06</option>
                                                    <option value="y.n">81.6</option>
                                                    <option value="F Y">{{ $t('June 1981') }}</option>
                                                    <option value="F y">{{ $t('June 81') }}</option>
                                                    <option value="M Y">{{ $t('Jun 1981') }}</option>
                                                    <option value="M y">{{ $t('Jun 81') }}</option>
                                                </select2-element>
                                                <has-error :form="form" field="year_month_format"></has-error>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-lg-12 col-md-12 col-xs-12">
                                                <label for="time_format" class="detail-label">{{ $t('Time Format') }}</label>
                                                <select2-element id="time_format"
                                                    name="time_format"
                                                    :options="time_format_options"
                                                    v-model="form.time_format"
                                                    :class="{ 'is-invalid': form.errors.has('time_format') }">
                                                    <option value="H:i">17:00</option>
                                                    <option value="h:i a">05:00 pm</option>
                                                    <option value="H:i:s">17:00:00</option>
                                                    <option value="h:i:s a">05:00:00 pm</option>
                                                </select2-element>
                                                <has-error :form="form" field="time_format"></has-error>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-lg-12 col-md-12 col-xs-12">
                                                <label for="number_format" class="detail-label">{{ $t('Number Format') }}</label>
                                                <select2-element id="number_format"
                                                    name="number_format"
                                                    :options="number_format_options"
                                                    v-model="form.number_format"
                                                    :class="{ 'is-invalid': form.errors.has('number_format') }">
                                                    <option value="tr">1.000.000,00</option>
                                                    <option value="en">1,000,000.00</option>
                                                </select2-element>
                                                <has-error :form="form" field="number_format"></has-error>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-lg-12 col-md-12 col-xs-12">
                                                <label for="google_maps_api_key" class="detail-label">{{ $t('Google Maps API Key') }}</label>
                                                <input type="text"
                                                    v-model="form.google_maps_api_key"
                                                    class="form-control"
                                                    :class="{ 'is-invalid': form.errors.has('google_maps_api_key') }"
                                                    id="google_maps_api_key"
                                                    name="google_maps_api_key">
                                                <has-error :form="form" field="number_format"></has-error>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer show_by_permission_must_update">
                                        <div class="col-lg-12 col-md-12 col-xs-12">
                                            <button :disabled="form.busy"
                                                type="submit"
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
            default_language_options: [],
            timezone_options: [],
            date_format_options: [],
            year_month_format_options: [],
            time_format_options: [],
            number_format_options: [],
            form: new Form({
                'debug_mode': false,
                'project_title': '',
                'main_folder': '',
                'landing_page': '',
                'default_language': '',
                'timezone': '',
                'date_format': '',
                'year_month_format': '',
                'time_format': '',
                'number_format': '',
                'google_maps_api_key': ''
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
                is_default_language_options_loading: false,
                is_default_language_options_loaded: false,
                is_timezone_options_loading: false,
                is_timezone_options_loaded: false,
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

            if (!this.page.is_variables_loaded && !this.page.is_data_loaded && !this.page.is_default_language_options_loaded && !this.page.is_timezone_options_loaded) {
                this.$Progress.start();
            }
            
            if (!this.page.is_variables_loaded) {
                this.loadPageVariables();
            } else {
                if (!this.page.is_default_language_options_loaded) {
                    this.loadDefaultLanguageOptions();
                }

                if (!this.page.is_timezone_options_loaded) {
                    this.loadTimezoneOptions();
                }

                if (this.page.is_data_loaded) {
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
        loadDefaultLanguageOptions: function () {
            if (this.page.is_default_language_options_loading) {
                return;
            }

            this.page.is_default_language_options_loading = true;

            axios.get(AdminLTEHelper.getAPIURL("general_settings/get_languages"))
                .then(({ data }) => {
                    this.page.is_default_language_options_loaded = true;
                    this.page.is_default_language_options_loading = false;
                    this.default_language_options = data;
                    this.processLoadQueue();
                }).catch(({ data }) => {
                    this.page.is_default_language_options_loaded = true;
                    this.page.is_default_language_options_loading = false;
                    this.$Progress.fail();
                    self.page.has_server_error = true;
                    self.processLoadQueue();
                });
        },
        loadTimezoneOptions: function () {
            if (this.page.is_timezone_options_loading) {
                return;
            }

            this.page.is_timezone_options_loading = true;

            axios.get(AdminLTEHelper.getAPIURL("general_settings/get_timezones"))
                .then(({ data }) => {
                    this.page.is_timezone_options_loaded = true;
                    this.page.is_timezone_options_loading = false;
                    this.timezone_options = data;
                    this.processLoadQueue();
                }).catch(({ data }) => {
                    this.page.is_timezone_options_loaded = true;
                    this.page.is_timezone_options_loading = false;
                    this.$Progress.fail();
                    self.page.has_server_error = true;
                    self.processLoadQueue();
                });
        },
        loadData: function () {
            if (this.page.is_data_loading) {
                return;
            }

            this.page.is_data_loading = true;

            axios.get(AdminLTEHelper.getAPIURL("general_settings"))
                .then(({ data }) => {
                    this.page.is_data_loaded = true;
                    this.page.is_data_loading = false;
                    this.form.fill(data);
                    this.processLoadQueue();
                }).catch(({ data }) => {
                    this.page.is_data_loaded = true;
                    this.page.is_data_loading = false;
                    this.$Progress.fail();
                    this.page.has_server_error = true;
                    this.processLoadQueue();
                });
        },
        submitForm: function () {
            var self = this;
            self.$Progress.start();
            
            self.form.post(AdminLTEHelper.getAPIURL("general_settings/post"))
                .then(({ data }) => {
                    self.$Progress.finish();
                    self.page.has_post_error = data.has_error;
                    self.page.post_error_msg = data.error_msg;
                }).catch(({ data }) => {
                    self.$Progress.fail();
                }).finally(function() {
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