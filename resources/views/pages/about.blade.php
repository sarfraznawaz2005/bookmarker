<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="shortcut icon" href="/favicon.png">

    <title>About - {{config('app.appname')}}</title>

    <link rel="stylesheet" type="text/css" href="../lib/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../lib/css/font-awesome.min.css">

</head>
<body id="app-layout">
<nav class="navbar navbar-default navbar-static-top">
    <div class="container">
        <div class="navbar-header">

            <!-- Collapsed Hamburger -->
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                    data-target="#app-navbar-collapse">
                <span class="sr-only">Toggle Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <!-- Branding Image -->
            <a class="navbar-brand" href="{{ url('/') }}">
                {{config('app.appname')}}
            </a>
        </div>

        <div class="collapse navbar-collapse" id="app-navbar-collapse">
            <!-- Left Side Of Navbar -->
            <ul class="nav navbar-nav">
                <li><a href="{{ url('/about') }}">About</a></li>
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="nav navbar-nav navbar-right">
                <!-- Authentication Links -->
                @if (Auth::guest())
                    <li><a href="{{ url('/login') }}">Sign In</a></li>
                    <li><a href="{{ url('/register') }}">Sign Up</a></li>
                @else
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a>

                        <ul class="dropdown-menu" role="menu">
                            <li><a href="{{ url('/logout') }}"><i class="fa fa-btn fa-sign-out"></i>Logout</a></li>
                        </ul>
                    </li>
                @endif
            </ul>
        </div>

    </div>
</nav>

<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="well">
                <p>
                    BookMarker is simple app made in Laraval 5.2 framework using SQLite that can be used to bookmark
                    important pages from the web to read them later. This is useful when you want to do research
                    about some topic and organize page links according to category/folder. Another good option
                    is that bookmarked pages can be annotated with highlights and comments. Pages can be
                    bookmarked either manually via Admin interface or through provided Chrome browser
                    extension.
                </p>
                <h2>Why</h2>
                <p>Because it is never easy to manage lots of bookmarks in the browser.</p>
            </div>

            <h2>Screenshots</h2>

            <div align="center">
                <strong>Dashboard</strong><br>
                <img src="/img/dashboard.png" alt="Dashboard" title="Dashboard"><hr>
                <strong>Bookmarks Page</strong><br>
                <img src="/img/bookmarks.png" alt="Bookmarks Page" title="Bookmarks Page"><hr>
                <strong>Add Bookmark</strong><br>
                <img src="/img/bookmarks_add.png" alt="Add Bookmark" title="Add Bookmark"><hr>
                <strong>Folders Page</strong><br>
                <img src="/img/folders.png" alt="Folders Page" title="Folders Page"><br>
            </div>

            <div class="row">&nbsp;</div>

        </div>
    </div>
</div>

<script type="text/javascript" src="../lib/js/jquery.min.js"></script>
<script type="text/javascript" src="../lib/js/bootstrap.min.js"></script>

</body>
</html>
