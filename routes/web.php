<?php

use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\EventsController;
use App\Http\Controllers\admin\UsersController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
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

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/detailevent/{id}', [HomeController::class, 'detailevent']);
Route::get('/pendaftaran/{id}', [HomeController::class, 'pendaftaran'])->middleware('role:pendaftar,admin');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::prefix('auth')->as('auth.')->middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'index'])->name('login');
    Route::get('register', [AuthController::class, 'register'])->name('register');
    Route::post('login-process', [AuthController::class, 'process_login']);
    Route::post('register-process', [AuthController::class, 'process_register']);
});


Route::prefix('admin')->as('admin.')->middleware('role:admin')->group(function () {
    Route::resource('dashboard', DashboardController::class);
    Route::resource('events', EventsController::class);

    Route::get('/users/admin', [UsersController::class, 'admin'])->name('users.admin');
    Route::get('/users/pendaftar', [UsersController::class, 'pendaftar'])->name('users.pendaftar');
    Route::resource('users', UsersController::class);
});
