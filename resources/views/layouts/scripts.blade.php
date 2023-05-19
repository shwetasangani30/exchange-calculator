<!-- JQuery -->
<script src="{{ asset('js/jquery/jquery-3.6.4.min.js') }}"></script>
<!-- Jquery Validate -->
<script src="{{ asset('js/jquery/jquery.validate.min.js') }}"></script>
<script src="{{ asset('js/jquery/additional-methods.min.js') }}"></script>

<script src="{{ asset('js/plugins/jquery.dataTables.min.js')}}"></script>
<script src="{{ asset('js/core/popper.min.js')}}"></script>
<script src="{{ asset('js/core/bootstrap.min.js')}}"></script>
<script src="{{ asset('js/plugins/perfect-scrollbar.min.js')}}"></script>
<script src="{{ asset('js/plugins/smooth-scrollbar.min.js')}}"></script>
<script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
        var options = {
            damping: '0.5'
        }
        Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
</script>

<script async defer src="{{ asset('js/plugins/buttons.js')}}"></script>

<script src="{{ asset('js/custom.js')}}"></script>