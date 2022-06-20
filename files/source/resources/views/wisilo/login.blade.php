@include('wisilo.head')
<body class="hold-transition login-page" data-main-folder="{{ $main_folder }}" data-landing-page="{{ $landing_page }}">
    <div id="mainVueApplication">
        <login-form></login-form>
    </div>

    @if (config('app.env') == 'local')
    <script src="{{asset('js/wisilo/app.js')}}"></script>
    @else
    <script src="{{asset(mix('js/wisilo/app.js'), true)}}"></script>
    @endif
    <script src="/js/wisilo/login.js"></script>
    
</body>
</html>