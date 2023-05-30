<?php

use App\Http\Controllers\ChangePassword;
use App\Http\Controllers\CUserManagement;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EditProfileController;
use App\Http\Controllers\GuestRegisterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ResetPassword;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\TransaksiEditController;
use App\Http\Controllers\TransaksiRegisterController;
use App\Http\Controllers\UserProfileController;
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

Route::GET('/', function () {return redirect('/profile');})->middleware('auth');

Route::GET('/login', [LoginController::class, 'show'])->middleware('guest')->name('login');
Route::POST('/login', [LoginController::class, 'login'])->middleware('guest')->name('login.perform');
Route::GET('/reset-password', [ResetPassword::class, 'show'])->middleware('guest')->name('reset-password');
Route::POST('/reset-password', [ResetPassword::class, 'send'])->middleware('guest')->name('reset.perform');
Route::GET('/change-password', [ChangePassword::class, 'show'])->middleware('guest')->name('change-password');
Route::POST('/change-password', [ChangePassword::class, 'update'])->middleware('guest')->name('change.perform');
Route::GET('/dashboard', [HomeController::class, 'index'])->name('home')->middleware('auth');

Route::group(['middleware' => 'auth','check.role'], function () {
    Route::GET('/profile', [UserProfileController::class, 'show'])->name('profile');
    Route::POST('/profile', [UserProfileController::class, 'update'])->name('profile.update');
    Route::POST('/profile_pic', [UserProfileController::class, 'ppicture'])->name('profile_ppicture');

    Route::GET('/transaksi-register', [TransaksiRegisterController::class, 'index'])->name('transaksi-register');
    Route::POST('/transaksi-register', [TransaksiRegisterController::class, 'register'])->name('transaksi-register-create');

//  Super Admin Gates
    Route::group(['middleware' => 'can:item-view'], function () {
        Route::GET('/user_management', [CUserManagement::class, 'index'])->name('user_management');
        Route::GET('/user_create', [UserProfileController::class, 'create'])->name('create_user');

//        Route::GET('/register', [GuestRegisterController::class, 'create'])->middleware('guest')->name('register');
//        Route::GET('/new_user', [RegisterController::class, 'create'])->middleware('auth')->name('register');
        Route::POST('/register', [RegisterController::class, 'store'])->middleware('auth')->name('register.perform');



        Route::GET('/edituser', [EditProfileController::class, 'index'])->name('editeuser');
        Route::POST('/edituser', [EditProfileController::class, 'updateuser'])->name('updateuser');
        Route::POST('/edituser_pic', [EditProfileController::class, 'updateuser_picture'])->name('updateuser_picture');

        Route::GET('/item-view', [ItemController::class, 'get'] )->name('item-view');
        Route::GET('/item-register', [ItemController::class, 'index'])->name('item-register');
        Route::POST('/item-register', [TransaksiController::class, 'create'])->name('item-create');

        Route::GET('/transaksi-view', [TransaksiController::class, 'get'])->name('transaksi-view');
        Route::POST('/transaksi-view-nofilter', [TransaksiController::class, 'get'])->name('transaksi-view-nofilter');
        Route::POST('/transaksi-view-filtersell', [TransaksiController::class, 'get'])->name('transaksi-view-filtersell');
        Route::POST('/transaksi-view-filterbuy', [TransaksiController::class, 'get'])->name('transaksi-view-filterbuy');

        Route::POST('/transaksi-edit', [TransaksiEditController::class, 'index'])->name('transaksi-edit');
        Route::POST('/transaksi-edit-update', [TransaksiEditController::class, 'update'])->name('transaksi-edit-update');
        Route::POST('/transaksi-delete', [TransaksiController::class, 'delete'])->name('transaksi-delete');

        Route::POST('/item-view', [ItemController::class, 'edit'])->name('item-edit');
        Route::POST('/item-delete', [ItemController::class, 'delete'])->name('item-delete');
        Route::GET('/dashboard-chart', [DashboardController::class, 'index'])->name('dashboard-chart');

        Route::GET('/{page}', [PageController::class, 'index'])->name('page');
    });
    Route::GET('/credits', function () {return view('pages.credits');})->name('credits');;
	Route::POST('logout', [LoginController::class, 'logout'])->name('logout');
});
