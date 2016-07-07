@extends('layouts.app')

@section('content')

    <div role="tabpanel">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active">
                <a href="#profile" aria-controls="home" role="tab" data-toggle="tab" aria-expanded="true"><i
                            class="fa fa-edit"></i> Edit Profile</a>
            </li>
            <li role="presentation" class="">
                <a href="#importexport" aria-controls="profile" role="tab" data-toggle="tab" aria-expanded="false"><i
                            class="fa fa-exchange"></i> Import / Export</a>
            </li>
            <li role="presentation" class="">
                <a href="#account" aria-controls="profile" role="tab" data-toggle="tab" aria-expanded="false"><i
                            class="fa fa-user"></i> Account</a>
            </li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="profile">
                <div class="whitebox">

                    <form role="form" method="post" action="{{url('store_profile')}}">

                        <div class="form-group">
                            <label for="name">Name</label>
                            <input value="{{auth()->user()->name}}" type="text" name="name" class="form-control"
                                   id="name">
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input autocomplete="off" value="{{auth()->user()->email}}" type="email" name="email"
                                   class="form-control" id="email">
                        </div>

                        <div class="alert-warning" style="padding:15px; margin-top: 5px;">
                            <h2>Change Password</h2>

                            <p class="help-block">
                                <b class="fa-bullhorn fa"></b>
                                Leave this blank to keep your existing password.
                            </p>

                            <div class="form-group">
                                <label for="password">New Password</label>
                                <input autocomplete="off" type="password" name="password" class="form-control"
                                       id="password">
                            </div>
                            <div class="form-group">
                                <label for="password_confirmation">Confirm New Password</label>
                                <input autocomplete="off" type="password" name="password_confirmation"
                                       class="form-control"
                                       id="password_confirmation">
                            </div>
                        </div>

                        <br>
                        <div class="pull-right">
                            <button type="submit" class="btn btn-info"><i class="fa fa-save"></i> Update</button>
                        </div>

                        {{ csrf_field() }}
                    </form>
                </div>
            </div>

            <div role="tabpanel" class="tab-pane" id="importexport">
                <div class="whitebox">
                    <form class="form-horizontal" enctype="multipart/form-data" role="form" method="post" action="{{url('import_bookmarks')}}">

                        <div class="form-group">
                            <label for="bookmarks_file">Choose Bookmarks File</label>
                            <input required type="file" name="bookmarks_file" class="form-control" id="bookmarks_file">
                        </div>

                        <button type="submit" class="btn btn-success"><i class="glyphicon glyphicon-import"></i> Import
                            Bookmarks
                        </button>
                        {{ csrf_field() }}
                    </form>

                    <hr>

                    <form class="form-horizontal" role="form" method="post" action="{{url('export_bookmarks')}}">
                        <button type="submit" class="btn btn-info"><i class="glyphicon glyphicon-export"></i> Export
                            Bookmarks
                        </button>
                        {{ csrf_field() }}
                    </form>
                </div>
            </div>

            <div role="tabpanel" class="tab-pane" id="account">
                <div class="whitebox">

                    <p class="alert  alert-warning">
                        <strong><i class="fa fa-warning"></i> Caution:</strong>
                        <br>
                        This option will delete your account permanently from {{config('app.appname')}}
                        and you will loose all your bookmarks.
                    </p>

                    <a data-placement="top" data-tooltip data-original-title="Delete Account" title="Delete Account"
                       class="delete_btn confirm-delete btn btn-danger" data-label="Delete Account" rel="delete_account"
                       href="javascript:void(0);">
                        <b class="fa fa-trash"></b> Delete Account Permanently
                    </a>
                </div>
            </div>
        </div>
    </div>

@endsection