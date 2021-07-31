<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FileUploadController extends Controller
{
    /**
     * Generate Upload View
     *
     * @return void
     */
    public  function dropzoneUi()
    {
        return view('upload-view');
    }

    /**
     * File Upload Method
     *
     * @return void
     */
    public  function dropzoneFileUpload(Request $request)
    {
        $file = $request->file('file');
        $time = time();

        $fileName = "{$time}.{$file->extension()}";
        $file->move(public_path('upload'), $fileName);

        return response()->json(['success' => $fileName]);
    }
}
