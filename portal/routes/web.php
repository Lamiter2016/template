<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SocialController;
use App\Http\Controllers\CommicController;
use App\Http\Controllers\NovelController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;



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

// Route::get('/', function () {
//     return view('v1.master.master-public');
// });

//login
Route::get('/', [UsersController::class, 'login'])->name('login');
//register
Route::get('/register', [UsersController::class, 'register']);

Route::post('/login', [UsersController::class, 'processLogin'])->name('post.login');

Route::get('/home', [HomeController::class, 'index'])->name('home');
// Facebook Login URL
Route::prefix('facebook')->name('facebook.')->group(function () {
    Route::get('auth', [SocialController::class, 'loginUsingFacebook'])->name('login');
    Route::get('callback', [SocialController::class, 'callbackFromFacebook'])->name('callback');
});
route::get('/test', function () {
    return view('test');
});

//start google
Route::prefix('google')->name('google.')->group(function () {
    route::post('/upload', function (Request $request) {
        $file = $request->file("thing");
        $googleDisk = Storage::disk('google');
        $googleDisk->putFileAs("", $file, $file->getClientOriginalName());
    })->name('upload');
    route::get('/list', function (Request $request) {
        dd(Storage::disk('google')->files());
        foreach ($files as $item) {
            dd($item);
        }
    })->name('list');
    Route::get('put', function() {
        Storage::cloud()->put('test.txt', 'Hello World');
        return 'File was saved to Google Drive';
    });

    Route::get('put-existing', function() {
        $filename = 'laravel.png';
        $filePath = public_path($filename);
        $fileData = File::get($filePath);
        Storage::cloud()->put($filename, $fileData);
        return 'File was saved to Google Drive';
    });

    Route::get('list-files', function() {
        $recursive = false; // Get subdirectories also?
        $contents = collect(Storage::cloud()->listContents('/', $recursive));
        //return $contents->where('type', 'dir'); // directories
        return $contents->where('type', 'file')->mapWithKeys(function($file) {
            return [$file->path() => pathinfo($file->path(),PATHINFO_BASENAME)];
        });
    });

    Route::get('list-team-drives', function () {
        $service = Storage::cloud()->getAdapter()->getService();
        $teamDrives = collect($service->teamdrives->listTeamdrives()->getTeamDrives());

        return $teamDrives->mapWithKeys(function($drive) {
            return [$drive->id => $drive->name];
        });
    });

    Route::get('get', function() {
        // there can be duplicate file names!
        $filename = 'test.txt';

        $rawData = Storage::cloud()->get($filename); // raw content
        $file = Storage::cloud()->getAdapter()->getMetadata($filename); // array with file info

        return response($rawData, 200)
            ->header('ContentType', $file->mimeType())
            ->header('Content-Disposition', "attachment; filename=$filename");
    });

    Route::get('put-get-stream', function() {
        // Use a stream to upload and download larger files
        // to avoid exceeding PHP's memory limit.

        // Thanks to @Arman8852's comment:
        // https://github.com/ivanvermeyen/laravel-google-drive-demo/issues/4#issuecomment-331625531
        // And this excellent explanation from Freek Van der Herten:
        // https://murze.be/2015/07/upload-large-files-to-s3-using-laravel-5/

        // Assume this is a large file...
        $filename = 'laravel.png';
        $filePath = public_path($filename);

        // Upload using a stream...
        Storage::cloud()->put($filename, fopen($filePath, 'r+'));
        $file = Storage::cloud()->getAdapter()->getMetadata($filename); // array with file info

        // Store the file locally...
        //$readStream = Storage::cloud()->getDriver()->readStream($filename);
        //$targetFile = storage_path("downloaded-{$filename}");
        //file_put_contents($targetFile, stream_get_contents($readStream), FILE_APPEND);

        // Stream the file to the browser...
        $readStream = Storage::cloud()->getDriver()->readStream($filename);

        return response()->stream(function () use ($readStream) {
            fpassthru($readStream);
        }, 200, [
            'Content-Type' => $file->mimeType(),
            //'Content-disposition' => 'attachment; filename='.$filename, // force download?
        ]);
    });

    Route::get('create-dir', function() {
        Storage::cloud()->makeDirectory('Test Dir');
        return 'Directory was created in Google Drive';
    });

    Route::get('create-sub-dir', function() {
        // Create parent dir
        Storage::cloud()->makeDirectory('Test Dir/Sub Dir');
        return 'Sub Directory was created in Google Drive';
    });

    Route::get('put-in-dir', function() {
        Storage::cloud()->put('Test Dir/test.txt', 'Hello World');
        return 'File was created in the sub directory in Google Drive';
    });

    Route::get('list-folder-contents', function() {
        // The human readable folder name to get the contents of...
        // For simplicity, this folder is assumed to exist in the root directory.
        $folder = 'Test Dir';

        // Get directory contents...
        $files = collect(Storage::cloud()->listContents($folder, false));

        return $files->mapWithKeys(function($file) {
            return [$file->path() => pathinfo($file->path(),PATHINFO_BASENAME)];
        });
    });

    Route::get('delete', function() {
        $path = 'Test Dir/test.txt';
        Storage::cloud()->put($path, 'Hello World');
        Storage::cloud()->delete($path);
        return 'File was deleted from Google Drive';
    });

    Route::get('delete-dir', function() {
        $directoryName = 'Test Dir';

        // First we need to create a directory to delete
        Storage::cloud()->makeDirectory($directoryName);
        Storage::cloud()->deleteDirectory($directoryName);

        return 'Directory was deleted from Google Drive';
    });

    Route::get('rename-dir', function() {
        $directoryName = 'test';

        Storage::cloud()->makeDirectory($directoryName);
        Storage::cloud()->move($directoryName, 'new-test');

        return 'Directory was renamed in Google Drive';
    });

    Route::get('share', function() {
        $filename = 'test.txt';
        // Store a demo file with public permission
        Storage::cloud()->put($filename, 'Hello World', 'public');
        return Storage::cloud()->url($filename);
    });

    Route::get('export/{filename}', function ($filename) {
        $service = Storage::cloud()->getAdapter()->getService();
        $file = Storage::cloud()->getAdapter()->getMetadata($filename);

        $mimeType = 'application/pdf';
        $export = $service->files->export($file->extraMetadata()['id'], $mimeType);

        return response($export->getBody(), 200, [
            'Content-Type' => $mimeType,
            'Content-disposition' => 'attachment; filename='.$filename.'.pdf',
        ]);
    });

});

//end google

route::get('/list', function (Request $request) {
    $recursive = false; // Get subdirectories also?
    dd(Storage::cloud()->files('/comic/fantasista/89'));

    })->name('listall');
route::post('/upload', function (Request $request) {
    $file = $request->file("thing");
    $googleDisk = Storage::disk('google');
    dd($googleDisk->putFileAs("", $file, $file->getClientOriginalName()));
});
//library commic
Route::prefix('library')->name('library.')->group(function () {
    Route::get('commic', [CommicController::class, 'index'])->name('commic');
    Route::get('novel', [NovelController::class, 'detail'])->name('novelDetail');
});

//END library commic
Route::get('/clear', function() {
    $route = Artisan::call('route:cache');
    $config = Artisan::call('config:cache');
    $cache = Artisan::call('cache:clear');
    $view = Artisan::call('view:clear');
    $view = Artisan::call('route:clear');
    $view = Artisan::call('optimize:clear');
    return 'cache has just been removed';
});
