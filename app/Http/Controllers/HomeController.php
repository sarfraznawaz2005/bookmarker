<?php

namespace App\Http\Controllers;

use App\Http\Requests;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Dashboard';

        $folders = auth()->user()->folders()->with('bookmarks')->get();

        return view('pages.home', compact('title', 'folders'));
    }
}
