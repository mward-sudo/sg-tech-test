<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\HomeOwnersController;
use App\Models\Homeowner;
use App\Names\Parser;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('index');
});

Route::get('home-owners/{uploadFile}', 'App\Http\Controllers\HomeOwnersController@show')->name('home-owners');

// Uploads
Route::get('upload-ui', [FileUploadController::class, 'dropzoneUi']);
// Route::post('file-upload', [FileUploadController::class, 'dropzoneFileUpload' ])->name('dropzoneFileUpload');
Route::post(
    'file-upload',
    'App\Http\Controllers\FileUploadController@dropzoneFileUpload'
)->name('dropzoneFileUpload');
