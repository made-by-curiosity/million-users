<?php

use App\Http\Controllers\Home;
use App\Http\Controllers\User;
use Illuminate\Support\Facades\Route;

Route::get('/', Home::class);
Route::get('/user', User::class);