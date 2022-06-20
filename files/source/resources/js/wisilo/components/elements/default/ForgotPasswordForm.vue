<template>
    <div class="login-box">
        <div class="login-logo">
            <img :src="brand_data.logo" alt="Wisilo  Logo" class="brand-login-logo img-circle elevation-3" style="opacity: .8">
            <span class="brand-login-name font-weight-light" v-html="brand_data.name"></span>
        </div>
        <div class="card">
            <div class="card-body login-card-body">
                <form id="formResetPassword"
                        name="formResetPassword"
                        class="form-horizontal"
                        @submit.prevent="submitForm"
                        @keydown="form.onKeydown($event)">
                    <div class="input-group mb-3">
                        <input type="text"
                            v-model="form.email"
                            id="formResetPassword-email"
                            name="formResetPassword-email"
                            class="form-control"
                            placeholder="Email"
                            :class="{ 'is-invalid': form.errors.has('email') }">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                        <has-error :form="form" field="email"></has-error>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button :disabled="form.busy"
                                    type="submit"
                                    class="btn btn-primary btn-block">{{ $t('Request new password') }}</button>
                        </div>
                    </div>
                </form>

                <p class="mb-1 mt-2">
                    <a href="login">{{ $t('Log in') }}</a>
                </p>
            </div>
        </div>
        <vue-progress-bar></vue-progress-bar>
        <body-loader :body_loader_active="body_loader_active" class="bodyLoader"></body-loader>
    </div>
</template>
<script>
export default {
    data() {
        return {
            brand_data: [],
            form: new Form,
            page: {
                is_ready: false,
                is_data_loading: false,
                is_data_loaded: false,
                is_post_success: false
            },
            body_loader_active: false
        }
    },
    created() {
        this.$Progress.start();
    },
    mounted() {
        this.page.is_ready = false;
        this.body_loader_active = true;
        this.processLoadQueue();
    },
    methods: {
        processLoadQueue: function () {
            if (!this.page.is_data_loaded) {
                this.$Progress.start();
                this.loadData();
            } else {
                this.$Progress.finish();
                this.page.is_ready = true;
            }
        },
        loadData: function () {
            var self = this;

            if (self.page.is_data_loading) {
                return;
            }

            self.page.is_data_loading = true;

            axios.get(WisiloHelper.getAPIURL("login/get_brand_data"))
                .then(({ data }) => {
                    self.page.is_data_loaded = true;
                    self.page.is_data_loading = false;
                    self.brand_data = data;
                    self.processLoadQueue();
                }).catch(({ data }) => {
                    self.page.is_data_loaded = true;
                    self.page.is_data_loading = false;
                    self.$Progress.fail();
                    self.processLoadQueue();
                }).finally(function() {
                    setTimeout(function() {
                        self.body_loader_active = false;
                    }, 1000);
                });
        },
        submitForm: function () {
            var self = this;
            self.$Progress.start();
            
            self.form.post(WisiloHelper.getAPIURL("forgotpassword"))
                .then(({ data }) => {
                    self.$Progress.finish();
                    self.page.is_post_success = true;
                }).catch(({ data }) => {
                    self.$Progress.fail();
                    self.page.is_post_success = false;
                }).finally(function() {
                    if (self.page.is_post_success) {
                        Vue.swal.fire({
                            position: 'top-end',
                            title: self.$t("Your new password was sent to your email."),
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true,
                        });
                    }
                });
        }
    }
}
</script>