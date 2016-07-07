@extends('layouts.app')

@section('content')

    @include('popups.loader')

    @push('scripts')
    <script>
        $(window).load(function () {
            $('.loading-indicator-with-overlay').hide();
        });
    </script>
    @endpush

    <div class="whitebox">

        <a target="_blank" href="{{url('/')}}/annotate_folder/{{$folder->id}}/{{$bookmark->id}}" class="btn btn-info">
            <b class="glyphicon glyphicon-book"></b> Annotate Folder Bookmarks
        </a>
        <hr>

        <table class="datatable table table-striped table-bordered table-responsive" cellspacing="0"
               width="100%">
            <thead>
            <tr>
                <th>Title</th>
                <th>Comments</th>
                <th align="center">Read</th>
                <th>Created At</th>
                <th>Action</th>
            </tr>
            </thead>

            <tbody>

            {{-- set bookmarks sort diretion to descending --}}
            <?php \App\Models\Bookmark::$orderDirection = 'DESC'?>

            @foreach($folder->bookmarks as $bookmark)
                <tr class="<?=$bookmark->isread == 1 ? 'trsuccess' : ''?>">
                    <td><a @if(strlen($bookmark->title) > 35) rel="popover" data-placement="top"
                           data-content="{{$bookmark->title}}" data-container="body" @endif target="_blank"
                           href="{{$bookmark->url}}">{{str_limit($bookmark->title, 35)}}</a></td>
                    <td>{!! popoverShortText($bookmark->comments) !!}</td>
                    <td align="center">{{$bookmark->isread == 1 ? 'Yes' : 'No'}}</td>
                    <td>{{$bookmark->created_at}}</td>
                    <td>
                        {!! listingAnnotateButton('/annotate/' . $bookmark->id) !!}
                        {!! listingMarkReadButton('/read_status/' . $bookmark->id, $bookmark->isread) !!}
                        {!! listingEditButton('/edit_bookmark/' . $bookmark->id) !!}
                        {!! listingDeleteButton('/delete_bookmark/'. $bookmark->id, 'Bookmark') !!}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

@endsection