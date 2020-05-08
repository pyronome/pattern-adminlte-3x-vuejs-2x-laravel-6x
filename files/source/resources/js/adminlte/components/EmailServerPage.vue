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
                            <li class="breadcrumb-item"><router-link to="home">{{ $t('Home') }}</router-link></li>
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
                            <form id="formConfiguration" name="formConfiguration" onsubmit="return false;" class="card-body text-sm">
                                <input type="hidden"
                                    id="id"
                                    name="id"
                                    class="">
                                <div class="row">
                                    <div class="form-group col-lg-12 col-md-12 col-xs-12">
                                        <label for="email_type" class="detail-label">{{ $t('Email Type') }}</label>
                                        <select data-placeholder=""
                                            id="email_type"
                                            name="email_type"
                                            class="form-control select-has-option"
                                            style="width: 100%;">
                                            <option value="0">{{ $t('Standart Mail') }}</option>
                                            <option value="1">{{ $t('SMTP') }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                        <label for="email_from_name" class="detail-label">{{ $t('Email From Name') }}</label>
                                        <input type="text"
                                            class="form-control"
                                            id="email_from_name"
                                            name="email_from_name">
                                    </div>
                                    <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                        <label for="email_reply_to" class="detail-label">{{ $t('Email Reply To') }}</label>
                                        <input type="text"
                                            class="form-control"
                                            id="email_reply_to"
                                            name="email_reply_to">
                                    </div>
                                </div>
                                <div v-show="(form.email_type==1)">
                                    <div class="row">
                                        <div class="form-group col-md-4 col-sm-4 col-xs-12">
                                            <label for="email_smtp_host" class="detail-label">{{ $t('SMTP Host') }}</label>
                                            <input type="text"
                                                class="form-control"
                                                id="email_smtp_host"
                                                name="email_smtp_host">
                                        </div>
                                        <div class="form-group col-md-4 col-sm-4 col-xs-12">
                                            <label for="email_smtp_user" class="detail-label">{{ $t('SMTP User') }}</label>
                                            <input type="text"
                                                class="form-control"
                                                id="email_smtp_user"
                                                name="email_smtp_user">
                                        </div>
                                        <div class="form-group col-md-4 col-sm-4 col-xs-12">
                                            <label for="email_smtp_password" class="detail-label">{{ $t('SMTP Password') }}</label>
                                            <input type="password"
                                                class="form-control"
                                                id="email_smtp_password"
                                                name="email_smtp_password"
                                                autocomplete="new-password">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                            <label for="email_smtp_encryption" class="detail-label">{{ $t('Encryption') }}</label>
                                            <select data-placeholder=""
                                                id="email_smtp_encryption"
                                                name="email_smtp_encryption"
                                                class="form-control select-has-option"
                                                style="width: 100%;">
                                                <option value="0">{{ $t('TLS') }}</option>
                                                <option value="1">{{ $t('SSL') }}</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                            <label for="email_smtp_port" class="detail-label">{{ $t('Port') }}</label>
                                            <input type="text"
                                                class="form-control"
                                                id="email_smtp_port"
                                                name="email_smtp_port">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-lg-12 col-md-12 col-xs-12">
                                        <label for="email_format" class="detail-label">{{ $t('Mail Format') }}</label>
                                        <select data-placeholder=""
                                            id="email_format"
                                            name="email_format"
                                            class="form-control select-has-option"
                                            style="width: 100%;">
                                            <option value="0">{{ $t('HTML') }}</option>
                                            <option value="1">{{ $t('Text') }}</option>
                                        </select>
                                    </div>
                                </div>
                            </form>
                            <div class="card-footer show_by_permission">
                                <div class="col-lg-12 col-md-12 col-xs-12">
                                    <button type="button"
                                        id="buttonSave-formConfiguration"
                                        name="buttonSave-formConfiguration"
                                        class="btn btn-success btn-md btn-on-table float-right">
                                        <i class="far fa-save" aria-hidden="true"></i> {{ $t('Save') }}
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