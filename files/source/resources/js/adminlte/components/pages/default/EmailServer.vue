<template>
    <div class="content-wrapper">
        <server-error v-if="page.has_server_error" ></server-error>
        <permission-error v-else-if="!page.is_authorized" :type="page.unauthorized_type"></permission-error>
        <div v-else>
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark">
                                {{ $t('Mail (SMTP) Server') }}
                            </h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="home">{{ $t('Home') }}</a></li>
                                <li class="breadcrumb-item enabled">{{ $t('Mail (SMTP) Server') }}</li>
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
                                        name="formConfiguration"
                                        class=""
                                        @submit.prevent="submitForm"
                                        @keydown="form.onKeydown($event)">
                                    <div class="card-body text-sm">
                                        <div class="row">
                                            <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                                <label for="email_from_name" class="detail-label">{{ $t('Email From Name') }}</label>
                                                <input type="text"
                                                    v-model="form.email_from_name"
                                                    class="form-control"
                                                    :class="{ 'is-invalid': form.errors.has('email_from_name') }"
                                                    id="email_from_name"
                                                    name="email_from_name">
                                                <has-error :form="form" field="email_from_name"></has-error>
                                            </div>
                                            <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                                <label for="email_reply_to" class="detail-label">{{ $t('Email Reply To') }}</label>
                                                <input type="text"
                                                    v-model="form.email_reply_to"
                                                    class="form-control"
                                                    :class="{ 'is-invalid': form.errors.has('email_reply_to') }"
                                                    id="email_reply_to"
                                                    name="email_reply_to">
                                                <has-error :form="form" field="email_reply_to"></has-error>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="row">
                                                <div class="form-group col-md-4 col-sm-4 col-xs-12">
                                                    <label for="email_smtp_host" class="detail-label">{{ $t('SMTP Host') }}</label>
                                                    <input type="text"
                                                        v-model="form.email_smtp_host"
                                                        class="form-control"
                                                        :class="{ 'is-invalid': form.errors.has('email_smtp_host') }"
                                                        id="email_smtp_host"
                                                        name="email_smtp_host">
                                                    <has-error :form="form" field="email_reply_to"></has-error>
                                                </div>
                                                <div class="form-group col-md-4 col-sm-4 col-xs-12">
                                                    <label for="email_smtp_user" class="detail-label">{{ $t('SMTP User') }}</label>
                                                    <input type="text"
                                                        v-model="form.email_smtp_user"
                                                        class="form-control"
                                                        :class="{ 'is-invalid': form.errors.has('email_smtp_user') }"
                                                        id="email_smtp_user"
                                                        name="email_smtp_user">
                                                    <has-error :form="form" field="email_smtp_user"></has-error>
                                                </div>
                                                <div class="form-group col-md-4 col-sm-4 col-xs-12">
                                                    <label for="email_smtp_password" class="detail-label">{{ $t('SMTP Password') }}</label>
                                                    <input type="password"
                                                        v-model="form.email_smtp_password"
                                                        class="form-control"
                                                        :class="{ 'is-invalid': form.errors.has('email_smtp_user') }"
                                                        id="email_smtp_password"
                                                        name="email_smtp_password"
                                                        autocomplete="new-password">
                                                    <has-error :form="form" field="email_smtp_password"></has-error>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                                    <label for="email_smtp_encryption" class="detail-label">{{ $t('Encryption') }}</label>
                                                    <select2-element id="email_smtp_encryption"
                                                        name="email_smtp_encryption"
                                                        :options="email_smtp_encryption_options"
                                                        v-model="form.email_smtp_encryption"
                                                        :class="{ 'is-invalid': form.errors.has('email_smtp_encryption') }">
                                                        <option value="tls">{{ $t('TLS') }}</option>
                                                        <option value="ssl">{{ $t('SSL') }}</option>
                                                    </select2-element>
                                                    <has-error :form="form" field="email_smtp_password"></has-error>
                                                </div>
                                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                                    <label for="email_smtp_port" class="detail-label">{{ $t('Port') }}</label>
                                                    <input type="text"
                                                        v-model="form.email_smtp_port"
                                                        class="form-control"
                                                        :class="{ 'is-invalid': form.errors.has('email_smtp_port') }"
                                                        id="email_smtp_port"
                                                        name="email_smtp_port">
                                                    <has-error :form="form" field="email_smtp_port"></has-error>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer show_by_permission_must_update">
                                        <div class="col-lg-12 col-md-12 col-xs-12">
                                            <button :disabled="form.busy"
                                                type="submit"
                                                class="btn btn-success btn-md btn-on-table float-right">
                                                {{ $t('Save') }}
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
            email_smtp_encryption_options: [],
            form: new Form({
                'email_from_name': '' ,
                'email_reply_to': '' ,
                'email_smtp_host': '' ,
                'email_smtp_user': '' ,
                'email_smtp_password': '' ,
                'email_smtp_encryption': '' ,
                'email_smtp_port': 0
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
            if (this.page.is_data_loading) {
                return;
            }

            this.page.is_data_loading = true;

            axios.get(AdminLTEHelper.getAPIURL("email_server"))
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
            
            self.form.post(AdminLTEHelper.getAPIURL("email_server/post"))
                .then(({ data }) => {
                    self.$Progress.finish();
                    self.page.has_post_error = data.has_error;
                    self.page.post_error_msg = data.error_msg;
                    self.page.has_server_error = false;
                }).catch(({ data }) => {
                    self.$Progress.fail();
                    let errors = (self.AdminLTEUserForm.errors.errors);
                    if (undefined !== errors.error) {
                        self.page.has_server_error = true;
                    } else {
                        self.page.has_post_error = true;
                        self.page.post_error_msg = self.$t("Please fill in the required fields.");
                    }
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
        this.loadData();
    }
}
</script>