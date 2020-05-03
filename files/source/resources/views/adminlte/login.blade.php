@include('adminlte.head')
<body class="hold-transition login-page" data-url-prefix=""  data-page-url="login">
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
                                    {{ __('Remember Me') }}
                                </label>
                            </div>
                        </div>
                        <div class="col-4">
                            <button type="button"
                                id="buttonSubmit-formLogin"
                                name="buttonSubmit-formLogin"
                                class="btn btn-primary btn-block htmldb-button-save"
                                data-htmldb-form="formLogin">
                                {{ __('Sign in') }}
                            </button>
                        </div>
                    </div>
                </form>

                <p class="mb-1 mt-2">
                    <a href="forgotpassword">{{ __('I forgot my password') }}</a>
                </p>
            </div>
        </div>
    </div>
    
    <div class="modal fade htmldb-dialog-edit" id="modal-Error">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h4 class="modal-title">{{ __('Error') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p id="pFormErrorText"></p>
                </div>
            </div>
        </div>
    </div>

    <div class="divDialogContent divLoader" id="divLoader" >
        <img class="center-block" src="/assets/adminlte/img/loader.svg" width="70" height="70" />
        <div id="divLoaderText" class="" data-default-text="{{ __('Loading...') }}"></div>
    </div>
    
    <div id="loginHTMLDB"
        class="htmldb-table"
        data-htmldb-read-url="/{{ config('adminlte.main_folder') }}/htmldb/login/get?_token={{ csrf_token() }}"
        data-htmldb-write-url="/{{ config('adminlte.main_folder') }}/htmldb/login/post?_token={{ csrf_token() }}"
        data-htmldb-loader="divLoader">
    </div>
    
    <!-- jQuery -->
    <script src="/assets/adminlte/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="/assets/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Toastr -->
    <script src="/assets/adminlte/plugins/toastr/toastr.min.js"></script>
    <!-- AdminLTE App -->
    <script src="/assets/adminlte/js/adminlte.min.js"></script>
    <script src="/assets/adminlte/js/global.js"></script>
    <script src="/assets/adminlte/js/htmldb.js"></script>
    <script src="/assets/adminlte/js/adminlte.htmldb.js"></script>
    <script src="/assets/adminlte/js/login.js"></script>
</body>
</html>