@extends('layouts.app')

@section('content')
    <div class="whitebox">
        <table class="datatable table table-striped table-bordered table-responsive" cellspacing="0"
               width="100%">
            <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Folders</th>
                <th>Bookmarks</th>
                <th>Admin</th>
                <th>Created At</th>
                <th>Action</th>
            </tr>
            </thead>

            <tbody>
            @foreach($users as $user)
                <tr>
                    <td>{{$user->name}}</td>
                    <td>{{$user->email}}</td>
                    <td>{{count($user->folders)}}</td>
                    <td>{{count($user->bookmarks)}}</td>
                    <td>{{$user->isadmin == 1 ? 'Yes' : 'No'}}</td>
                    <td>{{$user->created_at}}</td>
                    <td>
                        {!! listingLoginButton('/login_user/'. $user->id) !!}
                        {!! listingMarkAdminButton('/admin_status/'. $user->id, $user->isadmin) !!}
                        {!! listingDeleteButton('/delete_user/'. $user->id, 'User') !!}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        {{ $users->links() }}

    </div>
@endsection