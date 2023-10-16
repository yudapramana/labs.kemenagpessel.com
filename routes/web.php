<?php

use Illuminate\Support\Facades\Route;

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
    return view('welcome');
});


Route::prefix('file')->group(function () {

    Route::post('/upload', function (Request $request) {
        Storage::disk('google')->putFileAs('', $request->file('thing'), 'filename.jpg');
    })->name('upload');

    Route::get('/create/{filename}/{content}', function ($filename, $content) {
        $upload = Storage::disk('google')->put($filename, $content);
        $meta = Storage::disk("google")
                ->getAdapter()
                ->getMetadata($filename);

        dd($meta);
    });

    Route::get('list', function () {
        
        $files = Storage::disk("google")->files();
        // or
        // $files = Storage::disk("google")->AllFiles();

        return $files;
    });

    Route::get('details', function () {
        $files = Storage::disk("google")->files();
        dump($files);
     
        $firstFileName = $files[0];
        dump("FILE NAME: " . $firstFileName);
     
        $details = Storage::disk('google')->getMetadata($firstFileName);
        dump($details);
     
        $url = Storage::disk('google')->url($firstFileName);
        dump("Download URL (Session based): ");
        dump($url);
     });

     Route::get('/visibility', function () {
        $files = Storage::disk("google")->files();
        $firstFileName = $files[0];

        Storage::disk('google')->setVisibility($firstFileName, 'private');
        $visibility = Storage::disk('google')->getVisibility($firstFileName);
        dump('Visibility: ' . $visibility);

     });

     Route::get('/rename', function () {
        $firstFile = Storage::disk("google")->files()[0];
        Storage::disk('google')->rename($firstFile, 'RenameName');
     });

     Route::get('/download', function () {
        $firstFile = Storage::disk("google")->files()[0];
        $details = Storage::disk('google')->getMetadata($firstFile);
        $filename = $details['name'];

        $response = Storage::disk('google')->download($firstFile, $filename);
        $response->send();
     });

     Route::get('/get/{id}', function ($id) {
        $firstFile = $id;
        $details = Storage::disk('google')->getMetadata($firstFile);
        $filename = $details['name'];

        $response = Storage::disk('google')->download($firstFile, $filename);
        $response->send();
     });

});

Route::get('/directory/create/{dirname}', function ($dirname) {
    Storage::disk('google')->makeDirectory($dirname);
});

Route::get('/directory/list', function () {
    $dirs = Storage::disk('google')->directories();
    dd($dirs);
});



