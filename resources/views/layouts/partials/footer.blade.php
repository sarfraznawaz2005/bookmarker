<footer class="app-footer">
    <div class="wrapper">
        {{config('app.appname')}} - {{date('Y')}}
    </div>
</footer>

{!! Packer::js([
'/lib/js/jquery.min.js',
'/lib/js/bootstrap.min.js',
'/lib/js/bootstrap-switch.min.js',
'/lib/js/jquery.matchHeight-min.js',
'/lib/js/jquery.dataTables.min.js',
'/lib/js/dataTables.bootstrap.min.js',
'/lib/js/datatables.responsive.js',
'/lib/js/select2.full.min.js',
'/js/plugins/jquery.pulsate.min.js',
'/js/app.js',
],
'/storage/cache/js/')
!!}

@stack('scripts')