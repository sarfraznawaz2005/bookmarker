@extends('layouts.app')

@section('content')
    Other than using <strong>Add Bookmark</strong> option under <a class="alert-info" href="{{url('/bookmarks')}}">Bookmarks</a> page, you can also add bookmarks via Chrome browser
    extension which is located in <strong>public</strong> folder of this application.
    <br><br>
    See this <a class="alert-info" target="_blank"
                href="http://www.howtogeek.com/233355/how-to-install-extensions-from-outside-the-chrome-web-store-and-firefox-add-ons-gallery/">tutorial</a> on how to install it.

    <h3>Screenshot</h3>

    <div align="center">
        <img src="/img/extension.png" alt="Chrome Extension" title="Chrome Extension"><hr>
    </div>

@endsection