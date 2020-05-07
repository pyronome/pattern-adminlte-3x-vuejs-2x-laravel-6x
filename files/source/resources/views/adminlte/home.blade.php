@include('adminlte.head')
<body class="hold-transition sidebar-mini layout-fixed text-sm" data-main-folder="{{ config('adminlte.main_folder') }}">
    @include('adminlte.header')
    <div id="mainVueApplication">
        <router-view></router-view>
        <vue-progress-bar></vue-progress-bar>
    </div>
    <script src="/js/adminlte/app.js"></script>
    <script src="/js/adminlte/custom.js"></script>
</body>
</html>