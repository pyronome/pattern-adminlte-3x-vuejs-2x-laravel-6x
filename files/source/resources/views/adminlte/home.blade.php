@include('adminlte.head')
<body class="hold-transition sidebar-mini layout-fixed text-sm" data-main-folder="{{ config('adminlte.main_folder') }}" data-page-url="home">
    @include('adminlte.header')
    <div id="mainApp">
        <router-view></router-view>
    </div>
    <script src="/js/adminlte/app.js"></script>
    <script src="/js/adminlte/home.js"></script>
</body>
</html>