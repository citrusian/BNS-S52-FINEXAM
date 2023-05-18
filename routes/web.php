<?php

use App\Http\Controllers\ChangePassword;
use App\Http\Controllers\CUserManagement;
use App\Http\Controllers\EditProfileController;
use App\Http\Controllers\GuestRegisterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ResetPassword;
use App\Http\Controllers\TransaksiController;
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


Route::get('/', function () {return redirect('/dashboard');})->middleware('auth');

Route::get('/register', [GuestRegisterController::class, 'create'])->middleware('guest')->name('register');
//Route::post('/register', [GuestRegisterController::class, 'store'])->middleware('guest')->name('register.perform');


Route::get('/new_user', [RegisterController::class, 'create'])->middleware('auth')->name('register');
Route::post('/register', [RegisterController::class, 'store'])->middleware('auth')->name('register.perform');

Route::get('/login', [LoginController::class, 'show'])->middleware('guest')->name('login');
Route::post('/login', [LoginController::class, 'login'])->middleware('guest')->name('login.perform');
Route::get('/reset-password', [ResetPassword::class, 'show'])->middleware('guest')->name('reset-password');
Route::post('/reset-password', [ResetPassword::class, 'send'])->middleware('guest')->name('reset.perform');
Route::get('/change-password', [ChangePassword::class, 'show'])->middleware('guest')->name('change-password');
Route::post('/change-password', [ChangePassword::class, 'update'])->middleware('guest')->name('change.perform');
Route::get('/dashboard', [HomeController::class, 'index'])->name('home')->middleware('auth');

Route::group(['middleware' => 'auth'], function () {
    Route::get('/profile', [UserProfileController::class, 'show'])->name('profile');
    Route::post('/profile', [UserProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile2', [UserProfileController::class, 'ppicture'])->name('profile_ppicture');
//    workaround: use unused url+rand if you want to use 2 invoke in one page
//    eg: profile.update = invoke1, profile_ktp = invoke2

//    Route::get('/user_management', [PageController::class, 'user_management'])->name('user_management');
//    Route::get('/new_user', [UserProfileController::class, 'show_new'])->name('show_new');
//    Route::post('/new_user', [UserProfileController::class, 'new'])->name('profile_new');

    Route::get('/credits', [PageController::class, 'credits'])->name('credits');

    Route::get('/user_management', [CUserManagement::class, 'index'])->name('user_management');
    Route::get('/new_user', [UserProfileController::class, 'show_new'])->name('show_new');
    Route::post('/new_user', [UserProfileController::class, 'new'])->name('profile_new');


    Route::get('/edituser', [EditProfileController::class, 'show'])->name('editeuser');
    Route::post('/edituser', [EditProfileController::class, 'updateuser'])->name('updateuser');
    Route::post('/edituser2', [EditProfileController::class, 'updateppicture'])->name('updateppicture');





    Route::get('/transaksiview', [TransaksiController::class, 'get'])->name('transaksiview');
    Route::get('/newtransaction', [TransaksiController::class, 'index'])->name('transaksiregister');
    Route::post('/newtransaction', [TransaksiController::class, 'create'])->name('transaksicreate');



    Route::get('/itemmanagement', [PageController::class, 'itemmanagement'])->name('itemmanagement');





	Route::get('/{page}', [PageController::class, 'index'])->name('page');
	Route::post('logout', [LoginController::class, 'logout'])->name('logout');
});
