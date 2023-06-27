<?php

use App\Http\Controllers\ArticleController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes(['register' => false]);

Route::permanentRedirect('/home', '/articles');
Route::redirect('/', '/articles');
Route::resource('articles', ArticleController::class);
