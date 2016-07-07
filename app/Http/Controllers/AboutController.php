<?php

namespace App\Http\Controllers;

use App\Http\Requests;

class AboutController extends Controller
{
    /**
     * Display a listing of the about page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.about', ['title' => 'About']);
    }
}
