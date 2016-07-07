<!DOCTYPE html>
<html lang="en">

@include('layouts.partials.head')

<body class="flat-blue">

<div class="app-container expanded">
    <div class="row content-container">
        @include('layouts.partials.nav')
        @include('layouts.partials.side')

        <div class="container-fluid animated fadeIn">
            <div class="side-body padding-top">
                @include('flash::message')
                @include('shared.errors')

                @yield('content')
            </div>
        </div>
    </div>

    @include('shared.vars')
    @include('layouts.partials.footer')
</div>

@include('popups.delete_confirm')

</body>
</html>
