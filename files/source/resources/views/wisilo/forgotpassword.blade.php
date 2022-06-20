@include('wisilo.head')
<body class="hold-transition login-page" data-main-folder="{{ $main_folder }}">
    <div id="mainVueApplication">
        <forgot-password-form></forgot-password-form>
    </div>
    <div id="divSaveMessage" class="d-none">{{ __('Your new password was sent to your email.') }}</div>
    <script src="/js/wisilo/app.js"></script>
    <script src="/js/wisilo/forgotpassword.js"></script>
</body>
</html>