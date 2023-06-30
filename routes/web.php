<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TagsController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes(['register' => false]);

Route::permanentRedirect('/home', '/articles');
Route::redirect('/', '/articles');

Route::view('about-me', 'pages/about-me')->name('about-me');

Route::get('dashboard', DashboardController::class)->name('dashboard')->middleware(['auth']);

Route::resource('articles', ArticleController::class);

Route::controller(TagsController::class)->prefix('tags')->as('tags.')->group(function () {
    Route::post('/article/{article?}', 'store')->name('store');
    Route::put('/{tag}', 'update')->name('update');
    Route::delete('/{tag}', 'destroy')->name('destroy');
});

Route::controller(CategoryController::class)->prefix('categories')->as('categories.')->group(function () {
    Route::post('/', 'store')->name('store');
    Route::put('/{category}', 'update')->name('update');
    Route::delete('/{category}', 'destroy')->name('destroy');
});

