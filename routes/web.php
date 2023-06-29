<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\TagsController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes(['register' => false]);

Route::permanentRedirect('/home', '/articles');
Route::redirect('/', '/articles');
Route::resource('articles', ArticleController::class);

Route::controller(TagsController::class)->prefix('tags')->as('tags.')->group(function () {
    Route::post('/article/{article?}', 'store')->name('store');
    Route::put('/{tag}', 'update')->name('update');
    Route::delete('/{tag}', 'destroy')->name('destroy');
});
