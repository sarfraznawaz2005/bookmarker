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

            <li style="margin-top: 22px; margin-right: 5px; border:none;">
                <span style="padding: 10px;" class="alert-info"><i class="fa fa-book"></i> Read: <strong>{{readPercentage()}} ({{readPagesStats()}})</strong></span>
            </li>

            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                    <i class="fa fa-user"></i> {{ Auth::user()->name }}
                    <span class="caret"></span>
                </a>

                <ul class="dropdown-menu animated fadeInDown">
                    <li><a href="{{ url('/setting') }}"><i class="fa fa-gear fa-fw"></i> Settings</a></li>
                    <li class="divider"></li>
                    <li><a href="{{ url('/logout') }}"><i class="fa fa-sign-out fa-fw"></i> Logout</a></li>
                </ul>
            </li>
        </ul>
    </div>
</nav>
