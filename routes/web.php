<?php

use App\Http\Controllers\PhotoController;
use App\Models\Photo;
use Illuminate\Support\Facades\Auth;
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

Auth::routes();
Route::resource('photos', PhotoController::class);
Route::get('photos/delete/all/images', [PhotoController::class, 'deleteAll'])->name('photos.deleteAll');
Route::get('photos/download/{photo}', [PhotoController::class, 'download'])->name('photos.download');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
