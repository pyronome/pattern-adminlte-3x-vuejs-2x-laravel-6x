@include('adminlte.head')
<body class="hold-transition login-page" data-url-prefix="" data-page-url="forgotpassword">
    <div id="app">
        <forgotpasswordform></forgotpasswordform>
    </div>
    <div id="divSaveMessage" class="d-none">{{ __('Your new password was sent to your email.') }}</div>
    <script src="/js/adminlte/app.js"></script>
    <script src="/js/adminlte/forgotpassword.js"></script>
</body>
</html>