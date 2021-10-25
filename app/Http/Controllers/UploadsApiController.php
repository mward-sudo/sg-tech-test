<?php

namespace App\Http\Controllers;

use App\Models\Homeowner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UploadsApiController extends Controller
{
    function show(Request $request)
    {
        $uploads = Homeowner::select('upload_file AS path', 'uploaded_at')
            ->distinct()
            ->orderBy('uploaded_at', 'desc')
            ->get();

        foreach ($uploads as $upload) {
            $upload->path = route('home-owners', $upload->path, false);
        }

        return view('partials/uploads-list', ['uploads' => $uploads]);
    }
}
