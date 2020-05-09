<template>
    <div class="content-wrapper">
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
        <section class="content" v-show="page.ready">
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
                                <div class="card-footer show_by_permission">
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
</template>

<script>
export default {
    data() {
        return {
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
                ready: false,
                default_language_options_loaded: false,
                timezone_options_loaded: false,
                data_loaded: false
            }
        };
    },
    methods: {
        processLoadQueue: function () {
            if (!this.page.default_language_options_loaded
                    && !this.page.timezone_options_loaded
                    && !this.page.data_loaded) {
                this.$Progress.start();
            }

            if (!this.page.default_language_options_loaded) {
                this.loadDefaultLanguageOptions();
            }

            if (!this.page.timezone_options_loaded) {
                this.loadTimezoneOptions();
            }

            if (this.page.default_language_options_loaded
                    && this.page.timezone_options_loaded) {
                if (this.page.data_loaded) {
                    this.$Progress.finish();
                    this.page.ready = true;
                } else {
                    this.loadData();
                }
            }
        },
        loadDefaultLanguageOptions: function () {
            axios.get(AdminLTEHelper.getAPIURL("general_settings/get_languages"))
                .then(({ data }) => {
                    this.page.default_language_options_loaded = true;
                    this.default_language_options = data;
                    this.processLoadQueue();
                }).catch(({ data }) => {
                    this.page.default_language_options_loaded = true;
                    this.$Progress.fail();
                    this.processLoadQueue();
                });
        },
        loadTimezoneOptions: function () {
            axios.get(AdminLTEHelper.getAPIURL("general_settings/get_timezones"))
                .then(({ data }) => {
                    this.page.timezone_options_loaded = true;
                    this.timezone_options = data;
                    this.processLoadQueue();
                }).catch(({ data }) => {
                    this.page.timezone_options_loaded = true;
                    this.$Progress.fail();
                    this.processLoadQueue();
                });
        },
        loadData: function () {
            axios.get(AdminLTEHelper.getAPIURL("general_settings"))
                .then(({ data }) => {
                    this.page.data_loaded = true;
                    this.form.fill(data);
                    this.processLoadQueue();
                }).catch(({ data }) => {
                    this.page.data_loaded = true;
                    this.$Progress.fail();
                    this.processLoadQueue();
                });
        },
        submitForm: function () {
            // Submit the form via a POST request
            this.$Progress.start();
            this.form.post(AdminLTEHelper.getAPIURL("general_settings"))
                .then(({ data }) => {
                    this.$Progress.finish();
                }).catch(({ data }) => {
                    this.$Progress.fail();
                });
        }
    },
    mounted() {
        this.page.ready = false;
        this.processLoadQueue();
    }
}
</script>