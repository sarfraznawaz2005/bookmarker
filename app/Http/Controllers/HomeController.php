<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\Bookmark;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @param Bookmark $bookmark
     * @return \Illuminate\Http\Response
     */
    public function index(Bookmark $bookmark)
    {
        $title = 'Dashboard';

        $folders = auth()->user()->folders()->with('bookmarks')->get();

        // first bookmark to start annotation from for this folder
        $bookmark::$orderDirection = 'DESC';

        $bookmark = $bookmark
            ->where('user_id', auth()->user()->id)
            ->limit(1)
            ->first();

        return view('pages.home', compact('title', 'folders', 'bookmark'));
    }
}
