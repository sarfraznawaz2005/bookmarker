<?php

namespace App\Http\Controllers;

use App\Http\Requests;

class ChromeExtension extends Controller
{
    /**
     * Display a listing of the chrome extension
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.chrome', ['title' => 'Chrome Extension']);
    }
}
