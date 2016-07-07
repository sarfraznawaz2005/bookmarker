@extends('layouts.app')

@section('content')

    <a href="{{url('bookmarks')}}" class="btn btn-default"><i class="fa fa-arrow-circle-left"></i> Bookmarks</a>
    <br><br>

    <div class="whitebox">
        <form class="form-horizontal" role="form" method="post" action="{{url('update_bookmark/'.$bookmark->id)}}">
            <div class="form-group">
                <label class="control-label col-sm-2" for="url">URL</label>
                <div class="col-sm-10">
                    <input value="{{$bookmark->url}}" type="url" name="url" class="form-control" id="url" required>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-2" for="title">Title</label>
                <div class="col-sm-10">
                    <input value="{{$bookmark->title}}" type="text" name="title" class="form-control" id="title" required>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-2" for="folder_id">Folder</label>
                <div class="col-sm-10">
                    <select name="folder_id" class="form-control col-sm-10" id="folder_id" required>
                        <option value="">Select</option>

                        @foreach($folders as $folder)
                            <option <?=($folder->id === $bookmark->folder->id) ? 'selected' : ''?> value="{{$folder->id}}">{{$folder->name}}</option>
                        @endforeach

                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-2" for="comments">Comments</label>
                <div class="col-sm-10">
                                <textarea name="comments" class="form-control" id="comments" cols="30"
                                          rows="4">{{$bookmark->comments}}</textarea>
                </div>
            </div>

            <div class="pull-right">
                <button type="submit" class="btn btn-info"><i class="fa fa-save"></i> Save</button>
            </div>

            {{ csrf_field() }}
        </form>
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