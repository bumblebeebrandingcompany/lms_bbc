<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{config('app.name', 'LMS')}}</title>
    @includeIf('layouts.partials.css')
    @yield('styles')
</head>
<body class="sidebar-mini layout-fixed" style="height: auto;">
    <div class="wrapper">
        <nav class="main-header navbar navbar-expand bg-white navbar-light border-bottom">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#"><i class="fa fa-bars"></i></a>
                </li>
            </ul>
            <!-- Right navbar links -->
            @if(count(config('panel.available_languages', [])) > 1)
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link" data-toggle="dropdown" href="#">
                            {{ strtoupper(app()->getLocale()) }}
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            @foreach(config('panel.available_languages') as $langLocale => $langName)
                                <a class="dropdown-item" href="{{ url()->current() }}?change_language={{ $langLocale }}">{{ strtoupper($langLocale) }} ({{ $langName }})</a>
                            @endforeach
                        </div>
                    </li>
                </ul>
            @endif
        </nav>
        @include('partials.menu')
        <div class="content-wrapper" id="filter-container">
            @yield('content_header')
        </div>
        <div class="content-wrapper kanban">
            <section class="content pb-3">
                @yield('content')
            </section>
        </div>
        <footer class="main-footer">
            <div class="float-right d-none d-sm-block">
                {{config('app.name', 'Laravel')}} | Copyright <strong> &copy;</strong> {{Date('Y')}} {{ trans('global.allRightsReserved') }}
            </div>
        </footer>
        <form id="logoutform" action="{{ route('logout') }}" method="POST" style="display: none;">
            {{ csrf_field() }}
        </form>
    </div>
    @includeIf('layouts.partials.javascript')
    @yield('scripts')
</body>

</html>