<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>

        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">

        <title> @yield('title') | {{ config('app.setting.site_name') }}管理 </title>
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="author" content="吾非藝術銀行"  />
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{ URL::asset('backend/assets/images/favicon.ico')}}">

        @include('backend.layouts.head-css')
        @yield('extra_css')
  </head>

    @yield('body')

    @yield('content')

    @include('backend.layouts.vendor-scripts')
    </body>
</html>
