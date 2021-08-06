<!-- JAVASCRIPT -->
<script src="{{ URL::asset('backend/assets/libs/jquery/jquery.min.js')}}"></script>
<script src="{{ URL::asset('backend/assets/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{ URL::asset('backend/assets/libs/metismenu/metisMenu.min.js')}}"></script>
<script src="{{ URL::asset('backend/assets/libs/simplebar/simplebar.min.js')}}"></script>
<script src="{{ URL::asset('backend/assets/libs/node-waves/waves.min.js')}}"></script>
<script src="{{ URL::asset('backend/assets/libs/feather-icons/feather.min.js')}}"></script>
<!-- pace js -->
<script src="{{ URL::asset('backend/assets/libs/pace-js/pace.min.js')}}"></script>

<script src="{{ URL::asset('backend/assets/libs/alertifyjs/build/alertify.min.js') }}"></script>


@yield('script')

<!-- App js -->
<script src="{{ URL::asset('backend/assets/js/app.js')}}"></script>
<!-- Global function js -->
<script type="text/javascript">
    function fnNotifications(message){
        alertify.message(message);
    }
</script>
@yield('script-bottom')
