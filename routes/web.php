<?php

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

// Route::get('/', function () {
//     return view('welcome');
// });

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Rute Login & Autentikasi Google
Route::get('/login', 'AuthController@loginView')->name('login');
Route::get('/auth/google', 'AuthController@redirectToGoogle')->name('google.login');
Route::get('/auth/google/callback', 'AuthController@handleGoogleCallback');

Route::middleware(['auth'])->group(function () {
    
    // Beranda / Home (Menampilkan Riwayat)
    Route::get('/', 'HomeController@index')->name('home');

    // Penjualan
    Route::get('/penjualan/tambah', 'PenjualanController@create')->name('penjualan.create');
    Route::post('/penjualan/simpan', 'PenjualanController@store')->name('penjualan.store');
    // TAMBAHKAN RUTE INI UNTUK MELIHAT DETAIL:
    Route::get('/penjualan/{id}/detail', 'PenjualanController@show')->name('penjualan.show');

    // RUTE BARU UNTUK EDIT & HAPUS
    Route::get('/penjualan/{id}/edit', 'PenjualanController@edit')->name('penjualan.edit');
    Route::put('/penjualan/{id}/update', 'PenjualanController@update')->name('penjualan.update');
    Route::delete('/penjualan/{id}/hapus', 'PenjualanController@destroy')->name('penjualan.destroy');

    // Rute Edit Penjualan
    Route::get('/penjualan/{id}/edit', 'PenjualanController@edit')->name('penjualan.edit');
    Route::put('/penjualan/{id}/update', 'PenjualanController@update')->name('penjualan.update');
    Route::get('/penjualan/{id}/cetak', 'PenjualanController@cetak')->name('penjualan.cetak');

    // Nelayan
    Route::resource('nelayan', 'NelayanController');

    // Laporan & Profil
    Route::get('/laporan', 'LaporanController@index')->name('laporan.index');
    Route::get('/profil', 'ProfilController@index')->name('profil.index');
    Route::get('/laporan/pdf', 'LaporanController@downloadPDF')->name('laporan.pdf');

    // Proses Logout
    Route::get('/logout', 'AuthController@logout')->name('logout');
});
