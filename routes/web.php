<?php

use App\Http\Controllers\FacebookLoginController;
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

Route::get('/login', function () {
    return view('index');
})->name('login');
Route::get('/logout', [FacebookLoginController::class, 'logout'])->name('logout');
Route::post('/login/redirect', [FacebookLoginController::class, 'login'])->name('facebook.login');

Route::get('/dashboard', function(){
    return view('dashboard');
})->name('dashboard');