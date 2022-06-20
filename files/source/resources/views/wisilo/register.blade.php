@include('wisilo.head')
<body class="hold-transition register-page" data-main-folder="{{ $main_folder }}" data-landing-page="{{ $landing_page }}">
    <div id="mainVueApplication">
        <register-form></register-form>
    </div>

    @if (config('app.env') == 'local')
    <script src="{{asset('js/wisilo/app.js')}}"></script>
    @else
    <script src="{{asset(mix('js/wisilo/app.js'), true)}}"></script>
    @endif
    <script src="/js/wisilo/register.js"></script>
    
</body>
</html>