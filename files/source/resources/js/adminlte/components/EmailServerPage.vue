<template>
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">
                            {{ $t('Email Configuration') }}
                        </h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="home">{{ $t('Home') }}</a></li>
                            <li class="breadcrumb-item enabled">{{ $t('Email Configuration') }}</li>
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
                                    name="formConfiguration"
                                    class="card-body text-sm"
                                    @submit.prevent="submitForm"
                                    @keydown="form.onKeydown($event)">
                                <div class="row">
                                    <div class="form-group col-lg-12 col-md-12 col-xs-12">
                                        <label for="email_type" class="detail-label">{{ $t('Email Type') }}</label>
                                        <select data-placeholder=""
                                            v-model="form.email_type"
                                            id="email_type"
                                            name="email_type"
                                            class="form-control select2 select-has-option"
                                            :class="{ 'is-invalid': form.errors.has('email_type') }"
                                            style="width: 100%;">
                                            <option value="0">{{ $t('Standart Mail') }}</option>
                                            <option value="1">{{ $t('SMTP') }}</option>
                                        </select>
                                        <has-error :form="form" field="email_type"></has-error>
                                    </div>
                                </div>
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
                                <div v-show="(form.email_type==1)">
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
                                            <select data-placeholder=""
                                                v-model="form.email_smtp_encryption"
                                                id="email_smtp_encryption"
                                                name="email_smtp_encryption"
                                                class="form-control select2 select-has-option"
                                                :class="{ 'is-invalid': form.errors.has('email_smtp_encryption') }"
                                                style="width: 100%;">
                                                <option value="0">{{ $t('TLS') }}</option>
                                                <option value="1">{{ $t('SSL') }}</option>
                                            </select>
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
                                <div class="row">
                                    <div class="form-group col-lg-12 col-md-12 col-xs-12">
                                        <label for="email_format" class="detail-label">{{ $t('Mail Format') }}</label>
                                        <select data-placeholder=""
                                            v-model="form.email_format"
                                            id="email_format"
                                            name="email_format"
                                            class="form-control select2 select-has-option"
                                            :class="{ 'is-invalid': form.errors.has('email_format') }"
                                            style="width: 100%;">
                                            <option value="0">{{ $t('HTML') }}</option>
                                            <option value="1">{{ $t('Text') }}</option>
                                        </select>
                                        <has-error :form="form" field="email_format"></has-error>
                                    </div>
                                </div>
                            </form>
                            <div class="card-footer show_by_permission">
                                <div class="col-lg-12 col-md-12 col-xs-12">
                                    <button :disabled="form.busy"
                                        type="submit"
                                        class="btn btn-success btn-md btn-on-table float-right">
                                        <i class="far fa-save" aria-hidden="true"></i>&nbsp;{{ $t('Save') }}
                                    </button>
                                </div>
                            </div>
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
            form: new Form({
                email: '',
                password: '',
                remember: false
            }),
            page: {
                ready: false
            }
        };
    },
    methods: {
        loadData: function () {
            this.$Progress.start();
            axios.get(AdminLTEHelper.getAPIURL("email_server"))
                    .then(({ data }) => {
                        this.$Progress.finish();
                        this.form = data;
                        this.page.ready = true;
                    }).catch(({ data }) => {
                        this.$Progress.fail();
                        this.page.ready = true;
                    });
        },
        submitForm () {
            // Submit the form via a POST request
            this.$Progress.start();
            this.form.post(AdminLTEHelper.getAPIURL("email_server"))
                .then(({ data }) => {
                    this.$Progress.finish();
                }).catch(({ data }) => {
                    this.$Progress.fail();
                });
        }
    },
    mounted() {
        this.page.ready = false;
        this.loadData();
    }
}
</script>