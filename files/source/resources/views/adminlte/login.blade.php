@include('adminlte.head')
<body class="hold-transition login-page" data-main-folder="{{ config('adminlte.main_folder') }}">
    <div id="mainVueApplication">
        <login-form></login-form>
    </div>
    <script>document.write("<script type='text/javascript' src='/js/adminlte/app.js?v=" + Date.now() + "'><\/script>");</script>
    <script>document.write("<script type='text/javascript' src='/js/adminlte/login.js?v=" + Date.now() + "'><\/script>");</script>
</body>
</html>