@yield('css')

<!-- preloader css -->
<link rel="stylesheet" href="{{ URL::asset('backend/assets/css/preloader.min.css') }}" type="text/css"/>
<!-- Bootstrap Css -->
<link href="{{ URL::asset('backend/assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css"/>
<!-- alertifyjs Css -->
<link href="{{ URL::asset('backend/assets/libs/alertifyjs/build/css/alertify.min.css') }}" rel="stylesheet" type="text/css" />
<!-- alertifyjs default themes  Css -->
<link href="{{ URL::asset('backend/assets/libs/alertifyjs/build/css/themes/default.min.css') }}" rel="stylesheet" type="text/css" />
<!-- Icons Css -->
<link href="{{ URL::asset('backend/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css"/>
<!-- App Css-->
<link href="{{ URL::asset('backend/assets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css"/>
<!-- wfly Css-->
<link href="{{ URL::asset('backend/assets/css/wfly.css') }}" id="wfly-style" rel="stylesheet" type="text/css"/>

@yield('css_raw')
