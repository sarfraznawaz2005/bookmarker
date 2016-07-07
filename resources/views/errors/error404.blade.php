<!DOCTYPE html>
<html>
<head>
    <title>404 Error</title>

    <style>
        html, body {
            height: 100%;
            color: #666;
        }

        body {
            margin: 0;
            padding: 0;
            width: 100%;
            display: table;
            font-weight: 100;
        }

        .container {
            text-align: center;
            display: table-cell;
            vertical-align: middle;
        }

        .content {
            text-align: center;
            display: inline-block;
        }

        .title {
            font-size: 72px;
            margin-bottom: 40px;
        }

        a {
            color: #666;
            font-size: 24px;
            text-decoration: none;
            font-weight: bold;
        }

        a:hover {
            color: orangered;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="content">
        <div class="title">404 Error</div>
        <p>The page you were looking for could not be found.</p>
        <hr>
        <p><a href="{!!url('/') !!}">&larr; Back To Home Page</a></p>
    </div>
</div>
</body>
</html>
