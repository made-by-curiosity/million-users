<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/users', 301);

Route::resource('users', UserController::class)->except(['destroy']);