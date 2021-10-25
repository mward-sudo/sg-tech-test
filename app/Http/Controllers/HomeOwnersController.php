<?php

namespace App\Http\Controllers;

use App\Models\Homeowner;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeOwnersController extends Controller
{
    public static function show(string $uploadFile)
    {
        $homeOwners = Homeowner::where('upload_file', $uploadFile)->get();

        if (count($homeOwners) === 0) {
            abort(404);
        }

        return view('home-owners', ['homeOwners' => $homeOwners]);
    }
}
