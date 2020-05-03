@include('adminlte.head')
<body class="hold-transition login-page" data-url-prefix="" data-page-url="forgotpassword">
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
                                {{ __('Request new password') }}
                            </button>
                        </div>
                    </div>
                </form>

                <p class="mb-1 mt-2">
                    <a href="login">{{ __('Log in') }}</a>
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
    
    <div id="ResetPasswordHTMLDB"
        class="htmldb-table"
        data-htmldb-read-url="/{{ config('adminlte.main_folder') }}/htmldb/forgotpassword/get?_token={{ csrf_token() }}"
        data-htmldb-write-url="/{{ config('adminlte.main_folder') }}/htmldb/forgotpassword/post?_token={{ csrf_token() }}"
        data-htmldb-loader="divLoader">
    </div>
    
    <div id="divSaveMessage" class="d-none">{{ __('Your new password was sent to your email.') }}</div>
    
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
    <script src="/assets/adminlte/js/forgotpassword.js"></script>
</body>
</html>