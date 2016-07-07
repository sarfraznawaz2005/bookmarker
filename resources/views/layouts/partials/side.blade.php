<div class="side-menu sidebar-inverse">
    <nav class="navbar navbar-default" role="navigation">
        <div class="side-menu-container">
            <div class="navbar-header">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <div class="icon fa fa-bookmark"></div>
                    <div class="title">{{config('app.appname')}}</div>
                </a>
                <button type="button" class="navbar-expand-toggle pull-right visible-xs">
                    <i class="fa fa-times icon"></i>
                </button>
            </div>
            <ul class="nav navbar-nav">
                <li class="active">
                    <a rel="HomeController" href="{{ url('/') }}">
                        <span class="icon fa fa-tachometer"></span><span class="title">Dashboard</span>
                    </a>
                </li>

                <li>
                    <a rel="BookmarkController" href="{{url('/bookmarks')}}">
                        <span class="icon fa fa-bookmark"></span><span class="title">Bookmarks</span>
                    </a>
                </li>

                <li>
                    <a rel="FolderController" href="{{url('/folders')}}">
                        <span class="icon fa fa-folder"></span><span class="title">Folders</span>
                    </a>
                </li>

                @if($isAdmin)
                    <li>
                        <a rel="UserController" href="{{url('/users')}}">
                            <span class="icon fa fa-users"></span><span class="title">Users</span>
                        </a>
                    </li>
                @endif

                <li>
                    <a rel="SettingController" href="{{url('/setting')}}">
                        <span class="icon fa fa-cogs"></span><span class="title">Settings</span>
                    </a>
                </li>
                
                <li>
                    <a rel="ChromeController" href="{{url('/chrome')}}">
                        <span class="icon fa fa-chrome"></span><span class="title">Chrome Extension</span>
                    </a>
                </li>                

            </ul>
        </div>
        <!-- /.navbar-collapse -->
    </nav>
</div>