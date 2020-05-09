<template>
    <div class="login-box">
        <div class="login-logo">
            <b>Admin</b>LTE
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
            form: new Form
        }
    },
    methods: {
        submitForm: function () {
            // Submit the form via a POST request
            this.$Progress.start();
            this.form.post(AdminLTEHelper.getAPIURL("forgotpassword"))
                .then(({ data }) => {
                    this.$Progress.finish();
                }).catch(({ data }) => {
                    this.$Progress.fail();
                });
        }
    }
}
</script>