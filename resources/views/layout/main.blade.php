<html>
    <head>
        <title>@yield('title')</title>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link a href="{{asset('public/bootstrap/css/bootstrap.css')}}" rel="stylesheet">
    </head>
    <body>
        <div class="row">
            <div class="col-xs-12">
                <div class="container">
                    <div class="jumbotron">
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
        <script src="{{asset('public/js/jquery-3.2.1.min.js')}}"></script>
        @yield('scripts')
    </body>
</html>
