<nav class="navbar navbar-default navbar-fixed-top navbar-top">

    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-expand-toggle">
                <i class="fa fa-bars icon"></i>
            </button>
            <ol class="breadcrumb navbar-breadcrumb">
                <li class="active">{{$title or ''}}</li>
            </ol>
            <button type="button" class="navbar-right-expand-toggle pull-right visible-xs">
                <i class="fa fa-th icon"></i>
            </button>
        </div>

        <ul class="nav navbar-nav navbar-right">
            <button type="button" class="navbar-right-expand-toggle pull-right visible-xs">
                <i class="fa fa-times icon"></i>
            </button>
            <li style="border: none;">
                <form method="get" action="{{ url('/search') }}" class="navbar-text form-inline" style="height: 18px; margin-top: 9px;">
                    <input name="keyword" style="width: 150px; border-right:none;" type="text" class="form-control" placeholder="Search Bookmarks">
                    <button type="submit" style="margin-left: -5px;" class="btn btn-info" href="#"><i class="glyphicon glyphicon-search"></i></button>
                    {!! csrf_field() !!}
                </form>
            </li>
            <li>
                <a style="font-size: 16px; padding:0 12px;">
                    <strong class="text-primary"><i class="fa fa-book text-primary"></i> Read: {{readPercentage()}} ({{readPagesStats()}})</strong>
                </a>
            </li>
            <li class="dropdown profile">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                    <i class="fa fa-user"></i> {{ Auth::user()->name }} <span class="caret"></span>
                </a>
                <ul class="dropdown-menu animated fadeInDown">
                    <li>
                        <div class="profile-info">
                            <h4 class="username">{{ Auth::user()->name }}</h4>
                            <p>{{ Auth::user()->email }}</p>
                            <div class="btn-group margin-bottom-2x" role="group">
                                <a class="btn btn-default" href="{{ url('/setting') }}"><i class="fa fa-gear fa-fw"></i>
                                    Setting</a>
                                <a class="btn btn-default" href="{{ url('/logout') }}"><i
                                            class="fa fa-sign-out fa-fw"></i> Logout</a>
                            </div>
                        </div>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>