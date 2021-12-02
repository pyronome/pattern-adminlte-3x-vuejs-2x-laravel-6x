@include('adminlte.head')
<body class="hold-transition login-page" data-main-folder="{{ $main_folder }}">
    <div id="mainVueApplication">
        <forgot-password-form></forgot-password-form>
    </div>
    <div id="divSaveMessage" class="d-none">{{ __('Your new password was sent to your email.') }}</div>
    <script src="/js/adminlte/app.js"></script>
    <script src="/js/adminlte/forgotpassword.js"></script>
</body>
</html>