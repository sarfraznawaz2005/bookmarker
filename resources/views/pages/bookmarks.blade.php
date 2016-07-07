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

    <div role="tabpanel">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active">
                <a href="#list" aria-controls="home" role="tab" data-toggle="tab" aria-expanded="true"><i
                            class="fa fa-list"></i> List</a>
            </li>
            <li role="presentation" class="">
                <a href="#add" aria-controls="profile" role="tab" data-toggle="tab" aria-expanded="false"><i
                            class="fa fa-plus-square"></i> Add Bookmark</a>
            </li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="list">
                <div class="whitebox">
                    <table class="datatable stacktable table table-striped table-bordered table-responsive"
                           cellspacing="0"
                           width="100%">
                        <thead>
                        <tr>
                            <th>Title</th>
                            <th>Folder</th>
                            <th>Comments</th>
                            <th align="center">Read</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($bookmarks as $bookmark)
                            <tr class="<?=$bookmark->isread == 1 ? 'trsuccess' : ''?>">
                                <td><a @if(strlen($bookmark->title) > 35) rel="popover" data-placement="top"
                                       data-content="{{$bookmark->title}}" data-container="body" @endif target="_blank"
                                       href="{{$bookmark->url}}">{{str_limit($bookmark->title, 35)}}</a></td>
                                <td>
                                    <a href="{{url('/show_folder/' . $bookmark->folder->id)}}">
                                        {{$bookmark->folder->name}}
                                    </a>
                                </td>
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
            </div>

            <div role="tabpanel" class="tab-pane" id="add">
                <div class="whitebox">
                    <form class="form-horizontal" role="form" method="post" action="{{url('store_bookmark')}}">

                        <div class="form-group">
                            <label class="control-label col-sm-2" for="url">URL</label>
                            <div class="col-sm-10">
                                <input autofocus type="url" name="url" class="form-control" id="url" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-sm-2" for="title">Title</label>
                            <div class="col-sm-10">
                                <input type="text" name="title" class="form-control" id="title" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-sm-2" for="folder_id">Folder</label>
                            <div class="col-sm-10">
                                <select name="folder_id" class="form-control col-sm-10" id="folder_id" required>
                                    <option value="">Select</option>

                                    @foreach($folders as $folder)
                                        <option value="{{$folder->id}}">{{$folder->name}}</option>
                                    @endforeach

                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-sm-2" for="comments">Comments</label>
                            <div class="col-sm-10">
                                <textarea name="comments" class="form-control" id="comments" cols="30"
                                          rows="4"></textarea>
                            </div>
                        </div>

                        <div class="pull-right">
                            <button type="submit" class="btn btn-info"><i class="fa fa-save"></i> Save</button>
                        </div>

                        {{ csrf_field() }}
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        $('#url').blur(function () {
            var url = $(this).val();

            if (!url.length) {
                return false;
            }

            // get page title
            $.get('{{url('/')}}/get_title', {"url": url}, function (title) {
                if (title) {
                    $('#title').val(title);
                }
            });
        });
    </script>
    @endpush

@endsection
