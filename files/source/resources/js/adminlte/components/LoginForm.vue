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
                    method="post"
                    class="form-horizontal htmldb-form"
                    data-htmldb-table="loginHTMLDB"
                    onsubmit="return false;">
                    <input type="hidden"
                        id="formLogin-id"
                        name="formLogin-id"
                        class="htmldb-field"
                        data-htmldb-field="id"
                        value="0">
                    <div class="input-group mb-3">
                        <input type="text"
                            id="formLogin-email"
                            name="formLogin-email"
                            class="form-control htmldb-field"
                            data-htmldb-field="email"
                            placeholder="Email">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password"
                            id="formLogin-password"
                            name="formLogin-password"
                            class="form-control htmldb-field"
                            data-htmldb-field="password"
                            placeholder="Password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-8" style="padding-top:8px;">
                            <div class="icheck-primary d-inline">
                                <input type="checkbox"
                                    id="formLogin-remember"
                                    name="formLogin-remember"
                                    class="form-control htmldb-field"
                                    data-htmldb-field="remember">
                                <label for="formLogin-remember">
                                    {{ $t('Remember Me') }}
                                </label>
                            </div>
                        </div>
                        <div class="col-4">
                            <button type="button"
                                id="buttonSubmit-formLogin"
                                name="buttonSubmit-formLogin"
                                class="btn btn-primary btn-block htmldb-button-save"
                                data-htmldb-form="formLogin">
                                {{ $t('Sign in') }}
                            </button>
                        </div>
                    </div>
                </form>

                <p class="mb-1 mt-2">
                    <a href="forgotpassword">{{ $t('I forgot my password') }}</a>
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