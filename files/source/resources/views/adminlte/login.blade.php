@include('adminlte.head')
<body class="hold-transition login-page" data-main-folder="{{ config('adminlte.main_folder') }}">
    <div id="mainVueApplication">
        <login-form></login-form>
    </div>
    <script src="/js/adminlte/app.js"></script>
    <script src="/js/adminlte/login.js"></script>
</body>
</html>