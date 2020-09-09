<template>
    <div class="login-box">
        <div class="login-logo">
            <b>Admin</b>LTE
        </div>
        <div class="card">
            <div class="card-body login-card-body">
                <!-- <p class="login-box-msg">{{ __('Sign in to start your session') }}</p> -->
                <form id="formLogin"
                        name="formLogin"
                        class="form-horizontal"
                        @submit.prevent="submitForm"
                        @keydown="form.onKeydown($event)">
                    /* {{@snippet:begin_login_form}} */
                    <div class="input-group mb-3">
                        <input type="text"
                            v-model="form.email"
                            id="formLogin-email"
                            name="formLogin-email"
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
                    <div class="input-group mb-3">
                        <input type="password"
                            v-model="form.password"
                            id="formLogin-password"
                            name="formLogin-password"
                            class="form-control"
                            placeholder="Password"
                            :class="{ 'is-invalid': form.errors.has('password') }">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                        <has-error :form="form" field="password"></has-error>
                    </div>
                    <div class="row">
                        <div class="col-8" style="padding-top:8px;">
                            <div class="icheck-primary d-inline">
                                <input type="checkbox"
                                    v-model="form.remember"
                                    id="formLogin-remember"
                                    name="formLogin-remember"
                                    class="form-control">
                                <label for="formLogin-remember">
                                    {{ $t('Remember Me') }}
                                </label>
                            </div>
                        </div>
                        <div class="col-4">
                            <button :disabled="form.busy"
                                    type="submit"
                                    class="btn btn-primary btn-block">{{ $t('Sign in') }}</button>
                        </div>
                    </div>
                    /* {{@snippet:end_login_form}} */
                </form>
                <p class="mb-1 mt-2">
                    <a href="forgotpassword">{{ $t('I forgot my password') }}</a>
                </p>
            </div>
        </div>
        <vue-progress-bar></vue-progress-bar>
    </div>
</template>
<script>
export default {
    created() {
        this.$Progress.start();
    },
    mounted() {
        this.$Progress.finish();
    },
    data() {
        return {
            form: new Form({
                email: '',
                password: '',
                remember: false
            })
        }
    },
    methods: {
        submitForm: function () {
            // Submit the form via a POST request
            this.$Progress.start();
            this.form.post(AdminLTEHelper.getAPIURL("login"))
                .then(({ data }) => {
                    this.$Progress.finish();
                    if (!data.landing_page) {
                        data.landing_page = "home";
                    }
                    window.location = data.landing_page;
                }).catch(({ data }) => {
                    this.$Progress.fail();
                });
        }
    }
}
</script>
