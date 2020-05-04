<template>
    <div class="login-box">
        <div class="login-logo">
            <b>Admin</b>LTE
        </div>
        <div class="card">
            <div class="card-body login-card-body">
                <form id="formResetPassword"
                    name="formResetPassword"
                    method="post"
                    class="form-horizontal htmldb-form"
                    data-htmldb-table="ResetPasswordHTMLDB"
                    onsubmit="return false;">
                    <input type="hidden"
                        id="formResetPassword-id"
                        name="formResetPassword-id"
                        class="htmldb-field"
                        data-htmldb-field="id"
                        value="1">
                    <div class="input-group mb-3">
                        <input type="text"
                            id="formResetPassword-email"
                            name="formResetPassword-email"
                            class="form-control htmldb-field"
                            data-htmldb-field="email"
                            placeholder="Email">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button type="button"
                                id="buttonSubmit-formResetPassword"
                                name="buttonSubmit-formResetPassword"
                                class="btn btn-primary btn-block htmldb-button-save"
                                data-htmldb-form="formResetPassword">
                                {{ $t('Request new password') }}
                            </button>
                        </div>
                    </div>
                </form>

                <p class="mb-1 mt-2">
                    <a href="login">{{ $t('Log in') }}</a>
                </p>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            form: new Form
        }
    }
}
</script>