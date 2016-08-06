<?php

#############################################################
### PUBLIC ROUTES
#############################################################

// auth-related routes...
Route::auth();

// about page
Route::get('/about', 'AboutController@index');

// for chrome extension
Route::get('/save_bookmark', 'BookmarkController@saveBookmark');
Route::get('/get_folders/{email}/{password}', 'FolderController@getFolders');

#############################################################
### AUTHENTICATED ROUTES
#############################################################

Route::group(['middleware' => 'auth'], function () {

    // for logger
    Route::get('applogs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');

    // home
    Route::get('/', 'HomeController@index');
    Route::get('/home', 'HomeController@index');

    // settings
    Route::get('/setting', 'SettingController@index');
    Route::post('/store_profile', 'SettingController@store');
    Route::delete('/delete_account', 'SettingController@deleteAccount');
    Route::post('/import_bookmarks', 'SettingController@import');
    Route::post('/export_bookmarks', 'SettingController@export');

    // folder
    Route::get('/folders', 'FolderController@index');
    Route::post('/store_folder', 'FolderController@store');
    Route::get('/edit_folder/{id}', 'FolderController@edit');
    Route::post('/update_folder/{id}', 'FolderController@update');
    Route::delete('/delete_folder/{id}', 'FolderController@destroy');
    Route::get('/show_folder/{id}', 'FolderController@show');

    // bookmark
    Route::get('/bookmarks', 'BookmarkController@index');
    Route::post('/store_bookmark', 'BookmarkController@store');
    Route::get('/edit_bookmark/{id}', 'BookmarkController@edit');
    Route::post('/update_bookmark/{id}', 'BookmarkController@update');
    Route::delete('/delete_bookmark/{id}', 'BookmarkController@destroy');
    Route::delete('/delete_bookmark_annotate/{id}', 'BookmarkController@destroyAnnotate');
    Route::delete('/delete_bookmark_annotate_folder/{folder_id}/{id}', 'BookmarkController@destroyAnnotateFolder');
    Route::get('/read_status/{id}', 'BookmarkController@setReadStatus');
    Route::get('/annotate/{id}', 'BookmarkController@annotate');
    Route::get('/annotate_folder/{id}/{bookmark_id}', 'BookmarkController@annotateFolder');
    Route::get('/load_page/{id}', 'BookmarkController@loadPage');
    Route::post('/save_page', 'BookmarkController@savePage');
    Route::get('/get_title', 'BookmarkController@getTitle');
    Route::get('/search', 'BookmarkController@search');

    // chrome extension
    Route::get('/chrome', 'ChromeExtension@index');

    // users
    Route::get('/users', 'UserController@index');
    Route::get('/login_user/{id}', 'UserController@loginUser');
    Route::delete('/delete_user/{id}', 'UserController@destroy');
    Route::get('/admin_status/{id}', 'UserController@setAdminStatus');

    // downloads
    Route::get('/download/{file}', 'DownloadController@download');

    // for annotations
    Route::get('/annotation/search', 'AnnotationController@search');
    Route::post('/annotation/store', 'AnnotationController@store');
    Route::put('/annotation/update/{id}', 'AnnotationController@update');
    Route::delete('/annotation/delete/{id}', 'AnnotationController@delete');
});