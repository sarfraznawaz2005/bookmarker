@extends('layouts.app')

@section('content')

    <a href="{{url('folders')}}" class="btn btn-default"><i class="fa fa-arrow-circle-left"></i> Folders</a>
    <br><br>

    <div class="whitebox">
        <form role="form" method="post" action="{{url('update_folder/'.$folder->id)}}">
            <div class="form-group">
                <label for="name">Folder Name</label>
                <input value="{{$folder->name}}" type="text" name="name" class="form-control" id="name" required>
            </div>

            <div class="pull-right">
                <button type="submit" class="btn btn-info"><i class="fa fa-save"></i> Update</button>
            </div>

            {{ csrf_field() }}
        </form>
    </div>

@endsection