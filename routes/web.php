<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\URLShortner\ShortURLController;
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
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('short-urls', ShortURLController::class)->except(['destroy', 'edit', 'update']);
});

// public route to redirect short url to original link
Route::get('s/{code}', [ShortURLController::class, 'redirectToOriginalLink'])->name('short-url.redirection');

require __DIR__.'/auth.php';
