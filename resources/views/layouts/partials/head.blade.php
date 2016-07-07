<head>
    <title>{{$title or config('app.appname')}} - {{config('app.appname')}}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="shortcut icon" href="/favicon.ico">

    {!! Packer::css([
    '/lib/css/bootstrap.min.css',
    '/lib/css/font-awesome.min.css',
    '/lib/css/animate.min.css',
    '/lib/css/bootstrap-switch.min.css',
    '/lib/css/checkbox3.min.css',
    '/lib/css/jquery.dataTables.min.css',
    '/lib/css/dataTables.bootstrap.css',
    '/lib/css/datatables.responsive.css',
    '/lib/css/select2.min.css',
    '/css/style.css',
    '/css/themes/flat-blue.css',
    '/css/app.css',
    ],
    '/storage/cache/css/')
    !!}

    @stack('styles')
</head>