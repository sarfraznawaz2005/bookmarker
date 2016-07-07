@extends('layouts.app')

@section('content')

    <div role="tabpanel">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active">
                <a href="#list" aria-controls="home" role="tab" data-toggle="tab" aria-expanded="true"><i
                            class="fa fa-list"></i> List</a>
            </li>
            <li role="presentation" class="">
                <a href="#add" aria-controls="profile" role="tab" data-toggle="tab" aria-expanded="false"><i
                            class="fa fa-plus-square"></i> Add Folder</a>
            </li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="list">
                <div class="whitebox">
                    <table class="datatable table table-striped table-bordered table-responsive" cellspacing="0"
                           width="100%">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Bookmarks</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($folders as $folder)
                            <tr>
                                <td><a href="{{url('/show_folder/' . $folder->id)}}">{{$folder->name}}</a></td>
                                <td>{{count($folder->bookmarks)}}</td>
                                <td>{{$folder->created_at}}</td>
                                <td>
                                    {!! listingEditButton('/edit_folder/' . $folder->id) !!}
                                    {!! listingDeleteButton('/delete_folder/'. $folder->id, 'Folder') !!}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

            </div>

            <div role="tabpanel" class="tab-pane" id="add">
                <div class="whitebox">
                    <form role="form" method="post" action="{{url('store_folder')}}">
                        <div class="form-group">
                            <label for="name">Folder Name</label>
                            <input type="text" autofocus name="name" class="form-control" id="name" required>
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

@endsection