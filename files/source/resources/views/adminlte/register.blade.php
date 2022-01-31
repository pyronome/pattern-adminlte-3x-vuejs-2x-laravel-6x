@include('adminlte.head')
<body class="hold-transition register-page" data-main-folder="{{ $main_folder }}" data-landing-page="{{ $landing_page }}">
    <div id="mainVueApplication">
        <register-form></register-form>
    </div>

    @if (config('app.env') == 'local')
    <script src="{{asset('js/adminlte/app.js')}}"></script>
    @else
    <script src="{{asset(mix('js/adminlte/app.js'), true)}}"></script>
    @endif
    <script src="/js/adminlte/register.js"></script>
    
</body>
</html>