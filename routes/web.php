<?php

use App\Http\Controllers\URLShortner\ShortURLController;
use Illuminate\Support\Facades\Redirect;
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


Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return Redirect::route('short-urls.index');
    });
    Route::resource('short-urls', ShortURLController::class)->except(['edit']);
});

// public route to redirect short url to original link
Route::get('s/{code}', [ShortURLController::class, 'redirectToOriginalLink'])->name('short-url.redirection');

require __DIR__.'/auth.php';
