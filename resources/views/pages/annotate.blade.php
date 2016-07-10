<!doctype html>

<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{$bookmark->title}}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="shortcut icon" href="/favicon.ico">
</head>

<body>

@include('popups.loader')

<div id="annotator__header_elements_container">
    <div class="header-cont header">

        <div class="left">
            <form action="{{ url('/').'/delete_bookmark_annotate/'.$bookmark->id }}" method="POST">
                {{ csrf_field() }}
                {{ method_field('DELETE') }}

                <a href="#" id="delete_button" class="button center">
                    <i class="glyphicon glyphicon-trash"></i> Delete
                </a>
            </form>
        </div>

        @if($nextBookmark)
            <div class="right">
                <a class="link" title="{{$nextBookmark->title}}" href="{{url('/')}}/annotate/{{$nextBookmark->id}}">
                    Next <i class="glyphicon glyphicon-arrow-right"></i>
                </a>
            </div>
        @endif
        @if($prevBookmark)
            <div class="right">
                <a class="link" title="{{$prevBookmark->title}}" href="{{url('/')}}/annotate/{{$prevBookmark->id}}">
                    <i class="glyphicon glyphicon-arrow-left"></i> Prev
                </a>
            </div>
        @endif

        <div class="right title"><i class="fa fa-book"></i> Read: {{readPercentage()}} {{$count}}</div>

        <div class="center title" align="center">
            <strong><span>{{$bookmark->folder->name}}</span>
                <a class="url" target="_blank" href="{{$bookmark->url}}">{{str_limit($bookmark->title, 80)}}</a>
            </strong>
        </div>

        <div class="clear"></div>
    </div>
</div>

<div id="page-content"></div>

<link rel="stylesheet" href="/lib/css/bootstrap.min.css">
<link rel="stylesheet" href="/js/plugins/sweetalert/sweetalert.css">
<link rel="stylesheet" href="/css/annotator_styles.css">
<link rel="stylesheet" href="/js/plugins/annotator/annotator.min.css">

<script src="/lib/js/jquery.min.js"></script>
<script src="/js/plugins/annotator/annotator-full.min.js"></script>
<script src="/js/plugins/sweetalert/sweetalert.min.js"></script>

<script>

    // load page
    $.get('{{url('/')}}/load_page/{{$bookmark->id}}', function (html) {
        var $content = $('#page-content');
        html = html || '<h1 align="center">Error: Unable to load page...</h1>';

        $('.loading-indicator-with-overlay').hide();

        $content.html(html);
        var annotation = $content.annotator();

        // to manipulate annotations in database
        annotation.annotator('addPlugin', 'Store', {
            prefix: '{{url('/')}}/annotation',
            loadFromSearch: {
                page: {{$bookmark->id}}
            },
            annotationData: {
                page: {{$bookmark->id}}
            },
            urls: {
                create: '/store',
                update: '/update/:id',
                destroy: '/delete/:id',
                search: '/search'
            }
        });

    });

    // csrf ajax token setup
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // delete button
    $('#annotator__header_elements_container #delete_button').click(function () {

        var $form = $(this).closest('form');

        swal({
            title: "Are you sure?",
            text: "Delete this bookmark?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, delete it!",
            closeOnConfirm: false
        }, function () {
            $form.submit();
        });

        return false;
    });

</script>

</body>
</html>