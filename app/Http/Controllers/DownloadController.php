<?php

namespace App\Http\Controllers;

use App\Http\Requests;

class DownloadController extends Controller
{
    public function download($fileName)
    {
        $file_path = public_path('files/' . $fileName);

        return response()->download($file_path);
    }
}
